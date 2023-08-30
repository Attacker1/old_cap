<?php

namespace App\Services\Doli;

use App\Abstracts\Doli;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Common\Lead;
use Illuminate\Support\Facades\Log;

class CreateDoli extends Doli
{

    public function getUrl()
    {
        return $this->url . "create";
    }


    public function setBody($feedback)
    {

        $items = $feedback->feedbackgQuize()->where('action_result',"buy")->get();
        if (!$lead = Lead::whereUuid($feedback->lead_id)->first())
            return false;

        if ($items->count() > 0) {
            $products = self::products($items);

            $postfix = !empty($this->postfix_id) ? "_" . $this->postfix_id : null ;
            $this->id = "CS" . $lead->amo_lead_id . $postfix;


            $body = [
                "order" => [
                    "id" => $this->id,
                    "amount" => round($products->sum('price'),0),
                    "prepaid_amount" => 0,
                    "items" => $products->toArray()
                ],

                "client_info" => [
                    "first_name" => $feedback->client()->first()->name,
                    "last_name" => $feedback->client()->first()->second_name,
                    "middle_name" => $feedback->client()->first()->patronymic,
                    "birthdate" => now()->format("Y-m-d"),
                    "phone" => "+" . $feedback->client()->first()->phone, // "+79123456701",
                    "email" => $feedback->client()->first()->email // "test2@ya.ru"
                ],

                "notification_url" => ($this->stage === true ? config("doli.DOLI_STAGE_WEBHOOK_URI") : config("doli.DOLI_PROD_WEBHOOK_URI")) . $feedback->uuid ,
                "fail_url" => ($this->stage === true ? config("doli.DOLI_STAGE_FAIL_URI") : config("doli.DOLI_PROD_FAIL_URI")) . $feedback->uuid ,
                "success_url" => ($this->stage === true ? config("doli.DOLI_STAGE_SUCCESS_URI") : config("doli.DOLI_PROD_SUCCESS_URI")) . $feedback->uuid ,
            ];

            $this->lead_uuid = $lead->uuid;
            $this->order = $body["order"];
            $this->body =  json_encode($body);
            @Log::channel('doli')->info('CAPSULA Created: ' . @$this->body);
        }

    }


    public function getBody()
    {
        return $this->body;
    }
}