<?php

namespace App\Services\Doli;

use App\Abstracts\Doli;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Common\DoliTransactions;
use App\Http\Models\Common\Lead;
use Illuminate\Support\Facades\Log;

class CommitDoli extends Doli
{

    public function getUrl()
    {
        return $this->url . $this->id . "/commit";
    }

    public function setBody($feedback)
    {

        @Log::channel('doli')->info('Commit order UUID FB: ' . @$feedback->uuid);
        if (!$lead = Lead::whereUuid($feedback->lead_id)->first())
            return false;

        $transaction = DoliTransactions::where("lead_uuid",$lead->uuid)->orderByDesc("id")->first();

        $this->id = $transaction->doli_id;
        $items = $feedback->feedbackgQuize()->where('action_result',"buy")->get();
        $products = self::products($items);

        $body = [
            "amount" => round($products->sum('price'),0),
            "prepaid_amount" => 0,
            "items" => $products->toArray()
        ];
        $this->body =  json_encode($body);
        //@Log::channel('doli')->info('Commit order CAP: ' . @$this->body);
    }


    public function getBody()
    {
        return $this->body;
    }
}