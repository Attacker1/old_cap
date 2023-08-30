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

class CancelDoli extends Doli
{

    public function getUrl()
    {
        return $this->url . $this->id . "/cancel";
    }

    public function setBody($lead)
    {

        $transaction = DoliTransactions::where("lead_uuid",$lead)->orderByDesc("id")->first();
        $this->id = $transaction->doli_id;
        $this->body =  json_encode([]);
        @Log::channel('doli')->info('Cancel order: ' . @$transaction->doli_id);
    }


    public function getBody()
    {
        return $this->body;
    }
}