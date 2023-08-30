<?php

namespace App\Services;

use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\AdminClient\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class ClientService
 * @package App\Services
 */
class ClientService
{
    private Client $client;
    private int $client_status_id = 3;

    public function __construct(){}

    /**
     * @return Client|bool
     */
    public function get()
    {
        return $this->client ?? false;
    }

    public function getByUuid($uuid){

        if ($client = Client::whereUuid($uuid)->first())
            return $this->client = $client;

        return false;
    }

    public function getByPhone($phone_raw){

        $phone = Common::format_phone($phone_raw);

        if ($client = Client::wherePhone($phone)->first())
            return $this->client = $client;

        return false;
    }

    public function createOrUpdate(string $phone_raw, $params = false) : ClientService
    {

        Log::channel('anketa_err')->info('-------------- Клиент ------------');
        Log::channel('anketa_err')->info(json_encode($params));

        $validated = self::validate($params);
        $phone = Common::format_phone($phone_raw);
        if (!$client = self::getByPhone($phone)) {
            $client = new Client();
            $client->phone = $phone;

            if (!empty($validated['name'])) $client->name = $validated['name'];
            if (!empty($validated['second_name'])) $client->email = $validated['second_name'];
            if (!empty($validated['patronymic'])) $client->email = $validated['patronymic'];
            if (!empty($validated['email'])) $client->email = $validated['email'];
            if (!empty($validated['socialmedia_links'])) $client->email = $validated['socialmedia_links'];
            if (!empty($validated['address'])) $client->email = $validated['address'];

            $client->client_status_id = $this->client_status_id;
            $client->save();
            $this->client = $client;
        }

        if (!empty($client->name) && empty($client->referal_code))
            self::setReferalCode();

        if (!empty($this->client) && !empty($params) && $validated ) {
            $this->client->fill($validated);
            $this->client->save();
        }

        return $this;
    }

    public function amoFirstOrNew(): ClientService
    {

        try {
            if (!empty($this->client->amo_client_id)) {
                $this->amoUpdateClient();
                return $this;
            }

            $amo = new AmoCrm();
            $amo_contact_search = $amo->search_contact($this->client->phone, true);

            Log::channel('anketa_err')->info(json_encode($amo_contact_search));

            if (!empty($amo_contact_search["status"]) && $amo_contact_search["status"] == "200") {
                if (count($amo_contact_search['_embedded']['contacts']) >= 1) {
                    self::updateAmoContact($amo_contact_search['_embedded']['contacts'][0]['id']);
                    return $this;
                }
            } else {
                $amo_params = [
                    'name' => $this->client->name ?? null,
                    'phone' => $this->client->phone ?? null,
                    'email' => $this->client->email ?? null,
                    'instagramm' => $this->client->socialmedia_links ?? null,
                ];

                Log::channel('anketa_err')->info(json_encode($amo_params));

                $res_amo = $amo->add_contact($amo_params, true);

                if ($res_amo["status"]) {
                    self::updateAmoContact($res_amo['_embedded']['contacts'][0]['id']);
                } else
                    self::log('Клиент добавление [' . $this->client->phone . ']. Ошибки создания контакта АМО [' . json_encode($res_amo) . ']');
            }

            return $this;
        }
        catch (\Exception $e){
                throw new ModelNotFoundException('Client not set!' . $e->getMessage());
        }

    }

    public function amoUpdateClient(): ClientService
    {

        try {
            $amo = new AmoCrm();
            $amo_params = [
                'full_name' => $this->client->name,
                'phone' => $this->client->phone,
                'email' => $this->client->email,
                'socialmedia_links' => $this->client->socialmedia_links,
            ];
            $res_amo = $amo->update_contact($this->client->amo_client_id,$amo_params,true);

            if (!$res_amo["status"])
                self::log('Клиент обновление [' . $this->client->phone . ']. Ошибки АМО [' . json_encode($res_amo) . ']');

            return $this;

        }catch (\Exception $e){
            throw new ModelNotFoundException('Client not set or AMO error!'. $e->getMessage() .PHP_EOL.json_encode($res_amo));
        }
    }

    private function updateAmoContact($amo_id)
    {
        $this->client->amo_client_id = $amo_id;
        $this->client->save();
    }

    private function validate($params){

        $validator = Validator::make($params, [
            'name' => 'string | nullable | max:255',
            'second_name' => 'string | nullable | max:255',
            'patronymic' => 'string | nullable | max:255 ',
            'email' => 'string | max:255 | email | nullable',
            'comments' => 'string | max:2000 | nullable',
            'socialmedia_links' => 'string | max:250 | nullable',
            'amo_client_id' => 'integer | nullable',
            'address' => 'string | max:250 | nullable',
        ]);

        if ($validator->fails())
            return false;

        return $validator->validated();
    }

    private function setReferalCode()
    {

        $promo = new Promocode();
        $this->client->referal_code = $promo->set($this->client);
        $this->client->save();
        return true;

        // @deprecated - old version?
//        $code = null;
//
//        do {
//            $code = mt_rand(100000, 999999);
//            $res = DB::table('clients')->select('referal_code')->where('referal_code', $code)->first();
//        } while($res !== null);
//
//        return (string)$code;
    }

    private function log($message){
        Log::channel('clients')->info($message);
    }

    public function firstOrFail($uuid)
    {

        $this->client = Client::whereUuid($uuid)->first();
        return $this;

    }

    public function getFullName(){
        try {
            return $this->client->name . ' ' . $this->client->second_name . ' ' . $this->client->patronymic;

        } catch (\Exception $e){
            return false;
        }

    }

}