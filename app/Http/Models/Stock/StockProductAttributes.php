<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockProductAttributes
 *
 * @property int $id
 * @property int|null $product_id
 * @property int|null $attribute_id
 * @property array|null $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProductAttributes whereValue($value)
 * @mixin \Eloquent
 */
class StockProductAttributes extends Model
{

    protected $casts = [
        'value' => 'array',
    ];

    protected $fillable = [ 'product_id','attribute_id','value'];

}
