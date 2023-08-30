<?php

namespace App\Services;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\Common\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LeadService
{
    private Model $lead;
    private Client $client;
    private int $default_state = 1;
    private string $source;

    public function __construct($source = 'default'){
        $this->source = $source;
    }

    public function getByUuid($uuid){

        if ($lead = Lead::whereUuid($uuid)->first())
            return $this->lead = $lead;

        return false;
    }

    public function getByClient($client_uuid){

        if ($lead = Lead::whereClientId($client_uuid)->first())
            return $this->lead = $lead;

        return false;
    }

    public function createOrUpdate(string $client_uuid, $params = false) : LeadService
    {

        if (!$lead = self::getByClient($client_uuid)) {
            $lead = new Lead();
            $lead->state_id = $this->default_state;
            $lead->save();
            $this->lead = $lead;
        }

        if (!empty($params) && $validated = self::validate($params)) {
            $this->lead->fill($validated);
            $this->lead->save();
        }

        return $this;
    }

    public function create(string $client_uuid, $params = false) : LeadService
    {
            $lead = new Lead();
            $lead->client_id = $client_uuid;
            $lead->state_id = $this->default_state;
            $lead->source = $this->source;
            $lead->save();
            $this->lead = $lead;

            $this->client = Client::where('uuid',$client_uuid)->first();

        if (!empty($params) && $validated = self::validate($params)) {
            $this->lead->fill($validated);
            $this->lead->save();
        }

        return $this;
    }

    /** Добавление сделки в АМО + подвязка контакта к сделке
     * @param bool $amo_params
     * @return $this|bool
     */
    public function amoAddLead($amo_params = false){

        try {
            if (!empty($this->lead->amo_lead_id))
                return $this;

            $data = self::setAmoParams($amo_params);

            $amo = new AmoCrm();
            $resp = $amo->add_lead($data, true);

            if(!$resp['status']) {
                $this->error = $resp;
                return false;
            } else if(!isset($resp['_embedded']) || !isset($resp['_embedded']['leads'][0]['id']) )
                return false;

            $this->lead->amo_lead_id = $resp['_embedded']['leads'][0]['id'];
            $this->lead->save();

            if (!empty($this->client))
                $amo->link_lead_contact($this->lead->amo_lead_id,$this->client->amo_client_id);

            return $this;
        }
        catch (\Exception $e){
            throw new ModelNotFoundException('Lead error!' . $e->getMessage());
        }
    }

    private function setAmoParams($amo_params){

        $amo_params['state'] = $this->lead->state_id;

        if (!empty($this->lead->client_id)){
            if ($client = Client::whereUuid($this->lead->client_id)->first()) {
                $amo_params['name'] =  $client->name;
                $amo_params['phone'] =  $client->phone;
                $amo_params['email'] =  $client->email;

                if (!empty($client->address))
                    $amo_params['address_delivery'] =  $client->address;
            }
        }

        return $amo_params;
    }

    private function validateAmo($params){

        $validator_amo = Validator::make($params, [
            'name'          => 'string | max:255 | nullable',
            'state'         => 'integer | nullable',
            'coupon'         => 'string | nullable',
            'anketa_view'   => 'string | max:500 | nullable',
            'phone'         => 'required | regex:/[0-9_]+/i',
            'email'         => 'string | max:255 | email | nullable | nullable',
            'date_delivery' => 'string | max:10 | nullable',
            'address_delivery' => 'string | max:500 | nullable',
            'address_back'  => 'string | max:500 | nullable',
            'time_delivery' => 'string | max:500 | nullable',
            'time_back'     => 'string | max:500 | nullable',
            'pvz_point_id' => 'nullable | string| max:100 ',
            'pvz_address' => 'nullable | string| max:100 '
        ]);

        $amo_params = false;
        if(!$validator_amo->fails()) {

            if(!empty($params['name'])) $amo_params['name'] = $params['name'];
            if(isset($params['state'])) $amo_params['state'] = $params['state'];
            if(!empty($params['anketa_view'])) $amo_params['anketa_view'] = $params['anketa_view'];
            if(!empty($params['phone'])) $amo_params['phone'] = $params['phone'];
            if(!empty($params['email'])) $amo_params['email'] = $params['email'];
            if(!empty($params['date_delivery'])) $amo_params['date_delivery'] = $params['date_delivery'];
            if(!empty($params['coupon'])) $amo_params['coupon'] = $params['coupon'];

            //address_delivery либо курьер либо ПВЗ
            if(!empty($params['address_delivery'])) $amo_params['address_delivery'] = $params['address_delivery'];
            if(!empty($params['pvz_address'])) {
                $amo_params['address_delivery'] = $params['pvz_address'];
                $amo_params['delivery_comment'] ='BOXBERRY';
            }

            if(!empty($params['pvz_point_id'])) $amo_params['delivery_pvz'] = $params['pvz_point_id'];

            if(!empty($params['address_back'])) $amo_params['address_back'] = $params['address_back'];
            if(!empty($params['time_delivery'])) $amo_params['time_delivery'] = $params['time_delivery'];
            if(!empty($params['time_back'])) $amo_params['time_back'] = $params['time_back'];

        } else
            return false;

        return $amo_params;
    }

    private function validate($params){

        $validator = Validator::make($params, [
            'state_id' => 'nullable | integer',
            'anketa_uuid' => 'nullable | string | max:36',
            'anketa_id' => 'nullable | integer ',
            'client_num' => 'nullable | integer',
            'stylist_id' => 'nullable | integer',
            'contact_id' => 'nullable | integer',
            'create_type' => 'nullable | string | in:auto,manual',
            'date_delivery' => 'nullable | string | max:10',
            'tag' => 'nullable | string| max:100 '
        ]);

        if ($validator->fails())
            return false;

        return $validator->validated();
    }

    public function get()
    {
        return $this->lead ?? false;
    }

    /**
     * Простановка статуса "Доставлено до ПВЗ"
     * Отправка статуса в АМО
     * @param array $amo_ids
     * @return bool
     */
    public function arrivedToPvz(array $amo_ids){

        $leads = Lead::with('delivery')->whereHas('delivery',function ($q) use ($amo_ids){
            $q->whereNull('arrived_at');
        })->whereIn('amo_lead_id',$amo_ids);

        if (empty($items = $leads->count()))
            return false;

        foreach ($leads->get() as $item) {
                $item->state_id = 30; // Доставлено до ПВЗ
                $item->delivery()->first()->update(["arrived_at" => now()]);
                $item->save();
        }

        return  $items;

    }


}