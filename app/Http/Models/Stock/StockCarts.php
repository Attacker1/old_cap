<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockCarts
 *
 * @property int $id
 * @property int|null $stylist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Stock\StockProducts[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts whereStylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCarts whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockCarts extends Model
{
    protected $table = 'stock_carts';

    protected $fillable = ['stylist_id'];

    public function products() {
        return $this->belongsToMany(StockProducts::class, 'stock_carts_stock_products', 'stock_carts_id', 'stock_products_id')->withTimestamps();
    }
}
