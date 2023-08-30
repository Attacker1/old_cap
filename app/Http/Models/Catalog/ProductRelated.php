<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

/**
 * Связанные продукты
 * Class ProductRelated
 *
 * @package App\Http\Models\Catalog
 * @property int|null $product_id
 * @property int|null $group_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRelated newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRelated newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRelated query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRelated whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRelated whereProductId($value)
 * @mixin \Eloquent
 * @property int $id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Catalog\ProductRelated whereId($value)
 */
class ProductRelated extends Model
{
    /**
     * Таблица
     * @var string
     */
    protected $table = 'product_related';

    protected $fillable = ['product_id','group_id','created_at','updated_at'];
}
