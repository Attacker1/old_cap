<?php

namespace App\Http\Controllers\Vuex\Anketa;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Classes\Anketa\QuestionsVuexIO;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\Delivery;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Utm;
use App\Http\Models\Vuex\Anketa\AnketaBuilder;
use App\Http\Models\Vuex\Anketa\AnketaQuestion;
use App\Http\Models\Common\Coupon;
use App\Http\Models\Common\City;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;
use App\Services\ClientService;
use App\Services\LeadService;
use App\Services\Payments\CloudPayments;
use App\Traits\VuexAutoMethods;
use Carbon\Carbon;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Ixudra\Curl\Facades\Curl;
use Cookie;
class AnketaController extends Controller
{
    use VuexAutoMethods;

    private $uuid;
    private $answers;
    private $questions;
    private $localStorage;
    private $queue;
    private $client_uuid;
    private $coupon;
    private $clientPhotos;
    private $urlQuery;
    private $deliveryService;
    private $amoLeadId;


    public function index()
    {

        if (is_null(\request('t')) && \request()->path() !== 'success') {
            return $this->initialsCheckings();
        }

        $backend = [
            'payment' => [
                'MD' => \request('MD') ?: '',
                'PaRes' => \request('PaRes') ?: '',
                'cloudPaymentPublicId' => config('config.CLOUD_PAYMENT_PUBLIC_ID'),
                'cloudPaymentResultPage' => config('config.CLOUD_PAYMENT_RESULT_PAGE'),
                'tinkoffTerminalKey' => config('config.TINKOFF_TERMINAL_KEY')
            ]
        ];

        return view('vuex.anketa.frontend', ['backend' => json_encode($backend)]);
    }



    public function initialsCheckings(): \Illuminate\Http\RedirectResponse
    {

        $queryStr = explode('&',\request()->getQueryString());

        array_unshift($queryStr,'t='.$this->guessAnketaSlug());
        array_unshift($queryStr,'/');
        return redirect()->route('anketa.frontend',$queryStr)->with('message', '');
    }

    /**
     * @requests [anketaSlug, local ]
     * @return array
     */



    public function initAnketa(): array
    {
        try {
            $this->localStorage = is_null(request('local')) ? [] : json_decode(request('local'), true);
            $this->getAnswers();
            $this->getQuestions();
            $this->getQueue();
            $this->getClientPhotos();



            return [
                'uuid' => $this->uuid,
                'answers' => $this->answers,
                'questions' => $this->questions,
                'queue' => $this->queue,
                'local' => $this->localStorage,
                'img_1' => $this->clientPhotos[0],
                'img_2' => $this->clientPhotos[1],
                'img_3' => $this->clientPhotos[2],
            ];
        } catch (\Exception $e) {
            dd($e);
        }

    }

    public function guessAnketaSlug()
    {
        return AnketaBuilder::where('is_current', 1)->first()->slug;
    }

    public function getQuestions()
    {
        if (request('variantId')) {
            $anketaBuilder = AnketaBuilder::whereId(request('variantId'))->first();
        } elseif (request('anketaSlug')) {
            $anketaBuilder = AnketaBuilder::where('slug', request('anketaSlug'))->first();
        } else {
            $anketaBuilder = AnketaBuilder::where('is_current', 1)->first();
        }

        $isSub = AnketaQuestion::whereIsSub(1)->pluck('id')->toArray();
        $arrId = array_merge($anketaBuilder->questions, $isSub);

        $idsOrdered = implode(',', $arrId);

        $this->questions = AnketaQuestion::with(['type', 'options', 'tables.optionsPrints', 'subQuestions'])
            ->whereIn('id', $arrId)
            ->orderByRaw("FIELD(id, $idsOrdered)")
            ->get();
    }


    public function getAnswers()
    {
        if (is_array($this->localStorage)) {

            $needle = array_filter($this->localStorage, function ($innerArray) {
                return ($innerArray['anketaSlug'] == request('anketaSlug'));
            });

            $current = array_shift($needle);

//            dd($current['uuid']);

            if ($current && Questionnaire::whereUuid($current['uuid'])->first()) {

                $this->uuid = $current['uuid'];
                $convertOut = new QuestionsVuexIO();
                $this->answers = $convertOut->outputAnketa($this->uuid);

            } else {

                array_push($this->localStorage, $this->newAnswers());

            }

        } else {
            $this->localStorage = [$this->newAnswers()];
        }

    }

