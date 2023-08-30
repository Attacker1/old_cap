<?php

namespace App\Http\Controllers\AdminClients;

use App\Http\Classes\Anketa\QuestionsVuexIO;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\AdminClient\Reanketa;
use App\Http\Models\Common\Coupon;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\City;
use App\Http\Models\Common\Utm;
use App\Traits\ClientsLeadsCommonApi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Services\AnswersAdapter;
use App\Http\Models\Vuex\Anketa\AnketaQuestionOption;


class ReanketaController extends Controller
{
    use ClientsLeadsCommonApi;

    private function converter($request_anketa, $added = []) {

        $vuesIO = new QuestionsVuexIO();
        $vuexConverter = $vuesIO->oldAdapter;

        $anketaQuestionOption = new AnketaQuestionOption();
        $vuexConverter1 = $vuexConverter;
        foreach($vuexConverter1 as $key => $item) {
            if(isset($item['keys'])) {
                $vuexConverter[$key]['keys'] = [];
                for($i = 0 ; $i < count($item['keys']); $i++) {
                    if($options = $anketaQuestionOption->find($item['keys'][$i])) {
                        $vuexConverter[$key]['keys'][$options->option_key] = $item['keys'][$i];
                    }
                }
            }
        }

        foreach ($request_anketa as $key => $value ) {

            if($value !== null && $value !== [] && $value!== "NULL" && $key != 'whatChangeElse') {
                $answer = [];
                if(is_array($value)) {

                    if (isset($vuexConverter[$key])) {
                        for ($i = 0; $i < count($value); $i++) {
                            if (isset($vuexConverter[$key]['keys'][$value[$i]]))
                                $answer[$i] = $vuexConverter[$key]['keys'][ $value[$i]];
                        }
                    }
                } elseif (is_numeric($value) || $value == 0) {
                    if(isset($vuexConverter[$key])) {
                        if(is_array($vuexConverter[$key])) {
                            if(isset($vuexConverter[$key]['keys'][$value]))
                                $answer[0] = $vuexConverter[$key]['keys'][$value];
                        }
                    }
                }

                if($answer!=[]) $new_anketa[$key] = $answer;
                else $new_anketa[$key] = $value;
            }
        }

        return array_merge($added, $new_anketa);
    }

    public function new(Request $request) {

        $client = Auth::guard('admin-clients')->user();
        $params['data'] = json_encode($request->anketa);
        $validator = Validator::make($params, [
            'data' => 'required|json'
        ]);

        if($validator->fails()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Повторная анкета add. Ошибки валидации [' . $validator->errors()->first() . ']']);
            return response()->json(['result' => false, 'errors' => $validator->errors()]);
        }

        $adapter = new AnswersAdapter('reanketa');
        $data1 = $adapter->setReanketaAnswers($request->anketa);

        $data = (object)[];;

        for($i = 0; $i < count($data1); $i++) {
            $data->{(string)$i} = $data1[$i];
        }

        $reanketa = new Questionnaire();

        $reanketa->data = $data;

        $new_anketa = [
            'email' => $client->email,
            'phone' => $client->phone
        ];

        $reanketa->anketa = self::converter($request->anketa, $new_anketa);

        $reanketa->client_uuid = $client->uuid;
        $reanketa->source = "reanketa";

        try {
            $reanketa->save();
        } catch (QueryException $ex) {
            @Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Повторная анкета add ошибка сохранения ' . $ex->errorInfo[2] ]);
            return response()->json(['result' => false, 'errors' => 'ошибка сохранения']);
        }

        //utms

        if(session()->has('utms')) {
            $arr_utms = session()->get('utms');
            $arr_utms = $arr_utms[0];

            try {

                $utm = Utm::create([
                    'anketa_uuid' => $reanketa->uuid,
                    'utm_source' => $arr_utms['utm_source'] ?? null,
                    'utm_medium' => $arr_utms['utm_medium'] ?? null,
                    'utm_campaign' => $arr_utms['utm_campaign'] ?? null,
                    'utm_content' => $arr_utms['utm_content'] ?? null,
                    'utm_term' => $arr_utms['utm_term'] ?? null
                ]);
            } catch (\Exception $e) {
                Log::error($e);
            }
            session()->forget('utms');
        }

        $paymentAmount = 790;
        //была ли анкета
        if($lead = Lead::where('client_id', $client->uuid)->whereNotNull('anketa_uuid')->latest()->first())
        {
            //расчет суммы оплаты
            $carbon_todaySub3 = Carbon::now()->subMonths(3);
            $carbon_todaySub6 = Carbon::now()->subMonths(6);
            $carbon_lastAnketa = Carbon::parse($lead->created_at);
            //прошло меньше 3х мес
            if($carbon_lastAnketa->gte($carbon_todaySub3)) $paymentAmount = 290;
            else {
                //прошло от 3х мес до 6 мес
                if($carbon_lastAnketa->gte($carbon_todaySub6)) $paymentAmount = 490;
            }
        } else @Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Повторная анкета add сделка с последней анкетой не найдена ' . $ex->errorInfo[2] ]);

        return response()->json([
            'result' => true,
            'uuid' => $reanketa->uuid,
            'client' => [
                'uuid' => $client->uuid,
                'name' => $client->name,
                'second_name' => $client->second_name,
                'paymentAmount' => $paymentAmount,
                'patronymic' => $client->patronymic,
                'phone'	=> $client->phone,
                'email'	=> $client->email,
            ]
        ]);
    }

