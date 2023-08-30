<?php

namespace App\Http\Controllers\Vuex\Stock;

use App\Http\Controllers\Controller;
use App\Http\Models\Stock\StockLike;
use App\Http\Models\Stock\StockProducts;
use App\Traits\VuexAutoMethods;
use Illuminate\Http\Request;

class StockLikeController extends Controller
{
    use VuexAutoMethods;


    public function addLike() {
        if (auth()->guard('admin')->user()) {
            $like = new StockLike([
                'stylist_id' => auth()->guard('admin')->user()->id,
                'stock_product_id' => request('product_id')
            ]);
            $like->save();
        }
    }


    public function removeLike() {
        if (auth()->guard('admin')->user()) {
            $like = StockLike::where([
                ['stock_product_id', request('product_id')],
                ['stylist_id', auth()->guard('admin')->user()->id]
            ])->delete();
        }
    }


    public function fetchFavourites() {
        if (auth()->guard('admin')->user()) {
            $likedProducts = StockLike::with('product')->where('stylist_id', auth()->guard('admin')->user()->id)->get()->map(function ($likedProduct) {
                return $likedProduct->only(['product']);
            });
            $result = [];

            foreach ($likedProducts as $product) {
                $newProduct = $product['product'];
                $newProduct['isLiked'] = true;
                $newProduct['img'] = !empty($newProduct->external_uuid) ? route('stock.image',$newProduct->external_uuid) : false;
                array_push($result, $newProduct);
            }

            return $result;
        }
    }
}
