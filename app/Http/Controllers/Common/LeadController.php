<?php

namespace App\Http\Controllers\Common;

use App\Http\Classes\Client;
use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Admin\FeedbackController;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Filters\Leads\ListingFilter;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Trans\TransClient;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\AdminClient\Reanketa;
use App\Http\Models\Catalog\Tags;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\LeadRef;
use App\Http\Models\Common\Payments;
use App\Http\Models\Stock\StockProducts;
use App\Http\Requests\Common\LeadFromRequest;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use MoySklad\Entity\Status;
use Venturecraft\Revisionable\RevisionableTrait;
use Yajra\DataTables\Facades\DataTables;
use \App\Http\Models\AdminClient\Client as Clients;
use App\Services\AnswersAdapter;
use Illuminate\Support\Facades\Log;
use function GuzzleHttp\Psr7\uri_for;

/**
 * Контроллер по сделкам
 * Class LeadController
 * @package App\Http\Controllers\Common
 */
class LeadController extends Controller
{

    /**
     * Список сделок
     * @throws \Exception
     */
    public function list(ListingFilter $filters)
    {

        $lead = Lead::with('clients', 'payments', 'stylists', 'states')
            ->when(auth()->guard('admin')->user()->hasRole('stylist'), function ($q) {
                $q->where('stylist_id', auth()->guard('admin')->user()->getAuthIdentifier());
            })->filter($filters)->orderBy('created_at', 'desc')
            ->paginate(\request('perPage') ?? 20, ['*'], '', \request('page') ?? 1);

        return view(\request()->ajax() ? 'partials.admin.lead.listing.ajax' : 'admin.leads.index', [
            'title' => 'Сделки',
            'data' => $lead,
            'filtersData' => $filters->filtersData(),
            'manage' => auth()->guard('admin')->user()->hasRole('stylist') ? false : true,
            'stylists' => @['NO' => 'Стилист не назначен'] + @AdminUser::byRole('stylist')->pluck('name', 'id')->toArray(),
            'states' => @['NO' => 'Статус не назначен'] + LeadRef::orderBy('id')->whereNull('parent_id')->pluck('name', 'id')->toArray(),
            'tags' =>  @Tags::where('type', 'lead')->orderBy('id')->get()
        ]);
    }

    public function changeTag(Request $request)
    {
        $uuid = $request->uuidLead;
        $tagId = $request->tagId;
        $type = $request->type;
        $lead = Lead::with('tags')->find($uuid);
        if ($type == 'unselecting') {
            $lead->tags()->detach($tagId);
        } else if ($type == 'select') {
            $lead->tags()->attach($tagId);
        }
    }

    private function clear_content($content) {
        $content = strip_tags($content);
        $content = htmlentities($content);
        $content = htmlspecialchars($content);
        $content = preg_replace("/&#?[a-z0-9]{2,8};/i","",$content);
        return $content;
    }

    public function description(Request $request) {

        if(!$lead = Lead::find($request->lead_uuid)) return \response()->json(['result'=>false]);

        switch($request->comments_method) {
            case  'add_content':
                $description = preg_replace('~(?:\r?\n){2,}~', PHP_EOL, $lead->description);
            break;

            case 'save_content':

                $description = preg_replace('~(?:\r?\n){2,}~', PHP_EOL, $request->description);
                $description = self::clear_content($description);
                $lead->description = $description;
                $lead->save();

            break;

            case 'read_area':
                $description = preg_replace('(https?://[\w+?\.\w+]+[a-zA-Z0-9\~\!\@\#\$\%\^\&amp;\*\(\)_\-\=\+\\\/\?\:\;\'\.\/]+[\.]*[a-zA-Z0-9\/]+)', "<a href='$0' target='_blank'>$0</a>", nl2br($lead->description));
                $description = preg_replace('~\s\s+~', '&#160;', $description);
            break;

            default:
                $description = '';
            break;
        }

        return \response()->json(['content'=>$description, 'result'=>true]);

    }

    /**
     * Аналитика сделок
     */
    public function analytics()
    {
        $lead = Lead::with('states')->get();
        $statuses = LeadRef::get();

        return view('admin.analytics.index', [
            'title' => 'Статистика по сделкам',
            'data' => $lead,
            'statuses' => $statuses
        ]);
    }

