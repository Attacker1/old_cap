<?php

namespace App\Http\Controllers\Vuex\Stock;

use App\Http\Controllers\Controller;
use App\Http\Models\Stock\StockAttributes;
use App\Http\Models\Stock\StockProductAttributesUnique;
use App\Http\Models\Stock\StockProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Stock\StockCarts;
use App\Services\Stock\StockProduct;
use App\Traits\VuexAutoMethods;
use Illuminate\Support\Str;

class StockAttributeController extends Controller
{
    use VuexAutoMethods;

    public function fetch()
    {
        if ($attributes = StockAttributes::whereIn('id', [1, 19, 20, 21, 22])->get()) {
            $attributes = $attributes->map(function ($object) {

                $object->values = StockProductAttributesUnique::where('attribute_id', $object->id)->pluck('value');

                return $object;
            });
        }

        return $attributes;
    }

    public static function updateUniqueAttributes()
    {
        StockProductAttributesUnique::query()->delete();
        $attributes = DB::table('stock_product_attributes')->whereIn('attribute_id', [1, 19, 20, 21, 22])->selectRaw("DISTINCT LOWER(value) as value, attribute_id")->get();
        foreach ($attributes as $attr) {
            $uniqueAttr = new StockProductAttributesUnique();
            $uniqueAttr->attribute_id = $attr->attribute_id;
            $uniqueAttr->value = $attr->value;
            $uniqueAttr->save();
        }
    }
}
