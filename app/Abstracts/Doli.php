<?php

namespace App\Abstracts;

use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Lead;

abstract class Doli
{

    protected string $url;
    protected $body = false;
    protected $stage = true;
    protected int $postfix_id;
    public $id;
    public $order;
    public $items;
    public $lead_uuid;


    public function __construct($postfix_id = false)
    {
        $this->postfix_id = $postfix_id;
        $this->stage = config('app.env') == 'production' ? false : true;
        $this->url = $this->stage === true
            ? config("doli.DOLI_STAGE_API_URI") . "/v1/orders/"
            : config("doli.DOLI_PROD_API_URI") . "/v1/orders/";
    }

    abstract public function getUrl();
    abstract public function setBody($model); // REFUND = DoliTransactions $id
    abstract public function getBody();

    protected function products($items){

        foreach ($items as $item) {
            $product_name = "art_" . $item->product_id;
            if($product = Product::whereId($item->product_id)->first())
                $product_name .= " ($product->name)";

            $price = !empty($item->discount_price) ? $item->discount_price : $item->price;
            $products[] = (object)[
                "name" => $product_name,
                "quantity" => 1,
                "price" => round($price,0)
            ];

            // Для транзакций
            $this->items[] = [
                "id" => $item->product_id,
                "quantity" => 1,
                "price" => round($price,0)
            ];
        }

        return collect($products);
    }


}