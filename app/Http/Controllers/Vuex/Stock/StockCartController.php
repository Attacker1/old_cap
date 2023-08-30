<?php

namespace App\Http\Controllers\Vuex\Stock;

use App\Http\Controllers\Controller;
use App\Http\Models\Stock\StockCarts;
use App\Http\Models\Stock\StockLike;
use App\Http\Models\Stock\StockProducts;
use App\Services\Stock\StockProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Mockery\Exception;
use App\Traits\VuexAutoMethods;

class StockCartController extends Controller
{
    use VuexAutoMethods;

    public function fetch()
    {
        if (auth()->guard('admin')->user()) {
            $cart = StockCarts::with('products')->where('stylist_id', auth()->guard('admin')->user()->id);
            if ($cart = $cart->first()) {
                $cartProducts = $cart->products()->get();
                foreach ($cartProducts as $cartItem) {
                    $cartItem['img'] = !empty($cartItem->external_uuid) ? route('stock.image', $cartItem->external_uuid) : false;
                }
                return response()->json( (object)[
                    'expired_time' => $cart->created_at->addHours(2),
                    'products' => $cartProducts
                ]);
            }
        }
        return null;
    }

    public function fetchAll() {
        if (auth()->guard('admin')->user()) {
            $carts = StockCarts::with('products')->where('stylist_id', auth()->guard('admin')->user()->id)->get();
            return $carts;
        }
        return null;
    }

    public function addProduct()
    {
        try {
            if (auth()->guard('admin')->user()) {
                $product = StockProducts::findOrFail(request('product_id'));
                if ($product && $product->quantity > 0) {
                    $cart = StockCarts::with('products')->where('stylist_id', auth()->guard('admin')->user()->id);
                    if ($cart = $cart->first()) {
                        $cart->products()->attach(request('product_id'));
                        $cart->save();
                    } else {
                        $newCart = new StockCarts();
                        $newCart->stylist_id = auth()->guard('admin')->user()->id;
                        $newCart->save();
                        $newCart->products()->attach(request('product_id'));
                    }
                    $product->quantity -= 1;
                    $product->save();
                } else {
                    throw new Exception(
                        'Данный товар закончился',
                        404
                    );
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
        $products = new StockProduct();
        return $products->grid();
    }

    public function deleteProduct()
    {
        if (auth()->guard('admin')->user()) {
            $cart = StockCarts::where('stylist_id', auth()->guard('admin')->user()->id);
            if ($cart = $cart->first()) {
                $product = StockProducts::findOrFail(request('product_id'));
                $cart->products()->detach(request('product_id'));
                $cart->save();
                $product->quantity += 1;
                $product->save();
            }
        }
    }

    public function confirmOrder()
    {
        return true;
    }

    public static function checkExpireReservation() {
        $carts = StockCarts::get();
        foreach ($carts as $cart) {
            if ($cart->created_at->addHours(2) < Carbon::now()) {
                if (count($cart->products()->get()) > 0) {
                    foreach($cart->products()->get() as $product) {
                        $product->quantity += 1;
                        $product->save();
                    }
                }
                $cart->products()->detach();
                $cart->delete();
            }
        }
    }
}