    /*
     * сохранение анкеты
     */

    public function save(Request $request) {

        $params = $request->all();
        $params['data'] = json_encode($request->anketa);

        $validator = Validator::make($params, [
            'data' => 'required|json',
            'anketa_uuid' => 'required|uuid',
            'frame' => 'required|string|max:150',
            'coupon' => "nullable | string"
        ]);

        if($validator->fails()) {
            @Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Повторная анкета save. Ошибки валидации [' . $validator->errors()->first() . ']']);
            return response()->json(['result' => false, 'errors' => $validator->errors()]);
        }

        if(!$anketa = Questionnaire::find($params['anketa_uuid'])) {
            @Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Повторная анкета save. Повторная анкета uuid ' . $request->anketa_uuid . ' не найдена']);
            return response()->json(['result' => false, 'errors' => 'anketa not found']);
        }

        $adapter = new AnswersAdapter('reanketa');
        $data1 = $adapter->setReanketaAnswers($request->anketa);

        $data = (object)[];;

        for($i = 0; $i < count($data1); $i++) {
            $data->{(string)$i} = $data1[$i];
        }

        $added = [];
        if(isset($request->coupon)) {
            $data->{'coupon'} = $request->coupon;
            $added['coupon'] = $request->coupon;
        }

        $anketa->data = $data;

        $anketa->anketa = self::converter($request->anketa, $added);

        $anketa->save();
        return response()->json(['result' => true]);

    }

    /*
     * выбор города если не Мск/Питер, возвращает список городов
     */
    public function getOtherCities() {
        $cities= City::whereNull('region')->get();
        $arr_return = [];
        foreach ($cities as $city) {
            $arr_return[] = [
                'id' => $city->id,
                'name' => $city->name
            ];
        }
        return response()->json(['result' => true, 'cities' => $arr_return]);
    }

    /*
     * Создание сделки
     */
    public function createLead(Request $request) {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|uuid|exists:questionnaires',
            'delivery_type' => "nullable | string",
            'date_delivery'=> 'nullable | string| max:255',
            'address_delivery' => 'nullable | string| max:500',
            'time_delivery' => 'nullable | string| max:255',
            'time_back' => 'nullable | string| max:255',
            'pvz_point_id' => 'nullable | string| max:255',
            'pvz_address' => 'nullable | string| max:255',
            'tag' => 'nullable | string| max:255'
        ]);

        if($validator->fails()) {
            @Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Reanketa. Ошибки валидации [' . $validator->errors()->first() . ']']);
            return response()->json(['result_lead' => false, 'errors_lead' => $validator->errors()]);
        }

        //данные клиента
        $client = Auth::guard('admin-clients')->user();

        $client_data = [

            'name' => $client->name,
            'phone'	=> $client->phone,
            'email'	=> $client->email,
            'contact_id' => $client->amo_client_id
        ];

        $lead_params = [
            'client_id' => $client->uuid,
            'anketa_uuid' => $request->uuid,
            'contact_id' => $client->amo_client_id,
            'state_id' => 1,
            'name' => $client->name,
            'amo_state' => 1,
            'create_type'   => 'auto',
            'anketa_view'	=> '',
            'phone'	=> $client->phone,
            'email'	=> $client->email,
            'date_delivery'	=> $request->date_delivery ?? '',
            'address_delivery' => trim($request->address_delivery) ?? '',
            'address_back'	=> $request->address_back ?? '',
            'time_delivery' => $request->time_delivery ?? '',
            'time_back'		=> $request->time_back ?? '',
            'pvz_point_id' => $request->pvz_point_id ?? '',
            'pvz_address' => $request->pvz_address ?? '',
            'comment_d' => $request->comment_d ?? '',
            'tag' => $request->tag ?? ''
        ];

        @Log::channel('customlog')->info($lead_params);

        // Добавление сделки
        $res_leads = self::AddLead($lead_params);

        return response()->json($res_leads);
    }

    /*
     * Проверка купона
     */
    public function checkCoupon(Request $request) {
        $validator = Validator::make($request->all(), [
            'amount' => 'required | integer',
            'coupon_name' => "required | string"
        ]);

        if($validator->fails()) return response()->json(['result' => false, 'errors' => $validator->errors()]);
        //$request->amount = 990;
        $coupon = Coupon::where('name', $request->coupon_name)->first();
        if(!$coupon) return response()->json(['result' => false, 'errors' =>"not found"]);

        $coupon = $coupon->only('name', 'price');

        if( in_array($coupon['name'], ['1MORE', 'AGAIN', '2CHANCE', 'BESTFRIEND', 'PSBOR', 'WARDROBE', 'CAP30', 'CAP60', 'CAP90', 'FUTURE', 'AWGIF'])  && $coupon['price'] == 0)
            $return = ['result' => true, 'coupon' => $coupon];
        else if($request->amount == 790) $return = ['result' => true, 'coupon' => $coupon];
        else return response()->json(['result' => false, 'errors' =>"not found for this amount"]);

        return response()->json($return);
    }

};