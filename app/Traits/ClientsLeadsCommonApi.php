<?php

namespace App\Traits;

use App\Http\Classes\Client;
use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\AdminClient\Client as ModelClient;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\Delivery;
use App\Http\Models\Common\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait ClientsLeadsCommonApi
{
    public function AddClient($params)
    {
        if (!isset($params['phone'])) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Клиент add. Телефон не задан']);
            return response('', 400);
        }
        $time_start_amo = microtime(true);
        $validator_amo = Validator::make($params, [
            'name' => 'nullable | string| max:255 ',
            'phone' => 'required | regex:/[0-9_]+/i ',
            'email' => 'string | max:255 | email | nullable ',
            'instagramm' => 'nullable | string | max:250 ',
        ]);

        if ($validator_amo->fails()) {
            $amo_response = ['result_amo' => false, 'errors_amo' => $validator_amo->errors()];
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Клиент add. Ошибки валидации [' . $validator_amo->errors()->first() . ']']);
        }

        $params['phone'] = Common::format_phone($params['phone']);

        $amo = new AmoCrm();

        //проверка контакта
        $phone = str_replace('_', '', $params['phone']);

        if (strlen($phone) == 11) {

            $amo_contact_search = $amo->search_contact($params['phone'], true);

            if ($amo_contact_search["status"] == "200") {
                if (count($amo_contact_search['_embedded']['contacts']) >= 1) {
                    $params['amo_client_id'] = $amo_contact_search['_embedded']['contacts'][0]['id'];
                    $amo_response = ['result_amo' => true, 'amo_client_id' => $params['amo_client_id']];
                }
            }
        }

        //создание контакта
        if (empty($params['amo_client_id'])) {

            if (!$validator_amo->fails()) {

                $amo_params = [
                    'name' => $params['name'] ?? false,
                    'phone' => $params['phone'] ?? false,
                    'email' => $params['email'] ?? false,
                ];

                if (!empty($params['instagramm']))
                    $amo_params['instagramm'] = $params['instagramm'];

                $res_amo = $amo->add_contact($amo_params, true);

                if ($res_amo["status"]) {
                    $params['amo_client_id'] = $res_amo['_embedded']['contacts'][0]['id'];
                    $amo_response = ['result_amo' => true, 'amo_client_id' => $params['amo_client_id']];
                } else {
                    Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Клиент добавление [' . $params['phone'] . ']. Ошибки создания контакта АМО [' . json_encode($res_amo) . ']']);
                    $amo_response = ['result_amo' => false, 'errors_amo' => $res_amo];
                }

            }
        }
        $endtime_amo = (float)microtime(true) - $time_start_amo;
        Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': ------- ВРЕМЯ  ВЫПОЛНЕНИЯ ADD_CLIENT AMO: ' .$endtime_amo. '-----' . '----']);

        $params['referal_code'] = Client::createReferalCode();
        $params['client_status_id'] = 3;
        $validator = Validator::make($params, [
            'name' => 'nullable | string| max:255',
            'second_name' => 'string | nullable | max:255 | nullable',
            'phone' => 'required | regex:/[0-9_]+/i | unique:clients',
            'email' => 'string | max:255 | email | nullable',
            'comments' => 'string | max:2000 | nullable',
            'referal_code' => 'string | nullable | max:15 | unique:clients',
            'status' => 'exists:client_statuses,id| nullable',
            'instagramm' => 'string | max:250 | nullable',
            'amo_client_id' => 'integer | nullable'
        ]);

        if ($validator->fails()) {
            $clients_response = ['result_client' => false, 'errors_client' => $validator->errors()];
            return array_merge($amo_response, $clients_response);
        }
        $time_start_bd = microtime(true);
        try {
            $client = ModelClient::create($params);
        } catch (QueryException $ex) {

            $clients_response = [
                'result_client' => false,
                'errors_client' => $ex->errorInfo[2]
            ];
        }

        $clients_response = [
            'result_client' => true,
            'new_client' => $client,
        ];

        if ($clients_response['result_client']) {
            $promocode = '';

            $promocode_name = Str::slug($client->name);
            $promocode_phone = substr($client->phone, -4);

            $promocode = $promocode_name . $promocode_phone;

            $i = 0;

            do {
                $check_promo = Bonus::where('promocode', $promocode)->first();

                if ($check_promo) {
                    $promocode .= (string)$i;
                    $i++;
                }

            } while ($check_promo && $i < 100);

            $endtime_bd = (float)microtime(true) - $time_start_bd;

            $bonuses = new Bonus();
            $bonuses->client_id = $clients_response['new_client']->uuid;
            $bonuses->promocode = $promocode;
            $bonuses->save();

            $clients_response['new_client']['promocode'] = $promocode;
        }
        return array_merge($amo_response, $clients_response);
    }

    public function SearchClientByPhone($phone)
    {
        $client = new Client(['phone'=>$phone]);
        return $client->searchAdvanced(['phone'=>$phone]);
    }

    public function AddLead($params)
    {

        $validator = Validator::make($params, [
            'client_id' => 'required | string | max:36',
            'state_id' => 'required | integer',
            'anketa_uuid' => 'required | string | max:36',
            'contact_id' => 'nullable | integer',
            'create_type' => 'required | string | in:auto,manual',
            'date_delivery' => 'nullable | string | max:10',
            'tag' => 'nullable | string| max:100 '
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Сделка add. Ошибки валидации [' . $validator->errors()->first() . ']']);
                return [
                    'result' => false,
                    'message' => $validator->errors()
                ];
            }
            return [
                'result' => false,
                'message' => $validator->errors()
            ];
        }

        $validator_amo = Validator::make($params, [
            'name'          => 'string | max:255 | nullable',
            'amo_state'         => 'integer | nullable',
            'anketa_view'   => 'string | max:500 | nullable',
            'phone'         => 'required | regex:/[0-9_]+/i',
            'email'         => 'string | max:255 | email | nullable | nullable',
            'date_delivery' => 'string | max:10 | nullable',
            'address_delivery' => 'string | max:500 | nullable',
            'address_back'  => 'string | max:500 | nullable',
            'time_delivery' => 'string | max:500 | nullable',
            'time_back'     => 'string | max:500 | nullable',
            'pvz_point_id' => 'nullable | string| max:100 ',
            'pvz_address' => 'nullable | string| max:100 ',
            'comment_d' => 'nullable | string| max:100 '
        ]);


        $amo_params = false;
        if(!$validator_amo->fails()) {

            if(!empty($params['name'])) $amo_params['name'] = $params['name'];
            if(isset($params['amo_state'])) $amo_params['state'] = $params['amo_state'];
            if(!empty($params['anketa_view'])) $amo_params['anketa_view'] = $params['anketa_view'];
            if(!empty($params['phone'])) $amo_params['phone'] = $params['phone'];
            if(!empty($params['email'])) $amo_params['email'] = $params['email'];
            if(!empty($params['date_delivery'])) $amo_params['date_delivery'] = $params['date_delivery'];

            //address_delivery либо курьер либо ПВЗ
            if(!empty($params['address_delivery'])) $amo_params['address_delivery'] = $params['address_delivery'];
            if(!empty($params['pvz_address'])) $amo_params['address_delivery'] = $params['pvz_address'];

            if(!empty($params['pvz_point_id'])) $amo_params['delivery_pvz'] = $params['pvz_point_id'];

            if(!empty($params['address_back'])) $amo_params['address_back'] = $params['address_back'];
            if(!empty($params['time_delivery'])) $amo_params['time_delivery'] = $params['time_delivery'];
            if(!empty($params['time_back'])) $amo_params['time_back'] = $params['time_back'];
            if(!empty($params['tag'])) {
                $amo_params['tags'][] = $params['tag'];
                $amo_params['delivery_comment'] = $params['tag'];
            }
            if(!empty($params['comment_d'])) {
                $amo_params['comment_d'] = $params['comment_d'];
            }

        } else {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Сделка add. Ошибки валидации [' . $validator_amo->errors()->first() . ']']);
            $return_lead['errors_amo'] = $validator_amo->errors();
        }

        $contact_id = !empty($params['contact_id']) ? $params['contact_id'] : false;

        $return_lead = Lead::addLeadAmo($params['client_id'], $params['anketa_uuid'], $params['create_type'], $params['state_id'], $params['date_delivery'], $params['tag'],$contact_id, $amo_params);

        if(!$return_lead['result_amo']) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Сделка add. Контакт. [' . $contact_id . '] Ошибка добаления сделки в amo[' . json_encode($return_lead['error']) . ']']);
        }

        if(!empty($return_lead['amo_lead_id']) && $return_lead['result_amo']) {
            $amo = new AmoCrm();
            $res_link_amo = $amo->link_lead_contact((int)$return_lead['amo_lead_id'], (int)$params['contact_id'], true);
            $return_link_amo = ['result_link_amo'=> $res_link_amo['status']];

        } else {

            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Сделка add. Ошибка привязки контакта ['. $contact_id .'] нет amo_lead_id']);
            $return_link_amo = ['result_link_amo' => false, 'errors_link_amo' => 'no amo_lead_id'];
        }


        if (!empty ($return_lead['new_lead'])) {
            $lead = $return_lead['new_lead'];
            $lead->amo_link_contact_id = (int)$params['contact_id'];
            $closeLeads = Lead::with('states', 'clients')->where(function ($query) use ($params) {
                $query->whereHas('states', function ($stylists) {
                    $stylists->where('id', 14);
                })
                    ->whereHas('clients', function($client) use ($params) {
                        $client->where('phone', $params['phone'] );
                    });
            })->get();
            if (count($closeLeads) > 0) {
                $lead->tags()->attach(6);
            }
            $lead->save();
            $return_res = ['result_lead' => true, 'new_lead' => $lead->toArray()];
        } else {
            $return_res = ['result_lead' => false];
        }

        return array_merge($return_res, $return_lead, $return_link_amo);
    }

}