    public function getQueue()
    {

        if ((array)$this->answers) { // isset($this->answers['lastQuestionSlug'])
            $arrQuestionsIds = AnketaBuilder::questions(request('anketaSlug'));
            $lastQuestionId = AnketaQuestion::id($this->answers['lastQuestionSlug']);
            $try = array_search($lastQuestionId, $arrQuestionsIds);
            if ($try !== false) {
                $this->queue = $try;
            } else {
                $recursiveParents = AnketaQuestionOption::with('recursiveParent')->where('next_question', $lastQuestionId)->first();

                $this->queue = ($parentiId = $this->findParentQueue($recursiveParents, 0))
                    ? array_search($parentiId, $arrQuestionsIds)
                    : 0;
            }
        } else {
            $this->queue = 0;
        }


    }

    public function findParentQueue($recursiveParent, $cnt)
    {
        if ($cnt++ > 20) {
            return 0;
        } // ограничение рекурсивности (менеджеры могут зациклить )
        // @TODO-uretral:  -> запретить зацикливать вопросы в ветке
        return is_null($recursiveParent->recursiveParent)
            ? $recursiveParent->question_id
            : $this->findParentQueue($recursiveParent->recursiveParent, $cnt);
    }

    private function newAnswers(): array
    {
        Log::notice('newAnswers');
        $this->answers = json_decode('{}');
        $this->uuid = Questionnaire::create()->uuid;

        $this->urlQuery = \request('urlQuery') ?? [];
        $this->utm($this->uuid);
        return [
            'anketaSlug' => request('anketaSlug'),
            'uuid' => $this->uuid
        ];
    }


    public function getSampleQuestions()
    {
        $anketaBuilder = AnketaBuilder::where('is_current', 1)->first();
        $isSub = AnketaQuestion::whereIsSub(1)->pluck('id')->toArray();
        $arrId = array_merge($anketaBuilder->questions, $isSub);
        $idsOrdered = implode(',', $arrId);
        return AnketaQuestion::with(['type', 'options', 'tables.optionsPrints', 'subQuestions'])
            ->whereNotIn('id', $anketaBuilder->questions)
            ->get();
    }

    public function getCities()
    {
        return strlen(request('cityLike')) >= 1
            ? City::whereNull('region')->where('name', 'like', '%' . request('cityLike') . '%')->get()
            : [];

    }

    public function fetchLeadUuid()
    {
        $lead = Lead::whereAnketaUuid(\request('uuid'))->first();
        return $lead ? $lead->uuid : null;
    }

    public function save()
    {

        $this->urlQuery = \request('urlQuery') ?? [];

        try {
            $convert = new QuestionsVuexIO();

            $data = $convert->inputAnketa(request('data'));

            try {
                $oldData = $convert->reSaveAnketa($data['result']);
            } catch (\Exception $e) {
                Log::error($e);
            }

//            dd($data['result']);

            try {
                $this->client_uuid = array_key_exists('phone', $data['result'])
                    ? $this->client($data['result'])
                    : NULL;

                $questionnare = Questionnaire::where('uuid', request('uuid'))
                    ->update([
                        'data' => $oldData,
                        'anketa' => $data['result'],
                        'client_uuid' => $this->client_uuid,
                        'is_test' => 1, // request('capsulaTest') ?? NULL
                        'is_new' => 1
                    ]);

                $this->lead(request('uuid'), $data);


                return $questionnare;
            } catch (\Exception $e) {
                Log::error($e);
            }

        } catch (\Exception $e) {
//            dd($e);
        }
    }

    public function guessDelivery($data) {

        $deliveryKey = array_key_exists('delivery', $data) && isset($data['delivery'][0])
            ? $data['delivery'][0]
            :  null;
        $deliveryTypeKey = array_key_exists('deliveryType', $data) && isset($data['deliveryType'][0])
            ? $data['deliveryType'][0]
            : null;

        if($deliveryKey === 388) {
            $this->deliveryService = 'boxberry';
        } else {
            if($deliveryTypeKey === 389){
                $this->deliveryService = 'logsis';
            }
            if($deliveryTypeKey === 390){
                $this->deliveryService = 'boxberry';
            }
        }

    }