    /**
     * Аналитика сделок
     */
    public function stylist(ListingFilter $filters)
    {
        $lead = Lead::with('clients', 'payments', 'stylists', 'states')
            ->when(auth()->guard('admin')->user()->hasRole('stylist'), function ($q) {
                $q->where('stylist_id', auth()->guard('admin')->user()->getAuthIdentifier());
            })->filter($filters)->orderBy('created_at', 'desc')->where('stylist_id', '<>', '')
            ->paginate(\request('perPage') ?? 20, ['*'], '', \request('page') ?? 1);

        return view(\request()->ajax() ? 'partials.admin.stylist-leads.ajax' : 'admin.stylist-leads.index', [
            'title' => 'Статистика по дедлайнам стилистов',
            'data' => $lead,
            'filtersData' => $filters->filtersData(),
            'filters' => $filters,
            'manage' => auth()->guard('admin')->user()->hasRole('stylist') ? false : true,
            'stylists' => @['NO' => 'Стилист не назначен'] + @AdminUser::byRole('stylist')->pluck('name', 'id')->toArray(),
            'states' => @['NO' => 'Статус не назначен'] + LeadRef::orderBy('id')->pluck('name', 'id')->toArray()
        ]);
    }

    public function create()
    {

        return view('admin.leads.create', [
            'title' => 'Создание новой сделки',
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make(request()->all(), [
            'phone' => 'required | string | max:36 ',
            'state_id' => 'required | integer',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if (!$client = Clients::where('phone', \request()->input('phone'))->first()) {
            toastr()->error('Клиент не найден!');
            return redirect()->back();
        }

        $amo = new AmoCrm();

        // Проверка что у клиента есть id контакта в АМО
        if (empty($client->amo_client_id)) {
            if ($client->amo_client_id = TransClient::toAmo($client))
                $client->save();
            else {
                toastr()->error('Нет клиентского контакта AMO!');
                return redirect()->back();
            }
        }

        // Создание в АМО сделки
        if (!$amo_lead_id = self::addLead($client)) {
            toastr()->error('Не получилось создать сделку!');
            return redirect()->back();
        }
//        $amo_lead_id = 454545;

        // Создание локальной сделки
        $lead = new Lead();
        $lead->client_id = @$client->uuid;
        $lead->amo_lead_id = @$amo_lead_id;
        $lead->client_num = Common::clintMaxNum($client->uuid);
        if ($anketa = Questionnaire::where('client_uuid', $client->uuid)->orderBy('id', 'DESC')->first()) {
            $lead->anketa_id = $anketa->id;
            $lead->anketa_uuid = $anketa->uuid ?? null;
        }
        $lead->state_id = \request()->input('state_id');
        $lead->save();


        $amo->link_lead_contact((int)$amo_lead_id, $client->amo_client_id);

        toastr()->success('Сделка создана!');
        return redirect()->route('leads.edit', $lead->uuid);

    }

    /**
     * Display the specified resource.
     *
     * @param Lead $lead
     * @return Response
     */
    public function show(Lead $lead)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LeadFromRequest $request
     * @param $uuid
     * @return void
     */
    public function edit(LeadFromRequest $request, $uuid)
    {


        if (!$item = Lead::with('ankets', 'questionnaire', 'delivery')->where('uuid', $uuid)->first()) {
            toastr()->error('Сделка не найдена');
            return redirect()->back();
        }

        if($item->questionnaire) {
            if (!isset($item->questionnaire['data']['anketa'])) {
                $adapter = new AnswersAdapter();
                $item->questionnaire['data'] = [
                    'anketa' => ['question' => $adapter->setAnketaShow($item->questionnaire['data'])],
                    'coupon' => @$item->questionnaire['data']['coupon']];
            }
        }

        try {
            $amo = new AmoCrm();

            if (\request()->post()) {

                if (auth()->guard('admin')->user()->hasPermission('edit-lead-deadline')) {
                    if (!empty(\request()->input('deadline_at'))) {
                        $item->deadline_at = \request()->input('deadline_at');
                    }
                }
// @TODO-uretral: q  -> решение об удалении закомментированного
                /*            try {
                                // Пока нет стилистов делаем так
                                if (!empty(\request()->input('stylist_id')) && !empty(\request()->input('amo_lead_id'))) {
                                    if ($item->stylist_id != (int)\request()->input('stylist_id')) {
                                        // Поиск стилиста
                                        $amo_name = AdminUser::find((int)\request()->input('stylist_id'))->amo_name;
                                        $amo = new AmoCrm();
                                        $pars = [
                                            'id' => (int)\request()->input('amo_lead_id'),
                                            'state' => 3,
                                            'stylist' => $amo_name
                                        ];
                                        $amo->update_lead($pars);
                                    }
                                }
                            } catch (\Exception $e) {
                                toastr()->error('Не заполнено имя стилиста АМО или проблема обновления сделки!');
                            }*/


                $amo_updates = ['id' => $item->amo_lead_id];


                // Стилист

                if ($item->stylist_id != \request()->input('stylist_id')) {

                    $item->stylist_id = \request()->input('stylist_id');
                    $amo_updates['stylist'] = is_null(\request()->input('stylist_id'))
                        ? null
                        : AdminUser::find((int)\request()->input('stylist_id'))->amo_name;
                }

                if (!empty(\request()->input('amo_lead_id'))) {
                    $item->amo_lead_id = \request()->input('amo_lead_id');
                }

                if (!empty(\request()->input('state_id')))
                    $item->state_id = \request()->input('state_id');

                // Интервал доставки!
                if (!empty(\request()->input('time_delivery'))) {
                    $item->data = $this->setJsonLidData($item, 'time_delivery', \request()->input('time_delivery'));
                    $amo_updates['time_delivery'] = $item->data['time_delivery'];
                }

                // Город доставки
                if (!empty(\request()->input('city_delivery'))) {
                    $item->data = $this->setJsonLidData($item, 'city_delivery', \request()->input('city_delivery'));
                }

                // Адрес доставки
                if (!empty(\request()->input('address_delivery'))) {
                    $item->data = $this->setJsonLidData($item, 'address_delivery', \request()->input('address_delivery'));
                    if (!empty(\request()->input('city_delivery'))) {
                        $amo_updates['address_delivery'] = $item->data['address_delivery'];
                        //$amo_updates['address_delivery'] = \request()->input('city_delivery') .' '. $item->data['address_delivery'];
                    } else {
                        $amo_updates['address_delivery'] = $item->data['address_delivery'];
                    }
                }

                // Адрес возврата
                if (!empty(\request()->input('address_back'))) {
                    $item->data = $this->setJsonLidData($item, 'address_back', \request()->input('address_back'));
                    if (!empty(\request()->input('city_delivery'))) {
                        $amo_updates['address_back'] = $item->data['address_back'];
                        //$amo_updates['address_delivery'] = \request()->input('city_delivery') .' '. $item->data['address_delivery'];
                    } else {
                        $amo_updates['address_back'] = $item->data['address_back'];
                    }
                }

                // Дата доставки
                if (!empty(\request()->input('delivery_at'))) {
                    $item->data = $this->setJsonLidData($item, 'date_delivery', \request()->input('delivery_at'));
                    $item->delivery_at = \request()->input('delivery_at');

                    if (!empty($item->delivery_at)) {
                        $amo_updates['date_delivery'] = Carbon::parse($item->delivery_at)->format('d.m.Y');
                    }
                }

                // Код купона
                if (!empty(\request()->input('coupon'))) {
                    $item->data = $this->setJsonLidData($item, 'coupon', \request()->input('coupon'));
                    $amo_updates['coupon'] = $item->data['coupon'];
                }

                // Дата возврата
                if (!empty(\request()->input('date_back'))) {
                    $item->data = $this->setJsonLidData($item, 'date_back', \request()->input('date_back'));

                }

                // Интервал возврата
                if (!empty(\request()->input('time_back'))) {
                    $item->data = $this->setJsonLidData($item, 'time_back', \request()->input('time_back'));
                    $amo_updates['time_back'] = $item->data['time_back'];
                }

                // Добавление тега с проверкой на отличие
                if ($item->tag != \request()->input('tag')) {
                    $item->tag = \request()->input('tag') ?? null;
                    if (!empty(\request()->input('tag')))
                        $amo->add_tags($item->amo_lead_id, [\request()->input('tag')]);
                }

                $item->description = \request()->input('description') ?? null;
                $item->substate_id = \request()->input('sub_state_id') ?? null;
                $item->summ = \request()->input('summ') ?? null;
                if(\request()->input('total')) {

                    $err_total = 0;

                    if(!$fb_main = FeedbackgeneralQuize::where('lead_id', $item->uuid)->latest()->first()) {
                        toastr()->error('total - фидбек не найден');
                        $err_total = 1;
                    }

                    if(!$feedbacks = $fb_main->feedbackgQuize->where('action_result','buy')) {
                        toastr()->error('total - клиент не выбрал ни одного продукта для покупки');
                        $err_total = 1;
                    }

                    $summ_buy_products = 0;
                    foreach ($feedbacks as $feedback) {
                        $summ_buy_products += $feedback->price;
                    }

                    if( (float)\request()->input('total') + (float)\request()->input('discount') != (float)$summ_buy_products) {
                        toastr()->error('total + discount не равно сумме всех позиций по товарам ' . $summ_buy_products);
                        $err_total = 1;
                    }

                    if($err_total == 0) {
                        $item->total = \request()->input('total') ?? null;
                        $item->discount = \request()->input('discount') ?? null;
                    }
                }

                $item->save();
                try {
                    $amo->update_lead($amo_updates);
                } catch (\Exception $e) {
                    toastr()->error('Ошибка сохранения в АМО CRM');
                }

                toastr()->success('Сделка сохранена!');
                return redirect()->back();
            }

            // Устанавливаем первичную дату доставки клиенту
            if (!empty($item->anketa_id) && empty($item->delivery_at)) {
                if ($anketa = Questionnaire::where('id', $item->anketa_id)->first()) {
                    if (isset($anketa->data['anketa']['question'][64]['answer'])) {
                        $item->delivery_at = @Carbon::parse($anketa->data['anketa']['question'][64]['answer'])->format('Y-m-d H:i:s');
                        //$item->save();
                    }
                }
            }

            //вывод адреса и города
            if (!empty($item->anketa_uuid)) {

                if ($anketa = Questionnaire::find($item->anketa_uuid)) {
                    if (!empty($anketa->data['anketa']['question'][71]['answer'])) {
                        $item->address = @$anketa->data['anketa']['question'][71]['option'][@$anketa->data['anketa']['question'][71]['answer']]['text'];
                    }
                    $item->address .= ' ' . @$anketa->data['anketa']['question'][63]['answer'];
                    $new_address = '';
                    if(isset($anketa->data['anketa']['question'][74]))
                        $new_address .= ' ' . @$anketa->data['anketa']['question'][74]['answer'];
                    if(isset($anketa->data['anketa']['question'][76]))
                        $new_address .= ' ' . @$anketa->data['anketa']['question'][76]['answer'];
                    if(isset($anketa->data['anketa']['question'][77]))
                        $new_address .= ' ' . @$anketa->data['anketa']['question'][77]['answer'];

                }
            }

            // TODO: Не вникал устранял баг на проде - переписать
            if (!empty($item->data['coupon']))
                $coupon = $item->data['coupon'];
            elseif (!empty(@$item->questionnaire) && isset($item->questionnaire->data['coupon']))
                $coupon = $item->questionnaire->data['coupon'];
            else
                $coupon = false;

            // ФИО из анкеты
            $fio = ($this->lidQuestionnaireParser($item, 'surname', 79, 'text') ? $this->lidQuestionnaireParser($item, 'surname', 79, 'text')['value'] : '') . ' '
                    . ($this->lidQuestionnaireParser($item, 'name', 0, 'text') ? $this->lidQuestionnaireParser($item, 'name', 0, 'text')['value'] : '') . ' '
                    . ($this->lidQuestionnaireParser($item, 'patronymic', 84, 'text') ? $this->lidQuestionnaireParser($item, 'patronymic', 84, 'text')['value'] : '');

            $address_delivery = $this->lidQuestionnaireParser($item, 'address_delivery', 63, 'text');

            if($item->questionnaire) {
                if ($address_delivery['value'] == '') {
                    $address_delivery['value'] = ($this->lidQuestionnaireParser($item, 'surname', 79, 'text') ? $this->lidQuestionnaireParser($item, 'surname', 74, 'text')['value'] : '') . ' '
                        . ($this->lidQuestionnaireParser($item, 'name', 0, 'text') ? $this->lidQuestionnaireParser($item, 'name', 76, 'text')['value'] : '') . ' '
                        . ($this->lidQuestionnaireParser($item, 'patronymic', 84, 'text') ? $this->lidQuestionnaireParser($item, 'patronymic', 77, 'text')['value'] : '');
                }

                if (@$address_delivery['value'] == @$anketa->data[63])
                    @$address_delivery['value'] .=  ' '. @$anketa->data[76] . ' ' . @$anketa->data[77];

            }

            $address_back = @$item['data']['address_back'] ?? @$address_delivery['value'];



            $anketaUuidLink = '';
            if ($item->anketa_uuid) {
                if ($item->questionnaire) {
                    $anketaUuidLink = route('anketa.show', $item->questionnaire->uuid);
                }
                if ($item->questionnaire->source == 'reanketa' || $item->questionnaire->source == 'sber_re') {
                    $reAnketaUuidLink = route('reanketa.show', $item->questionnaire->uuid);
                }
            }
            //  TODO: заплатка, на проде ошибка
            else{
                $anketa_tmp =  Questionnaire::whereClientUuid($item->client_id)->orderBy('created_at','desc')->first();
                if ($anketa_tmp)
                    $anketaUuidLink = route('anketa.show', $anketa_tmp->uuid);
            }

            $item->description = preg_replace('(https?://[\w+?\.\w+]+[a-zA-Z0-9\~\!\@\#\$\%\^\&amp;\*\(\)_\-\=\+\\\/\?\:\;\'\.\/]+[\.]*[a-zA-Z0-9\/]+)', "<a href='$0' class='description-link-temp' target='_blank'>$0</a>", nl2br($item->description, false));
            $item->description = preg_replace('~\s\s+~', '&#160;', $item->description);
            return view('admin.leads.edit', [
                'title' => 'Cделка: ' . @$item->amo_lead_id,
                'data' => $item,
                'stylists' => AdminUser::byRole('stylist')->pluck('name', 'id'),
                'clientData' => $this->guessClientData($item),
                'feedback_id' => FeedbackgeneralQuize::where('lead_id', $uuid)->latest()->first()->id ?? false,
                'manage' => auth()->guard('admin')->user()->hasRole('stylist') ? false : true,
                'states' => LeadRef::getList(auth()->guard('admin')->user()->roles[0]->slug),
                'time_delivery' => $this->lidQuestionnaireParser($item, 'time_delivery', 65, 'choice_single'),
                'time_back' => $this->lidQuestionnaireParser($item, 'time_back', 66, 'choice_single'),
                'address_delivery' => $address_delivery,
                'address_back' => $address_back ?? '',
                'city_delivery' => $this->lidQuestionnaireParser($item, 'city_delivery', 71, 'choice_single'),
                'clientFIO' => $fio ?? '',
                'coupon' => @$coupon,
                'new_address' => $new_address ?? '',
                'all_states' => LeadRef::all()->pluck('name','id'),
                'anketaUuidLink' => $anketaUuidLink,
                'reAnketaUuidLink' => $reAnketaUuidLink ?? ''
            ]);
        } catch (\Exception $e) {
            dd($e);
        }


    }

    public function guessClientData($item) {
        // socials
        try {
            if($item->clients && !$item->clients->socialmedia_links) {
                $anketa = Questionnaire::where('client_uuid',$item->clients->uuid)->whereNotNull('anketa')->first();
                if($anketa && array_key_exists('socials',$anketa->anketa) && !empty($anketa->anketa['socials'])) {
                    $item->clients->socialmedia_links = $anketa->anketa['socials'];
                }
            }
        } catch (\Exception $e) {
            Log::info($e);
        }

        return $item->clients ?? null;
    }


    /**
     * Полготовка массива для поля data
     * @param $lid
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setJsonLidData($lid, $key, $value)
    {
        $data = $lid->data;
        $data[$key] = $value;
        return $data;
    }

    /**
     * Парсит вопросы из анкеты по ключу и подготавливает к Вью и сохранению
     * @param $lid
     * @param $amoKey
     * @param $QKey
     * @param $QType
     * @return array|bool
     */
    public function lidQuestionnaireParser($lid, $amoKey, $QKey, $QType)
    {

        if (!empty($lid->questionnaire->data['anketa']['question'])) {
            $quest = $lid->questionnaire->data['anketa']['question'];
            $result = [
                'value' => null
            ];
            if (isset($lid->data[$amoKey])) {
                $result['value'] = $lid->data[$amoKey];
            } else if (isset($quest[$QKey]['answer'])) {
                if ($QType == 'check') {
                    $result['value'] = @$quest[$QKey]['answer'][0] ?? null;
                    /*if (is_array($quest[$QKey]['answer'])) {
                        $result['value'] = $quest[$QKey]['option'][$quest[$QKey]['answer'][0]]['text'] ?? null;
                    } else if ($quest[$QKey]['answer']) {
                        $result['value'] = $quest[$QKey]['option'][$quest[$QKey]['answer']]['text'] ?? null;
                    } else {
                        $result['value'] = null;
                    }*/
                } elseif ($QType == 'choice_single') {
                    $result['value'] = @$quest[$QKey]['option'][$quest[$QKey]['answer']]['text'] ?? null;
                } else {
                    $result['value'] = @$quest[$QKey]['answer'];
                }
            }

            $result['options'] = $quest[$QKey]['option'] ?? [];
            $result['placeholder'] = $quest[$QKey]['placeholder'] ?? null;

            return $result;
        }
        return '';


    }
    /*

    public function lidQuestionnaireParser($lid,$amoKey,$QKey,$QCheck = false) {
        $result = [
            'value' => null
        ];
        if(isset($lid->data[$amoKey])){
            $result['value'] = $lid->data[$amoKey];
        } else if(isset($lid->questionnaire->data['anketa']['question'][$QKey]['answer'])) {
            if($QCheck) {
                $result['value'] = $lid->questionnaire->data['anketa']['question'][$QKey]['answer'][0] ?? null;
            } else {
                $result['value'] = $lid->questionnaire->data['anketa']['question'][$QKey]['answer'];
            }
        }

        $result['options'] = $lid->questionnaire->data['anketa']['question'][$QKey]['option'] ?? [];
        $result['placeholder'] = $lid->questionnaire->data['anketa']['question'][$QKey]['placeholder'] ?? null;

        return $result;
    }*/

    /**
     * Запись в АМО даты доставки
     * @param int $amo_id
     * @param $delivery_at
     * @return bool|mixed
     */
    public function updateAmoDelivery(int $amo_id, $delivery_at)
    {

        if (!empty($amo_id) && !empty($delivery_at)) {
            $amo = new AmoCrm();
            $data = [
                'id' => $amo_id,
                'date_delivery' => Carbon::parse($delivery_at)->format('d.m.Y')
            ];
            return $amo->update_lead($data);
        }
        return false;
    }

    /**
     * Запись в АМО изменённых значений
     * @param int $amo_id
     * @param $delivery_at
     * @return bool|mixed
     */
    public function updateAmo($values)
    {

        if (count($values) && !empty($values['id'])) {
            $amo = new AmoCrm();
            return $amo->update_lead($values);
        }

        return false;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Lead $lead
     * @return Response
     */
    public function update(Request $request, Lead $lead)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $uuid
     * @return Response
     */
    public function destroy(string $uuid)
    {
        Lead::where('uuid', $uuid)->delete();
        toastr()->info('Сделка удалена');
        return redirect()->route('leads.list');
    }

    /**
     * Оплата по сделке
     * @param string $uuid
     * @return JsonResponse
     * @throws \Exception
     */
    public function payment(string $uuid)
    {
        $payments = Payments::with('leads');
        $payments = $payments->wherehas('leads', function ($q) use ($uuid) {
            $q->where('uuid', $uuid);
        });


        $dt = DataTables::eloquent($payments);

        $dt->editColumn('created_at', function ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
        });

        $dt->editColumn('client', function ($data) {
            return @$data->leads->clients->name;
        });

        $dt->addColumn('action', function ($data) {
            $buttons = '';

            //$buttons .='<a href = "'. route('payments.edit',$data->id) .'" title = "Редактирование оплаты'. $data->id .'" ><button class="btn btn-dark btn-sm px-1 ml-1" ><i class="fa fa-pencil-square-o" aria-hidden= "true" ></i ></button ></a >';

            if (auth()->guard('admin')->user()->hasPermission('destroy-payments'))
                $buttons .= '<a data-route-destroy = "' . route('payments.destroy', @$data->id) . '" href = "' . route('payments.destroy', $data->id) . '"  class="ml-3 modal-payment-delete" title = "Удаление оплаты ' . $data->id . '" ><i class="fa far fa-trash-alt text-danger"></i></a >';

            return $buttons;
        });

        return $dt->make(true);
    }

    /**
     * Получение списка подстатусов Ajax
     * @param int $state_id
     * @return JsonResponse
     */
    public function subStates(int $state_id)
    {

        if (!$list = LeadRef::getSubList(auth()->guard('admin')->user()->roles[0]->slug, $state_id))
            return \response()->json([
                'result' => false,
                'message' => 'Нет списка подстатусов для статуса: ' . $state_id,
            ], 200);

        return \response()->json([
            'result' => true,
            'data' => $list
        ], 200);

    }

    /**
     * Поиск клиентов по номеру телефона
     * @return JsonResponse
     */
    public function clientSearch()
    {

        if (!$list = Clients::where('phone', 'like', '%' . \request()->input('term') . '%')->limit(10)->get())
            return \response()->json([
                'result' => false,
                'message' => 'Нет списка клиентов',
            ], 200);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id' => $item->uuid,
                'label' => $item->phone . ': ' . $item->name,
                'value' => $item->phone
            ];
        }

        return \response()->json($data, 200);
    }

    /**
     * Созадть сделку
     * @param $client
     * @return bool
     */
    protected function addLead($client)
    {

        $data = [
            'name' => $client->name ?? '',
            'phone' => $client->phone ?? '',
            'email' => $client->email ?? '',
        ];

        $amo = new AmoCrm();
        $resp = $amo->add_lead($data, true);

        if (isset($resp['_embedded']['leads'][0]['id']))
            return $resp['_embedded']['leads'][0]['id'];

        return false;

    }

    /**
     * Страница оплаты
     * @param $lead_uuid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function payLink($lead_uuid)
    {

        if (!$lead = Lead::with('clients')->find($lead_uuid))
            $error_message = "Произошла ошибка обратитесь к администратору";

        if (empty($lead->client_id))
            $error_message = "Произошла ошибка обратитесь к администратору";

        $lead_summ = '';
        if ($lead) {
            $lead_summ = $lead->summ;

            //заказ уже был оплачен
            $payment = Payments::where('lead_id', $lead->uuid)->first();
            if ($payment) $error_message = "Заказ уже был оплачен";
        }

        return view('admin.payments.pay-order-submit', [
            'title' => 'Оплатить сумму: ' . $lead_summ . ' за услуги стилиста',
            'error_message' => $error_message ?? '',
            'data' => $lead
        ]);

    }

    public function bulkAction(ListingFilter $filters){

        dump('filters',$filters);

        $state_id = \request()->input('action');
        $items = \request()->input('data');
        if (!empty($items) && $state_id >= 0){
            foreach ($items as $k=>$v){
                $lead = Lead::whereUuid($v)->first();
                $lead->state_id = $state_id;
                $lead->save();
            }
        }


        $lead = Lead::with('clients', 'payments', 'stylists', 'states')
            ->when(auth()->guard('admin')->user()->hasRole('stylist'), function ($q) {
                $q->where('stylist_id', auth()->guard('admin')->user()->getAuthIdentifier());
            })->filter($filters)->orderBy('created_at', 'desc')
            ->paginate(\request('perPage') ?? 20, ['*'], '', \request('page') ?? 1);

        return view(\request()->ajax() ? 'partials.admin.lead.listing.ajax' : 'admin.leads.index', [
            'title' => 'Сделки',
            'data' => $lead,
            'fil' => typeOf($filters),
            'filtersData' => $filters->filtersData(),
            'manage' => auth()->guard('admin')->user()->hasRole('stylist') ? false : true,
            'stylists' => @['NO' => 'Стилист не назначен'] + @AdminUser::byRole('stylist')->pluck('name', 'id')->toArray(),
            'states' => @['NO' => 'Статус не назначен'] + LeadRef::orderBy('id')->pluck('name', 'id')->toArray()
        ]);

        return redirect()->route('leads.list');

    }

    public function XSLListing(Request $request)
    {
        $csv = new \App\Http\Classes\DataConversion\LeadToCsv($request->items, $request->type);
        return json_encode($csv->make(), true, JSON_UNESCAPED_UNICODE);
    }
}
