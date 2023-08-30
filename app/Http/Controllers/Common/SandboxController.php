<?php

namespace App\Http\Controllers\Common;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Controllers\Controller;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Api\ApiUser;
use App\Http\Models\Common\Delivery;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\LeadRef;
use App\Http\Models\Stock\StockCategories;
use App\Http\Models\Stock\StockProducts;
use App\Services\ClientService;
use App\Services\LeadService;
use App\Services\Stock\MoySklad;
use App\Services\Stock\StockAttribute;
use App\Services\Stock\StockCart;
use App\Services\Stock\StockCategory;
use App\Services\Stock\StockProduct;
use Carbon\Carbon;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ixudra\Curl\CurlService;
use Ixudra\Curl\Facades\Curl;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Models\AdminClient\Client;


/**
 * @package App\Http\Controllers\Common
 */
class SandboxController extends Controller
{
    public $start;

    public function __construct()
    {
        $this->start = microtime(true);
    }

    private function execution(){
        return round(microtime(true) - $this->start) .' секунд';
    }

    public function index()
    {

        $cat = new StockCategory();
        dd($cat->setAllCatsProducts());
        dd(1);

        // Отправка тестовой корзины
        $cart = new StockCart();
        $products = [16742,16645];
        $amo_id = uuid_v4();
        dd($cart->pushMoySklad($products,$amo_id,'Примечание'));


        return view('admin.payments.widget', [
            'title' => 'Оплата товаров',
            'client' => $client,
            'lead' => $lead
        ]);

    }

    private function setProductCats(){

        $this->i = 0;
        $cats = StockCategories::whereNotNull('origin')->get();
        $products = StockProducts::whereNotNull('pathName')
            ->chunkById(100, function ($products) use ($cats) {
                foreach ($products as $product) {
                    $this->i++;

                    $cat_id = $cats->where('origin',$product->pathName)->first();
                    if (!empty($cat_id->id)) {

                        StockProducts::whereId($product->id)->update([
                            "category_id" =>  $cat_id->id
                        ]);
                    }
                }
            });
    }

    private function getUniqueCats(){

        $items = StockProducts::distinct()->select('pathName')->whereNotNull('pathName')
            ->orderBy('pathName','asc')->get()->pluck('pathName');

        $cats = collect([]);
        foreach ($items as $item) {
            $parsed = explode("/",$item);

            if (!empty($parsed[2]))
                $cat_name = $parsed[2];
            elseif (!empty($parsed[1]))
                $cat_name = $parsed[1];
            else
                $cat_name = $parsed[0];

            $parent = !empty($parsed[1]) ? $parsed[1] : $parsed[0];
            $cat_name = $parent != $cat_name ? $cat_name : null;

            $cats->add((object)[
                'origin' => $item,
                'parent' => !empty($parsed[1]) ? $parsed[1] : $parsed[0],
                'name' => $cat_name
            ]);
        }

        return $cats;

    }

    private function setEndCats(Collection $items){
        $cats = StockCategories::all();
        $items = $items->whereNotNull('name')->all();
        foreach ($items as $item) {
            $parent_id = $cats->whereNotNull('name')->where('name',$item->parent)->first()->id;
            $info = StockCategories::updateOrCreate([
                'origin' => $item->origin
            ],
                [
                    'name' => $item->parent,
                    'parent_id' => $parent_id,
                    'origin' => $item->origin,
                    'external_name' => $item->parent,
                    'visible' => true,
                ]);
        }
        return true;
    }

    private function setSubCats($cats){

        $first_level = StockCategories::whereNull('parent_id')->get();
        $second_level = $cats->unique('parent')->all();

        foreach ($second_level as $item) {
            $tmp = explode("/",$item->origin);
            $parent = $first_level->where('name',$tmp[0])->first();

            if (!empty($parent->id))
                $parent_id = $parent->id;
            else
                $parent_id = null;

            if (!empty($parent->name) && $item->parent != $parent->name) {
                $info = StockCategories::updateOrCreate([
                    'name' => $item->parent
                ],
                    [
                        'name' => $item->parent,
                        'origin' => $item->origin,
                        'parent_id' => $parent_id,
                        'external_name' => $item->parent,
                        'visible' => true,
                    ]);
            }

        }

        return true;
    }


    private function getProduct($product_uuid)
    {

        $path = "https://online.moysklad.ru/api/remap/1.2/entity/product/" . $product_uuid;
        $headers = ['Content-Type: application/json', 'Authorization: Bearer ' . ServiceToken::where('service', 'sklad')->first()->token];

        $opts = ['http' => ['method' => 'GET',
            'ignore_errors' => true,
            'header' => $headers,
        ]];

        $ctx = stream_context_create($opts);
        $resp = file_get_contents($path, false, $ctx);
        $data = json_decode($resp, true);
        return $data;

    }

    private function getHeaders()
    {

        $user_name = 'admin@thecapsula';
        $user_pass = 'Capsula202112';

        return [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($user_name . ":" . $user_pass)];

    }
}
