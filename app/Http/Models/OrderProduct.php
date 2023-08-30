<?php

namespace App\Http\Models;
use \Illuminate\Database\Eloquent\Model;
use App\Http\Models\Catalog\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\OrderProduct
 *
 * @property int $id
 * @property string $order_uuid
 * @property int $product_id
 * @property int $feedback_quize_id
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct newQuery()
 * @method static \Illuminate\Database\Query\Builder|OrderProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereFeedbackQuizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereOrderUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|OrderProduct withTrashed()
 * @method static \Illuminate\Database\Query\Builder|OrderProduct withoutTrashed()
 * @mixin \Eloquent
 * @property int $order_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\OrderProduct whereOrderId($value)
 */
class OrderProduct extends Model
{
    use SoftDeletes;

    /**
     * Массовые назначаемые поля/аттрибуты для модели
     *
     * @var array
     */

    protected $fillable = ['order_uuid', 'product_id', 'feedback_quize_id'];
}