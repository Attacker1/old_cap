<?php

namespace App\Services\Stock;

use App\Http\Models\Stock\StockWebhook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class StockProduct
 * @package App\Services\Stock
 */
class StockWebhooks
{
    /**
     * @var StockWebhook
     */
    private StockWebhook $model;

    /**
     * @var \Illuminate\Http\JsonResponse
     */
    private \Illuminate\Http\JsonResponse $response;

    /**
     * StockProduct constructor.
     */
    public function __construct()
    {
        $this->model = new StockWebhook();
        $this->response = response()->json(['OK']);
    }


    public function get(string $uuid)
    {

        $input = file_get_contents('php://input');
        Log::channel('ms_webhook')->info($input);

        if(!$item = $this->model::whereUuid($uuid)->first())
            return $this->response;

        Log::channel('ms_webhook')->info($item->entity);

        return $this->response;
    }



}
