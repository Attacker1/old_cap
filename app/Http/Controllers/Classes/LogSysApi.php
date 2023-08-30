<?php

namespace App\Http\Controllers\Classes;

use App\Http\Classes\Common;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Common\Lead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class LogSysApi
 * @package App\Http\Controllers\Classes
 */
class LogSysApi extends Controller {

    private $leads_per_load = 250;
    /**
     * @var
     */
    public $model;
    /**
     * @var array
     */
    public $cfg = [];

    /**
     * LogSysApi constructor.
     */
    public function __construct()
    {

        if ($this->model = ServiceToken::where('service','logsys')->first()) {
            $this->cfg = [
                'client_id' => config('config.API_LOGSYS_CLIENT_ID'),
                'client_secret' => config('config.API_LOGSYS_CLIENT_SECRET'),
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->model->refresh_token,
                'redirect_uri' => config('config.API_LOGSYS_REDIRECT_URI')
            ];
        }

    }

    /**
     * Получение токена
     * @return mixed
     */
    public function getToken(){


        // Отвалился ключ АМО в старой связке, ставим общий
         $service = ServiceToken::whereService("amo")->first();
         return $service->token;

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
            curl_setopt($curl, CURLOPT_URL, "https://" . config('config.API_LOGSYS_SUBDOMAIN') . ".amocrm.ru/oauth2/access_token");
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($this->cfg));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            $out = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($out, true);
            $this->model->token = $response['access_token'];
            $this->model->refresh_token = $response['refresh_token'];
            $this->model->save();

            if (empty($this->model->token)) {
                Log::channel('exceptions')->error('Ошибка получения токена');
            }

            return $this->model->token;
        }
        catch (\Exception $e){
            Log::channel('exceptions')->error('Ошибка получения токена');
            Log::channel('exceptions')->error( json_encode($this->cfg));
            Log::critical($e);
            return false;
        }

    }

    private function curl($access_token,$page = 1 ){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://" . config('config.API_LOGSYS_SUBDOMAIN') . ".amocrm.ru/api/v4/leads?limit={$this->leads_per_load}&page=$page&filter[statuses][0][pipeline_id]=1635712&filter[statuses][0][status_id]=24838114&filter[statuses][1][pipeline_id]=1635712&filter[statuses][1][status_id]=34918930&filter[statuses][2][pipeline_id]=1635712&filter[statuses][2][status_id]=35093551",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'amoCRM-oAuth-client/1.0',
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $access_token
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response,1);
        return $data;
    }

    /**
     * @param $access_token
     * @return bool|string
     */
    public function getLeads($access_token){


        $page = 1; $data = [];
        do {
            echo "start getting amo leads, $page" . PHP_EOL;

            $tmp_data = self::curl($access_token,$page);
            if (empty($tmp_data['_embedded']['leads']))
                return false;

            $count = count($tmp_data['_embedded']['leads']);

            if ($count >= $this->leads_per_load)
                $page++;
            $data = array_merge(self::checkDeliverySystem($tmp_data),$data);

        } while ($count == $this->leads_per_load);
        echo "end, $page" . PHP_EOL;

        return $data;
    }

    private function checkDeliverySystem($data){


        if (empty($data['_embedded']['leads']))
            return false;

        $leads = [];
        $i = 0;
        foreach ($data['_embedded']['leads'] as $k=>$v) {
            $boxberry = false;
            if (!empty($v['_embedded']['tags'])) {
                foreach ($v['_embedded']['tags'] as $k2 => $item) {
                    if (in_array($item['name'], ['BOXBERRY', 'boxberry']))
                       $boxberry = true;
                }
            }
            if ($boxberry == false)
                $leads[] = $v['id'];
            $i++;
        }
        echo "Сделок в загрузке: " . $i . PHP_EOL;

        return $leads;
    }

    /**
     * @param $id
     * @param $access_token
     */
    public function changeLeadState($id, $access_token){

        $data = [];
        foreach ($id as $val) {
            $data[] = ['id' => (int)$val, 'status_id' => 24838120,];
            self::changeLocalLead((int)$val);
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://" . config('config.API_LOGSYS_SUBDOMAIN') . ".amocrm.ru/api/v4/leads",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $access_token
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);

        // Логируем сделки проставленные
        Log::channel('logsys')->info(json_encode($data));

    }

    /**
     * Поиск локальной сделки и простановка статуса 9 -  // Одежда у клиента
     * @param $amo_lead_id
     * @return bool
     */
    public function changeLocalLead($amo_lead_id){

        if ($lead = Lead::where('amo_lead_id',$amo_lead_id)->first()){
            $lead->state_id = 9; // Одежда у клиента
            $lead->save();
        }
        return true;
    }

    /**
     * Запуск простановки статусов
     * @return bool
     */
    public function run(){

        header('Content-Type: text/html; charset=utf-8');


        if (!$access_token = $this->getToken())
            return false;

        $leads = $this->getLeads($access_token);    //получение всех заявок в статусе одежда отгружена

        if (empty($leads))
            return false;

        // Логирование сделок для обработки
        Storage::disk('local')
            ->append('logs/logsys.log', now()->format("d/m/Y H:i") .': '. json_encode($leads));

        $leads_change_status = [];
        foreach ($leads as $val) {

            try {
                $response = json_decode(file_get_contents("https://api.logsis.ru/apiv2/getstatus?key=" . config('config.API_LOGSYS_KEY') . "&inner_n=" . $val), true);
                if ($response['status'] == 200) {
                    if ($response['response']['status'] == 5) {
                        $leads_change_status[] = $response['response']['inner_id'];
                    }
                } else {
                    //
                }
                //dd($response);
            }
            catch (\Exception $e){
                echo $val .PHP_EOL;
//                dd($e->getMessage());
  //              Log::channel('logsys')->alert(json_encode($val));
            }


        }
        unset($val,$val);

        Log::channel('logsys')->alert(json_encode($leads_change_status));
        if (count($leads_change_status) > 0) {
            $this->changeLeadState($leads_change_status, $access_token);
        }
    }
}