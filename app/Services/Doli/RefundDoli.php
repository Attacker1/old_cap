<?php

namespace App\Services\Doli;

use App\Abstracts\Doli;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\DoliTransactions;
use App\Http\Models\Common\Lead;
use Illuminate\Support\Facades\Log;

class RefundDoli extends Doli
{

    public function getUrl()
    {
        return $this->url . $this->id . "/refund";
    }

    private function setProducts($items){

        foreach ($items as $item) {
            $item = (object)$item;
            $product_name = "art_" . $item->id;
            if($product = Product::whereId($item->id)->first())
                $product_name .= " ($product->name)";

            $products[] = (object)[
                "name" => $product_name,
                "quantity" => $item->quantity,
                "price" => $item->price
            ];
        }

        return $products;
    }


    public function setBody($transaction)
    {
        $this->id = $transaction->doli_id;
        $body = [
            "amount" => $transaction->refund_amount,
            "prepaid_amount" => 0, // ?
            "returned_items" => self::setProducts($transaction->returned_items),
        ];

        $this->body = json_encode($body);

        @Log::channel('doli')->info('CAPSULA REFUND ORDER #' . $transaction->doli_id . PHP_EOL . @$this->body);
        return true;

    }


    public function getBody()
    {
        return $this->body;
    }
}