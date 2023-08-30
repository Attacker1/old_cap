<?php


namespace App\Services\Stock;


use App\Http\Models\Admin\ServiceToken;
use Illuminate\Support\Facades\Storage;
use MoySklad\ApiClient;

class AbstractStock
{

    protected ApiClient $api;

    protected function api(){

        $host = config('config.MOY_SKLAD_HOST');
        $token = ServiceToken::where('service', 'sklad')->firstOrFail()->token;
        return $this->api = new ApiClient($host, true, ['token' => $token]);

    }

}