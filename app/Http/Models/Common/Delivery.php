<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Класса-модель для работы с различными доставщиками, возможно придется расширить
 * Class Delivery
 *
 * @package App\Http\Models\Common
 * @property int $id
 * @property string|null $lead_id
 * @property string|null $source
 * @property string|null $delivery_id Идентификатор заказа от доставщика
 * @property int|null $delivery_point_id ПВЗ - ID пункта выдачи заказа
 * @property string|null $delivery_address Адрес пункта выдачи заказа текстом
 * @property string $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $exception
 * @property-read \App\Http\Models\Common\Lead|null $lead
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Delivery onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereDeliveryAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereDeliveryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereDeliveryPointId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereException($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Delivery withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Delivery withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $deleted_at
 * @property string|null $arrived_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereArrivedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Delivery whereDeletedAt($value)
 */
class Delivery extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['delivery_id','lead_id','delivery_point_id','delivery_address','source','state','arrived_at'];

    /**
     * @return BelongsTo
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'uuid');
    }
}