    public function lead($questionnare_uuid, $data)
    {


        if (!$this->client_uuid)
            return false;

        if (array_key_exists('deliveryBackTime', $data['result']) || array_key_exists('boxberryPoint', $data['result'])) {

            $lead = Lead::whereAnketaUuid($questionnare_uuid)->first();

            $this->guessDelivery($data['result']);

            if ($lead) { // update

                $payload = [];
                $payload['data'] = $this->leadData($data['result']);
                $payload['tag'] = $this->deliveryService;
                $payload['delivery_at'] = array_key_exists('deliveryDate', $data['result']) ? $data['result']['deliveryDate'] : null;
                $amount = array_key_exists('amount', $data['result']) ? $data['result']['amount'] : null;
                $payload['state_id'] = $payload['data']['coupon'] && $amount === 0 ? 2 : 1;
                $this->pvz($lead->uuid, $data['result']);

                Lead::whereUuid($lead->uuid)->update($payload);

                $amo = new AmoCrm();

                $amoLeadPayload = $this->amoLead($data['result']);
                $amoLeadPayload['id'] = $lead->amo_lead_id;
                $amoLeadPayload['amo_id'] = (string)$lead->amo_lead_id;
                $amoLeadPayload['state'] = $payload['state_id'];
                $amoLeadPayload['paid_price'] = $payload['data']['coupon'] && $amount === 0 ? 0 : null;

                if(array_key_exists('is_new', $data)) $amoLeadPayload['tags']['is_new'] = $data;

                $amo->update_lead($amoLeadPayload);

            } else { // create
                $amoLeadPayload = $this->amoLead($data['result']);

                $service = new LeadService();
                try {
                    $service->create($this->client_uuid, ['anketa_uuid' => $questionnare_uuid])
                        ->amoAddLead($amoLeadPayload);
                } catch (\Exception $e) {
                    dd($e);
                }
            }

        }
    }


    public function leadData($data) : array {
        $leadData = [];

        if(array_key_exists('coupon', $data)) {
            $leadData['coupon'] = $data['coupon']; }// "coupon": "1MORE",
        elseif(array_key_exists('rf', $data))
            $leadData['coupon'] = $data['rf'];

        if(array_key_exists('deliveryDate', $data)) {
            $leadData['date_back'] = Carbon::parse($data['deliveryDate'])->addDay()->format('Y-m-d') ; }// "date_back": "2021-10-31",

        if(array_key_exists('deliveryBackTime', $data)) {
            $leadData['time_back'] = $this->getOption($data['deliveryBackTime'][0])->text; } // "time_back": "14:00 - 18:00",

        if(array_key_exists('address', $data)) {
            $leadData['address_back'] = $this->address($data); }// "address_back": "Красногорск, ул.Егорова Д.3, 6 Консьерж",

        if(array_key_exists('delivery', $data)) {
            $leadData['city_delivery'] = $this->getOption($data['delivery']['0'])->text; }// "city_delivery": "Москва и Московская область",

        if(array_key_exists('deliveryDate', $data)) {
            $leadData['date_delivery'] = $data['deliveryDate']; }// "date_delivery": "2021-10-30",

        if(array_key_exists('deliveryTime', $data)) {
            $leadData['time_delivery'] = $this->getOption($data['deliveryTime'][0])->text; }// "time_delivery": "14:00 - 18:00",

        if(array_key_exists('address', $data)) {
            $leadData['address_delivery'] = $this->address($data); }// "address_delivery": "Красногорск, ул.Егорова Д.3, 6 Консьерж"

        return $leadData;

    }

    public function address($data) : string {
        $address = [];
        if(array_key_exists('address',$data)) { $address[] = $data['address'];}
        if(array_key_exists('addressOffice',$data)) { $address[] = $data['addressOffice'];}
        if(array_key_exists('addressComment',$data)) { $address[] = $data['addressComment'];}
        return implode(' ', $address);
    }

    public function getOption($id) : AnketaQuestionOption {
        return AnketaQuestionOption::whereId($id)->first();
    }

