<?php

namespace App\Http\Controllers\Vuex\Stock;

use App\Http\Controllers\Controller;
use App\Services\Stock\StockProduct;
use App\Traits\VuexAutoMethods;
use Bnb\Laravel\Attachments\Attachment;
use http\Env\Response;
use Illuminate\Support\Facades\Storage;

class StockProductController extends Controller
{
    use VuexAutoMethods;

    public function index() {
        return view('vuex.stock.frontend', ['settings' => $this->settings()]);
    }


    public function grid(){
        $products = new StockProduct();
        return $products->grid();
    }

    public function find(){
        $product = new StockProduct();
        return $product->find(request('id'));
    }

    public function image($uuid)
    {

        if($file = @Attachment::where("key",$uuid)->first())
            return Storage::disk('s3')->response($file->filepath);

        return Storage::disk('public')->response("/images/placeholder.jpg");
    }



}
