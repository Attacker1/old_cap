<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Anketa\QuestionDataToAnketa;
use App\Http\Classes\DataConversion\AnketaXlsShort;
use App\Http\Controllers\Admin\Anketa\TabPhotoSupportController;
use App\Http\Controllers\Admin\Anketa\TabReferenceController;
use \App\Http\Controllers\Controller;
use App\Http\Filters\Questionnaire\ListingFilter;
use App\Http\Models\AdminClient\AnketaStylistComment;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Payments;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Helpers\AnketaHelper;
use Illuminate\Http\Request;
use App\Http\Models\Common\Lead;
use App\Http\Models\Admin\AdminUser;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AnketaController extends Controller
{
    use AnketaHelper;

    private $anketa_data;
    private $anketa_row_data;

    public function list(Request $request)
    {
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            return redirect()->route('admin.main.index');
        }

        if (request()->ajax()) {
            $arr_columns = [
                1 => 'uuid',
                2 => 'amount',
                3 => 'created_at',
                4 => 'updated_at'];

            $request_params = $request->all();

            $order_column = $arr_columns[$request_params['order'][0]['column']];
            $order_dir = $request_params['order'][0]['dir'];

            $pagination_start = $request_params['start'];

            $pagination_length = $request_params['length'];

            $search_value = $request_params['search']['value'];

            session()->forget('anketa_data');
            session()->push('anketa_data', [
                'order_column' => $request_params['order'][0]['column'],
                'order_dir' => $order_dir,
                'search_value' => $search_value,
                'limit_menu' => $pagination_length,
                'paging_start' => $pagination_start
            ]);

            $anketaModal = new Questionnaire();

            $anketa = $anketaModal->select($arr_columns);

            if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
                $stylist_id = auth()->guard('admin')->user()->id;

                //находим клиента по анкете
                if ($leads = Lead::where('stylist_id', $stylist_id)->get()) {
                    $arr_anketa_ids = [];
                    foreach ($leads as $lead){
                        $client_uuid = $lead->client_id;
                        if($client_leads= Lead::where('client_id', $client_uuid)->get()) {
                            $arr_anketa_ids = array_merge($arr_anketa_ids, $client_leads->pluck('anketa_uuid')->toArray());
                        }
                    }

                }

                $anketa = $anketa->whereIn('uuid', $arr_anketa_ids);
            }

            if ($search_value != '') {
                $anketa = $anketa
                    ->where('uuid', 'LIKE', "%{$search_value}%")
                    ->orWhere('amount', 'LIKE', "%{$search_value}%");

                $recordsFiltered = $anketa->count();
            }

            $anketa = $anketa
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)
                ->get()
                ->toArray();

            /*$anketa
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)->dump();*/

            if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {

                $recordsTotal = Questionnaire:: whereIn('uuid', $arr_anketa_ids)->count();

            } else $recordsTotal = Questionnaire::count();

            $recordsFiltered = $recordsFiltered ?? $recordsTotal;

            $res = [
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $anketa,
                'temp' => session()->get('anketa_data')
            ];

            return json_encode($res);
        }

        if (session('status_success')) {
            toastr()->success(session('status_success'));
        }

        if (session('status_error')) {
            toastr()->error(session('status_error'));
        }

        $datatable_params[0] = [
            'order_column' => '3',
            'order_dir' => 'desc',
            'search_value' => '',
            'limit_menu' => '10',
            'paging_start' => 0
        ];

        if (session()->has('anketa_data')) {
            $datatable_params = session()->get('anketa_data', 'default');
        }

        return view('admin.anketa.list', [
            'title' => 'Управление анкетами',
            'datatable_params' => $datatable_params[0]
        ]);
    }

    public function reset_datatable_settings()
    {
        session()->forget('anketa_data');
        return redirect()->route('admin.anketa.list.fill');
    }

    public function show ($uuid)
    {

        // @TODO-uretral:  -> Упростить метод
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            toastr()->error('Нет прав на просмотр анкеты');
            return redirect()->route('admin.main.index');
        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {

            //находим клиента по анкете
            if ($lead = Lead:: where('anketa_uuid', $uuid)->first()) {
                $client_uuid = $lead->client_id;
                if (!Lead:: where('stylist_id', auth()->guard('admin')->user()->id)->where('client_id', $client_uuid)->first()) {
                    toastr()->error('Нет прав на просмотр анкеты');
                    return redirect()->route('admin.anketa.list.fill');
                }
            } else {
                toastr()->error('Сделка не найдена');
                return redirect()->route('admin.anketa.list.fill');
            }
        }

        if (!$questionnaire = Questionnaire::find($uuid)) return redirect()->route('admin.anketa.list.fill');

        $anketaComments = AnketaStylistComment::where('anketa_uuid', $uuid)->get();
        // все сделки по этой анкете и клиенту
        $lids = '';
        try {
            $lids = Lead::where('client_id', $questionnaire->client_uuid)->where('anketa_uuid', $questionnaire->uuid)->get()->implode('amo_lead_id', ', ');
        } catch (\Exception $e) {
            Log::info($e, ['ошибка выборки лидов по client_id', self::class, 'show']);
        }

        $this->anketa_row_data = $questionnaire->anketa;
        $this->create_data();

        $fotos_url = [];
        if(isset($questionnaire->anketa['clientPhotos'])) {
            $fotos_url = $this->createFotoPaths($questionnaire->uuid, $questionnaire->anketa['clientPhotos']);
        }

        return view('admin.anketa.show', [
            'anketa' => $this->anketa_data,
            'fotos_url' => $fotos_url,
            'client' => $questionnaire->client,
            'amo_ids' => $lids,
            'anketaComments' => $anketaComments,
            'title' => 'Просмотр анкеты',
            'uuid' => $uuid,
            'tab_reference' => TabReferenceController::show($uuid),
            'support_photo' => TabPhotoSupportController::show($uuid),
            'is_new' => !empty($questionnaire->is_new)
        ]);
    }

    public function show_old ($amo_id) {
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            toastr()->error('Нет прав на просмотр анкеты');
        }

        if(! $anketa = DB::table('anketa')
            ->where('amo_id', $amo_id)
            ->latest()
            ->first()) return 'анкета не найдена';;


        $questionDataToAnketa = new QuestionDataToAnketa();
        $this->anketa_row_data = $questionDataToAnketa->convertFirstOld($anketa->amo_id);

        $this->create_data();

        return view('admin.anketa.show', [
            'anketa' => $this->anketa_data,
            'fotos_url' => [],
            'client' => null,
            'amo_ids' => '',
            'anketaComments' => [],
            'title' => 'Просмотр старой анкеты',
            'uuid' => '',
            'tab_reference' => TabReferenceController::show(''),
            'support_photo' => TabPhotoSupportController::show(''),
            'is_new' => false,
            'is_old' => true
        ]);

    }

    private function create_data()
    {
        $anketa = [];
        $whatPurposeText = '';

        if(array_key_exists('whatPurpose', $this->anketa_row_data)) {
            if (is_array($this->anketa_row_data['whatPurpose'])) {

                foreach ($this->anketa_row_data['whatPurpose'] as $val) {
                    if (!is_numeric($val)) {
                        $whatPurposeText = $val;
                    }
                }
            }
        }

        foreach($this->anketa_row_data as $key => $item) {

            if(is_string($item)) {
                $type = 'text';
                $answer = $item;
            } elseif(is_array($item)) {
                $answer = '';
                $option_text = '';

                $image_data = '';
                $arr_answer = [];
                $arr_prints_dislike = [];
                $arr_pallete = [];

                if($q_images = AnketaQuestion::where('slug', $key)->first())
                    $image_data = $q_images->style_image;

                for($i = 0; $i < count($item); $i++ ) {

                    if(!$option = AnketaQuestionOption::find($item[$i])) {
                        //exception
                        continue;
                    }

                    $option_text = str_replace('<br>', '',$option->text);

                    switch($key) {
                        case 'styleOnWeekend':
                        case 'styleOnWork':
                            $option_text = str_replace('Стилей же больше! ', '' , $option_text);
                        break;
                        case 'earsPierced':
                            $option_text = str_replace('Да', 'Проколоты/' , $option_text);
                            $option_text = str_replace('Нет', 'Не проколоты/' , $option_text);
                        break;
                        case 'bijouterie':
                            $option_text = str_replace('Да', 'Готова к биж/' , $option_text);
                            $option_text = str_replace('Нет', '' , $option_text);
                        break;
                        case 'jewelry':
                            $option_text = str_replace('50/50', 'золото и серебро' , $option_text);
                        break;

                        case 'tryOtherOrSaveStyle':
                            $option_text = str_replace('50/50 - действуем аккуратно', 'клиент хотел бы остаться в своем стиле, но не против небольших изменений/' , $option_text);
                        break;

                        case 'printsDislike':
                            if($q = AnketaQuestionOption::find($item[$i])) {
                                if($q->image) {
                                    $arr_prints_dislike[] = [
                                        'answer'=> $option_text,
                                        'image' => config('config.ANKETA_URL').'/storage/'. $q->image];
                                }
                            }
                        break;

                        case 'choosingPalletes25':
                            if($q = AnketaQuestionOption::find($item[$i])) {
                                $arr_pallete[] =[
                                    'pallete' => $q->pallete,
                                    'answer' => $option_text];
                            }
                        break;

                        case 'noColor':
                            if($q = AnketaQuestionOption::find($item[$i])) {
                                $arr_noColor[] =[
                                    'pallete' => $q->pallete,
                                    'answer' => $option_text];
                            }
                            break;
                    }

                    if(strpos($key, 'choosingStyle') === 0) {
                        if($q = AnketaQuestionOption::find($item[$i])) {
                            if ($q->text) {
                                $arr_answer['text'] = $q->text;
                            }
                            $arr_answer['answer'][] = $q->option_key;
                            if($image_data!='') $arr_answer['image'] = config('config.ANKETA_URL').'/storage/'.$image_data;
                        }
                    }

                    switch($key) {
                        case 'styleOnWeekend' :
                        case 'styleOnWork' :
                        case 'whatPurpose' :
                            $answer .= $option_text . '<br> ';
//                        . $whatPurposeText . '<br> '
                        break;

                        default : if(!empty($option_text) && strpos($key, 'choosingStyle') !== 0) $answer .= $option_text . ', ';
                    }
                }
            }



            if($key == 'printsDislike') {
                if (!empty($arr_prints_dislike)) $anketa['printsDislike_arr'] = $arr_prints_dislike;
            }
            if($key == 'choosingPalletes25') {
                if( !empty($arr_pallete) ) $anketa['pallete_arr'] = $arr_pallete;
            }
            if($key == 'noColor') {
                if( !empty($arr_noColor) ) $anketa['noColor_arr'] = $arr_noColor;
            }

            if (!empty($arr_answer) && !is_string($item)) {$anketa[$key] = $arr_answer;}
            else $anketa[$key] = trim($answer ?? '', ', ');
        }

        if(array_key_exists('whatPurpose', $anketa) ) {
            $anketa['whatPurpose'] .= $whatPurposeText;
        }


        $allQuestions = AnketaQuestion::all()->pluck('slug');
        foreach ($allQuestions as $q) {
            if(!isset($anketa[$q])) {
                $anketa[$q] = '';
            }
        }
        $anketa['prices'] = '';
        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnBlouseShirt'])
            ? 'блуза/ рубашки: ' . $anketa['howMuchToSpendOnBlouseShirt'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnSweaterJumperPullover'])
            ? 'свитер/ джемпер: ' . $anketa['howMuchToSpendOnSweaterJumperPullover'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnDressesSundresses'])
            ? 'платья/ сарафаны: ' . $anketa['howMuchToSpendOnDressesSundresses'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnJacket'])
            ? 'жакет/ пиджак: ' . $anketa['howMuchToSpendOnJacket'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnJeansTrousersSkirts'])
            ? 'джинсы/ брюки/ юбки: ' . $anketa['howMuchToSpendOnJeansTrousersSkirts'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnBags'])
            ? 'сумки: ' . $anketa['howMuchToSpendOnBags'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnEarringsNecklacesBracelets'])
            ? 'серьги/ браслеты: ' . $anketa['howMuchToSpendOnEarringsNecklacesBracelets'] . '<br>'
            : '';

        $anketa['prices'] .= !empty($anketa['howMuchToSpendOnBeltsScarvesShawls'])
            ? 'ремни / шарфы / платки: ' . $anketa['howMuchToSpendOnBeltsScarvesShawls'] . '<br>'
            : '';

        $anketa['prices'] = str_replace(['рублей', ', '], '' , $anketa['prices']);

        $anketa['topSizeStyle'] = $anketa['sizeTop'] . '/ ' . $anketa['aboutTopStyle'];

        $anketa['bottomSizeStyle'] = $anketa['sizeBottom'] . '/ ' . $anketa['aboutBottomStyle'];

        //рост
        if(!empty($anketa['bioHeight'])) {
            $anketa['bioHeight'] .= ' '. $this->calculateHeight((integer)trim($anketa['bioHeight'])) . '/';
        }
        //возраст
        if(!empty($anketa['bioBirth'])) {
            $anketa['bioBirth'] = $this->calculateAge($anketa['bioBirth']).'/';
        }

        //замеры
        $og = trim($anketa['bioChest']  ?? '');
        $ot = trim($anketa['bioWaist']  ?? '');
        $ob = trim($anketa['bioHips']  ?? '');

        $anketa['chestWeistHips'] = $og . '/' . $ot . '/' .$ob;

        $anketa['figura'] = $this->calculateFigura($og, $ot, $ob);
        if(!isset($anketa['bioName']))  $anketa['bioName'] = '';
        $this->anketa_data = $anketa;

    }

    public function edit($uuid)
    {

        $stylist_id = auth()->guard('admin')->user()->id;

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', $stylist_id)->where('anketa_uuid', $uuid)->count();
            if ($check === 0) return redirect()->route('admin.anketa.list.fill');
        }


        $anketaData = $anketaComments = null;
        $anketaData = Questionnaire::find($uuid);
        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $anketaComments = AnketaStylistComment::where('anketa_uuid', $uuid)->where('stylist_id', $stylist_id)->first();
        }

        $stylistsData = $stylistSelected = null;
        if (auth()->guard('admin')->user()->can('manage-anketa')) {
            $stylistsData = AdminUser::whereHas('roles', function ($query) {
                $query->where('id', '3');
            })->get();
            $lead = Lead::where('anketa_uuid', $uuid)->first();
            if ($lead) {
                $stylistSelected = $lead->stylist_id;
            }
        }

        return view('admin.anketa.edit', [
            'anketa_uuid' => $uuid,
            'anketaData' => $anketaData,
            'anketaComments' => $anketaComments,
            'stylistsData' => $stylistsData,
            'stylistSelected' => $stylistSelected,
            'title' => 'Редактирование анкеты'
        ]);
    }

    public function update(Request $request, $anketa_uuid)
    {

        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            return redirect()->route('admin.main.index');
        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', auth()->guard('admin')->user()->id)->where('anketa_uuid', $anketa_uuid)->count();
            if ($check === 0) return redirect()->route('admin.anketa.list.fill');
        }

        if (auth()->guard('admin')->user()->can('manage-anketa')) {
            /*$validated = $request->validate([
                'stylists' => 'required'
            ]);*/

            $success = null;

            $lead = Lead::where('anketa_uuid', $anketa_uuid)->first();
            if ($lead) {
                $lead->stylist_id = $request->input('stylists');
                $lead->save();
                toastr()->success('Стилист прикреплен к анкете');
            } else toastr()->error('Стилист не прикреплен. Сделка не найдена');

            $anketa = Questionnaire::find($anketa_uuid);
            if ($anketa) {
                $anketa->manager_comment = $request->input('manager_comment');
                $anketa->save();
                toastr()->success('Комментарий сохранен');
            } else toastr()->error('Комментарий не сохранен');

            return redirect()->route('anketa.edit', $anketa_uuid);

        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {

            $validated = $request->validate([
                'content' => 'required'
            ]);

            $stylist_id = auth()->guard('admin')->user()->id;

            $comment = AnketaStylistComment::updateOrCreate([
                'anketa_uuid' => $anketa_uuid,
                'stylist_id' => $stylist_id
            ],

                [
                    'content' => $request->input('content')
                ]);

            toastr()->success('Данные успешно сохранены');

            return redirect()->route('anketa.edit', $anketa_uuid);

        }
    }

    public function destroy($anketa_uuid)
    {
        if (!auth()->guard('admin')->user()->can('manage-anketa') && !auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            return redirect()->route('admin.main.index');
        }

        if (auth()->guard('admin')->user()->can('viewing-anketa-list-own')) {
            $check = Lead:: where('stylist_id', auth()->guard('admin')->user()->id)->where('anketa_uuid', $anketa_uuid)->count();
            if ($check === 0) return redirect()->route('admin.anketa.list.fill');
        }

        $res = Questionnaire::where('uuid', $anketa_uuid)->delete();

        if ($res) toastr()->success('Запись успешно удалена');
        else toastr()->error('Ошибка удаления');

        return redirect()->route('admin.anketa.list.fill');
    }

    /**
     * Новый список по Анкетам
     */
    public function listing(ListingFilter $filters)
    {

        $manage = !auth()->guard('admin')->user()->hasRole('stylist');

        $questionnaire = Questionnaire::with('hasClient','hasLids')
            ->when(!$manage, function ($q) { // если менеджер
                $userId = auth()->guard('admin')->user()->getAuthIdentifier();
                $questsUuidArr = Lead::where('stylist_id', $userId)->whereNotNull('anketa_uuid')->pluck('anketa_uuid');
                return $q->whereIn('uuid', $questsUuidArr);
            })
            ->filter($filters)
            ->paginate(\request('perPage') ?? 20, ['*'], '', \request('page') ?? 1);

        return view(\request()->ajax() ? 'partials.admin.anketa.listing.ajax' : 'admin.anketa.listing', [
            'title' => 'Анкеты',
            'manage' => $manage,
            'data' => $questionnaire,
            'filtersData' => $filters->filtersData(),
        ]);
    }

    /**
     * Список оплат по клиенту
     * @param $client_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function payments($client_id)
    {

        $leads = Lead::where('client_id', $client_id)->pluck('uuid');
        $query = Payments::whereIn('lead_id', $leads);

        $dt = DataTables::eloquent($query);

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
     * Список анкет по клиенту
     * @param $client_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function ajax($client_id)
    {


        $query = Questionnaire::with('leads')->where('client_uuid', $client_id);

        $dt = DataTables::eloquent($query);

        $dt->editColumn('created_at', function ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
        });

        $dt->editColumn('id', function ($data) {
            return @$data->id;
        });

        $dt->addColumn('stylist', function ($data) {
            return @AdminUser::where('id', $data->leads->stylist_id)->first()->name ?? '—';
        });

        $dt->addColumn('action', function ($data) {
            $buttons = '';
            $buttons .= '<a href = "/admin/anketa/' . $data->uuid . '" title = "Просмотр анкеты" class="ml-5" target="_blank"><i class="fa far fa-eye text-primary"></i></a >';
            return $buttons;
        });

        return $dt->make(true);

    }

    /**
     * Список обратной связи по клиенту
     * @param $client_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function feedback($client_id)
    {


        $query = FeedbackgeneralQuize::where('client_uuid', $client_id);

        $dt = DataTables::eloquent($query);

        $dt->editColumn('created_at', function ($data) {
            return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y');
        });

        $dt->editColumn('id', function ($data) {
            return @$data->id;
        });

        $dt->addColumn('action', function ($data) {
            $buttons = '';

            $buttons .= '<a href = "/admin/feedback/' . $data->id . '" title = "Просмотр обратной связи" class="ml-5" target="_blank"><i class="fa far fa-eye text-primary"></i></a >';

            return $buttons;
        });

        return $dt->make(true);

    }

    public function XSLListingWhole(Request $request)
    {
        $csv = new \App\Http\Classes\DataConversion\AnketaToCsv($request->perPage, $request->pageNr);
        return $csv->make();
    }

    public function XSLListingShort(Request $request)
    {
        $collection = new AnketaXlsShort($request->perPage, $request->pageNr);
        return $collection->make();
    }

    private function photoAdapter($item){

        $new = !empty($item->is_new) ? 1 : 0;
        $base_path = config('config.ANKETA_URL');

        switch ($new) {

            case 0:
                if (!$item->code)
                    return false;
                $dir = '/upload/';
                $base_filename = $item->code;
                $ext = false;
                $i = 1; $j = 3;
                break;

            case 1:
                // TODO: Убрать избыточность с Anketa/AnketaController
                $base_path = $base_path . "/storage/" . config('config.ANKETA_CLIENT_PHOTO_PATH');
                $dir = "/" . substr($item->uuid, 0, config('config.ANKETA_DIR_LENGTH') ) . "/";
                $base_filename = $item->uuid;
                $ext = '.jpg';
                $i = 0;
                $j = 2;
                break;

            default:
               return false;
        }

        $path = $base_path . $dir .  $base_filename . '_';

        for ($i; $i <= $j; $i++) {
            if (@fopen($path . $i . $ext, 'r'))
                $fotos_url[] = $path . $i . $ext;
        }

        return  !empty($fotos_url)
            ? $fotos_url
            : false;


    }


}