    public function amoLead($data) : array {

        $amoLeadPayload = [];



        if($this->deliveryService == 'logsis') {
            $amoLeadPayload = $this->leadData($data);
            if(!$amoLeadPayload['coupon']) {
                unset($amoLeadPayload['coupon']);
            }
            $amoLeadPayload['tags']['name'] = 'LOGSIS';
        }

        if($this->deliveryService == 'boxberry') {
            $leadData = $this->leadData($data);
            if(isset($leadData['coupon'])) {
                $amoLeadPayload['coupon'] = $leadData['coupon'];

            }
            $amoLeadPayload['address_delivery'] = $data['pvz_address'];
            $amoLeadPayload['delivery_pvz'] = $data['pvz_id'];
            $amoLeadPayload['comment_d'] = 'boxberry';
            $amoLeadPayload['tags']['name'] = 'BOXBERRY';
        }
        // dostavka
        if ($this->deliveryService) {
            $amoLeadPayload['partner'] = $this->deliveryService;

        }

//        name

        if(\request('uuid')) {

            if($question = Questionnaire::whereUuid(\request('uuid'))->first()) {

                if($client = Client::where('uuid', $question->client_uuid)->first()) {
                    $amoLeadPayload['name'] = $client->name;
                }

                $arr_answers  = array_intersect_key( $question->anketa, [
                    'whatPurpose'=>'',
                    'bioHeight'=>'',
                    'bioWeight'=>'',
                    'sizeTop'=>'',
                    'sizeBottom'=>'',
                    'howMuchToSpendOnBlouseShirt'=>'',
                    'howMuchToSpendOnSweaterJumperPullover'=>'',
                    'howMuchToSpendOnDressesSundresses'=>'',
                    'howMuchToSpendOnJacket'=>'',
                    'howMuchToSpendOnBags'=>'',
                    'howMuchToSpendOnJeansTrousersSkirts'=>'',
                    'howMuchToSpendOnEarringsNecklacesBracelets'=>'',
                    'howMuchToSpendOnBeltsScarvesShawls'=>''
                ]);

                foreach ($arr_answers as $q_key => $answer) {
                    if(is_array($answer)) {
                        $arr_answers_to_amo = [];
                        foreach ($answer as $answer_item) {

                            if ($questionOptionText = AnketaQuestionOption::find($answer_item, 'text')) {
                                $arr_answers_to_amo[] = $questionOptionText->text;
                            } elseif($q_key == "whatPurpose") {
                                //(цель подборки- другое)
                                $arr_answers_to_amo[] = $answer_item;
                            }

                        }
                        $amoLeadPayload[$q_key] = implode('; ', $arr_answers_to_amo);
                    }

                    if(is_string($answer)) {
                        $amoLeadPayload[$q_key] = $answer;
                    }
                }

            }
        }

        // tags
        if(isset($leadData['is_new'])) {
            $amoLeadPayload['is_new'] = $leadData['is_new'];

        }

        return $amoLeadPayload;
    }


    public function pvz($leadUuid, $data) {
        if(array_key_exists('pvz_id', $data)) {
            $payload = [];
            $payload['source'] = 'BOXBERRY';
            $payload['delivery_point_id'] = $data['pvz_id'];
            if(array_key_exists('pvz_address',$data)) { $payload['delivery_address'] = $data['pvz_address'];}

            return Delivery::updateOrCreate(['lead_id' => $leadUuid],$payload);
        }
    }

    public function utm($anketa_uuid){
        $arr = [
            'anketa_uuid' => $anketa_uuid ?? null,
            'amo_id' => null,
            'lead_uuid' => null,
            'utm_source' => $this->urlQuery['utm_source'] ?? null,
            'utm_medium' => $this->urlQuery['utm_medium'] ?? null,
            'utm_campaign' => $this->urlQuery['utm_campaign'] ?? null,
            'utm_content' => $this->urlQuery['utm_content'] ?? null,
            'utm_term' => $this->urlQuery['utm_term'] ?? null,
        ];

        return Utm::create($arr);

    }

    //для однотипности создания сделки в повторной и первичной
    public function temp_reanketa_lead(Request $request) {
        $this->client_uuid = $request->client_uuid;
        $data = $request->data;
        $data['is_new'] = 'ПОВТОР';

        if(!$lead = Lead::whereAnketaUuid($request->questionnare_uuid)->first()) {
            $this->lead($request->questionnare_uuid, $data);//create
            $this->lead($request->questionnare_uuid, $data);//update
        } else {
            $this->lead($request->questionnare_uuid, $data);//update
        }

        if($lead) {
            return response()->json([
                'result' => true,
                'new_lead' => ['uuid' => $lead->uuid]
            ]);
        }
        return response()->json([
            'result' => false,
        ]);
    }

    public function temp_reanketa_client(Request $request) {
        if($client = Client::find($request->client_uuid)) {
            $this->client_uuid = $request->client_uuid;

            $data = $request->data;
            $data['phone'] = $client->phone;
            $this->client($data);

            return response()->json([
                'result' => true,
            ]);
        }

        return response()->json([
            'result' => false,
            'message' => 'client not found'
        ]);

    }

    public function saveClientPhotos(): array
    {
        $response = [];

        foreach (request('images') as $key => $image) {

            try {
                $base_path = config('config.ANKETA_CLIENT_PHOTO_PATH');
                $base_filename = request('anketaId');
                $postfix = '_' . $key;
                $ext = '.jpg';

                $dir_length = config('config.ANKETA_DIR_LENGTH');
                $dir = substr($base_filename, 0, $dir_length ?? 3 );
                $path = "$base_path/$dir/$base_filename" . $postfix . $ext;

                if (preg_match('/^data:image\/(\w+);base64,/', $image)) {
                    $data = substr($image, strpos($image, ',') + 1);
                    $data = base64_decode($data);

                    if (Storage::disk('public')->put($path, $data)) {
                        array_push($response, $path);
                    }

                } else
                    array_push($response, $path);


            }catch (\Exception $e){
                Log::channel('anketa_arr')->critical('Запись фото: ' . $e->getMessage());
                array_push($response, $path);
            }
        }

        return $response;

    }

