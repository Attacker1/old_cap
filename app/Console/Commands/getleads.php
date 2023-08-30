<?php

namespace App\Console\Commands;

use App\Http\Models\Admin\ServiceToken;
use Illuminate\Console\Command;

/**
 * Автоапдейт статуса "Одежда у клиента
 * Class common
 * @package App\Console\Commands
 */

class getleads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:get_leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение сделок - статусов // Автоапдейт статуса "Одежда у клиента';


    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        header('Content-Type: text/html; charset=utf-8');

        $api_data = [
            'client_secret' => 'gBHXJswRTCidlSz7hbWpGNgPzMyxXiyraN83z62RFLIyaG1HyPCw25dAWj1N698R',
            'client_id' => 'edcf7ba8-4989-4f70-9809-8943447af2b5',
            'subdomain' => 'thecapsula',
            'redirect_uri' => 'https://script.thecapsula.ru/',
        ];

        $access_token = $this->get_refresh();
        $data = json_decode($this->get_leads($access_token), true);    //получение всех заявок в статусе одежда отгружена

        $leads = [];
        foreach ($data['_embedded']['leads'] as $val) {
            $leads[] = $val['id'];
        }
        unset($val);


        $leads_change_status = [];
        foreach ($leads as $val) {
            $response = json_decode(file_get_contents("http://api.logsis.ru/apiv2/getstatus?key=" . config('config.API_LOGSYS_KEY') . "&inner_n=" . $val), true);
            if ($response['status'] == 200) {
                if ($response['response']['status'] == 5) {
                    $leads_change_status[] = $response['response']['inner_id'];
                }
            } else {
                //file_put_contents('leads_logsis_get_status_log.txt', date('Y-m-d, H:i:s') . "\n" . $val . "\n", FILE_APPEND | LOCK_EX);
            }
        }
        unset($val);

        if (count($leads_change_status) > 0) {
            $this->change_lead_status($leads_change_status, $access_token);
        }


    }

    /**
     * @param $id
     * @param $access_token
     */
    function change_lead_status($id, $access_token)
    {

        $data = [];
        foreach ($id as $val) {
            $data[] = ['id' => (int)$val, 'status_id' => 24838120,];
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
        //file_put_contents('leads_work_change_log.txt', date('Y-m-d, H:i:s') . "\n" . $response . "\n" . print_r($data, true), FILE_APPEND | LOCK_EX);
    }

    function get_refresh()
    {

        $service = ServiceToken::where('service','logsys')->first();
        $data = [
            'client_id' => config('config.API_LOGSYS_CLIENT_ID'),
            'client_secret' => config('config.API_LOGSYS_CLIENT_SECRET'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $service->refresh_token,
            'redirect_uri' =>  config('config.API_LOGSYS_REDIRECT_URI')
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, "https://" . config('config.API_LOGSYS_SUBDOMAIN') . ".amocrm.ru/oauth2/access_token");
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($out, true);
        $service->token = $response['access_token'];
        $service->refresh_token = $response['refresh_token'];
        $service->save();
        if (empty($service->token)) {
            //file_put_contents('error_log.txt', date('Y-m-d, H:i:s') . "\nОшибка получения токена\n", FILE_APPEND | LOCK_EX);
            die("Произошла ошибка!");
        } else {
            file_put_contents('access.txt', $service->token, LOCK_EX);
            //file_put_contents('refresh.txt', $refresh_token, LOCK_EX);
        }

        return $service->token;
    }





    function get_leads($access_token)
    {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://" . config('config.API_LOGSYS_SUBDOMAIN') . ".amocrm.ru/api/v4/leads?limit=250&filter[statuses][0][pipeline_id]=1635712&filter[statuses][0][status_id]=24838114&filter[statuses][1][pipeline_id]=1635712&filter[statuses][1][status_id]=34918930&filter[statuses][2][pipeline_id]=1635712&filter[statuses][2][status_id]=35093551",
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

        //file_put_contents('leads_work_log.txt', date('Y-m-d, H:i:s') . "\n" . $response . "\n", FILE_APPEND | LOCK_EX);
        return $response;
    }
}
