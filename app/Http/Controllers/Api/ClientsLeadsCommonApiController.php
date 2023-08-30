<?php

namespace App\Http\Controllers\Api;

use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Utm;
use Illuminate\Http\Request;
use App\Traits\ClientsLeadsCommonApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ClientsLeadsCommonApiController extends Controller
{
    use ClientsLeadsCommonApi;

    public function make(Request $request)
    {

        $time_start = microtime(true);
        $params = $request->all();
        if(!is_array($params)) return response('', 400);

        $time_start_client = microtime(true);
        $client = self::AddClient($params);
        $endtime_client = (float)microtime(true) - $time_start_client;
        //Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': ------- ВРЕМЯ  ВЫПОЛНЕНИЯ ADD_CLIENT: ' .$endtime_client. '-----' . @$res_leads['new_lead']['uuid'] . '----']);

        if($client['result_amo']) $id_amo_contact = $client['amo_client_id'];

        if($client['result_client']) {
            $client_uuid = $client['new_client']['uuid'];
            if(empty($id_amo_contact)) $id_amo_contact = $client['new_client']['amo_client_id'];
        } else {

            // проверка существование клиента
            if(strlen($params['phone']) >= 11) {
                $clients = self::SearchClientByPhone($params['phone']);

                if($clients['result']) {
                    if(count($clients['search_advanced_result']) == 1) {
                        $client_uuid = $clients['search_advanced_result'][0]['uuid'];
                        if(empty($id_amo_contact)) $id_amo_contact = $clients['search_advanced_result'][0]['amo_client_id'];
                    }
                }
            }
        }

        $lead_params = [
            'client_id' => $client_uuid ?? '',
            'anketa_uuid' => $params['anketa_uuid'],
            'contact_id' => $id_amo_contact ?? '',
            'state_id' => 1,
            'name'			=> $params['name'],
            'amo_state'			=> 1,
            'create_type'   => 'auto',
            'anketa_view'	=> $params['anketa_view'],
            'phone'			=> $params['phone'],
            'email'			=> $params['email'],
            'date_delivery'	=> $params['date_delivery'],
            'address_delivery' => $params['address_back'], //$params['address_delivery'],
            'address_back'	=> $params['address_back'],
            'time_delivery' => $params['time_delivery'],
            'time_back'		=> $params['time_back'],
            'pvz_point_id' => $params['pvz_point_id'],
            'pvz_address' => $params['pvz_address'],
            'tag' => $params['tag']
        ];

        @Log::channel('customlog')->info($lead_params);

        // Добавление сделки
        $res_leads = self::AddLead($lead_params);
        if($res_leads['result_amo'] === true || isset($res_leads['new_lead']))
        {
            parse_str($params['utms_metka'], $metka);
            $validator_utms = Validator::make($metka, [
                'utm_source' => 'nullable | string| max:100 ',
                'utm_medium' => 'nullable | string| max:100 ',
                'utm_campaign' => 'nullable | string| max:100 ',
                'utm_content' => 'nullable | string| max:100 ',
                'utm_term' => 'nullable | string| max:100 '
            ]);

            $utms = [
                'utm_source' => $metka['utm_source'] ?? '',
                'utm_medium' => $metka['utm_medium'] ?? '',
                'utm_campaign' => $metka['utm_campaign'] ?? '',
                'utm_content' => $metka['utm_content'] ?? '',
                'utm_term' => $metka['utm_term'] ?? '',
            ];

            if($utms['utm_source'] != '' || $utms['utm_medium'] != '' || $utms['utm_campaign'] != '' || $utms['utm_content'] != '' || $utms['utm_term'] != '')
            {
                $utm = new Utm();
                $utm->fill($utms);
                if(isset($res_leads['new_lead'])) $utm->lead_uuid = $res_leads['new_lead']['uuid'];
                if(isset($res_leads['amo_lead_id'])) $utm->amo_id = $res_leads['amo_lead_id'];
                $utm->save();
            }
        }


        $endtime = (float)microtime(true) - $time_start;
        //Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': ------- ВРЕМЯ  ВЫПОЛНЕНИЯ: ' .$endtime. '-----' . @$res_leads['new_lead']['uuid'] . '----']);

        return response()->json($res_leads);
    }

}