    public function getClientPhotos() {
        if($this->answers && array_key_exists('clientPhotos', (array)$this->answers)){
            $this->clientPhotos =  $this->answers['clientPhotos']['forms'];
        } else {
            $this->clientPhotos = ['','',''];
        }

    }

    // @TODO-uretral: отдельный класс для работыс купонами и промокодами

    public function checkBonus()
    {
        return Bonus::where('promocode', request('code'))->first();
    }

    public function checkCoupon()
    {
        if ($coupon = Coupon::whereName(request('code'))->first()) {
            return $coupon;
        } else {
            if ($bonus = $this->checkBonus()) {
                $bonus->price = 490;
                $bonus->name = $bonus->promocode;
                //return response()->json(['price' => 490]);
                return $bonus;
            }
        }

    }


    public function test($payload)
    {
        return $payload;
    }


    // anketa variants

    public function getBuilderData(): array
    {
        $buildedQuestions = AnketaBuilder::where('slug', request('anketaSlug'))->first();
        return $this->getLists($buildedQuestions->questions ?? []);
    }

    public function updateBuilderData(): array
    {
        $questionsId = [];
        foreach (request('anketaList') as $question) {
            $questionsId[] = $question['id'];
        }
        AnketaBuilder::where('slug', request('anketaSlug'))->update(['questions' => $questionsId]);
        return $this->getLists($questionsId);
    }

    private function getLists($questionsId): array
    {

        $with = ['recursiveOptions', 'options', 'tables.optionsPrints'];
        $isArr = false;
        $idsOrdered = '';
        if (is_array($questionsId) && count($questionsId)) {
            $idsOrdered = implode(',', $questionsId);
            $isArr = true;
        }

        return [
            'anketaList' => $isArr ? AnketaQuestion::with($with)->whereIn('id', $questionsId)->orderByRaw("FIELD(id, $idsOrdered)")->get() : [],
            'questionsList' => $isArr ? AnketaQuestion::with($with)->orWhereNotIn('id', $questionsId)->get() : AnketaQuestion::with($with)->get(),
        ];
    }

    protected function getQuestionsList(): array
    {
        return ['dict' => AnketaQuestion::with(['options', 'tables.optionsPrints'])->select(['id', 'slug', 'question'])->get()->keyBy('id')];
    }

    // client

    public function client($data)
    {
        Log::channel('anketa_err')->info("Клиент данные: перед созданием:" . json_encode($data));

        $phone = '7' . preg_replace("/[^,.0-9]/", '', $data['phone']);

        $arr = [];

        if (!empty($data['name']))
            $arr['name'] = @$data['name'];

        if (array_key_exists('bioName', $data)) {
            $arr['name'] = $data['bioName'];
        }
        if (array_key_exists('bioSurname', $data)) {
            $arr['second_name'] = $data['bioSurname'];
        }
        if (array_key_exists('bioPatronymic', $data)) {
            $arr['patronymic'] = $data['bioPatronymic'];
        }
        if (array_key_exists('phone', $data)) {
            $arr['phone'] = $phone;
        }
        if (array_key_exists('email', $data)) {
            $arr['email'] = $data['email'];
        }
        if (array_key_exists('address', $data)) {
            $arr['address'] = $data['address'] . ' ' . $data['addressOffice'];
        }
        if (array_key_exists('socials', $data)) {
            $arr['socialmedia_links'] = $data['socials'];
            if(empty($arr['socialmedia_links'])) $arr['socialmedia_links'] = '';
        }

        $client = (new ClientService())
            ->createOrUpdate($data['phone'], $arr)
            ->amoFirstOrNew()
            ->get();


//        $client = Client::updateOrCreate(['phone' => $phone], $arr);

        return $client->uuid;
    }


    public function fetchClientIp(): ?string
    {
        return request()->ip();
    }

    public function payment()
    {
        $payment = new CloudPayments(\request());
        return $payment->paymentCharge();
    }

    public function payment3d()
    {
        $payment = new CloudPayments(\request());
        return $payment->threeDSecure();
    }

    public function googlePayWidget(){
        $payment = new CloudPayments(\request());
        return $payment->googlePayWidget();
    }

    public function paymentTinkoff(){

    }





}
