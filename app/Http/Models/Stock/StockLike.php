<?php

namespace App\Http\Models\Stock;

use App\Http\Models\Admin\AdminUser;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockLike
 *
 * @property int $id
 * @property int $stock_product_id
 * @property int $stylist_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Models\Stock\StockProducts $product
 * @property-read \App\Http\Models\Admin\AdminUser $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike isLikedByUser($user_id, $product_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike whereStockProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike whereStylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockLike whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StockLike extends Model
{
    protected $table = 'stock_likes';

    protected $fillable = ['stock_product_id', 'stylist_id'];

    public function user()
    {
        return $this->belongsTo(AdminUser::class,'stylist_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(StockProducts::class, 'stock_product_id', 'id');
    }

    public function scopeIsLikedByUser($query, $user_id, $product_id)
    {
        return $query->where([
            ['stylist_id', $user_id],
            ['stock_product_id', $product_id]
        ]);
    }
}
