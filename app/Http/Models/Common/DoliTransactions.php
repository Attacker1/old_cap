<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Common\DoliTransactions
 *
 * @property-read \App\Http\Models\Common\Lead $leads
 * @method static \Illuminate\Database\Eloquent\Builder|DoliTransactions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DoliTransactions newQuery()
 * @method static \Illuminate\Database\Query\Builder|DoliTransactions onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|DoliTransactions query()
 * @method static \Illuminate\Database\Query\Builder|DoliTransactions withTrashed()
 * @method static \Illuminate\Database\Query\Builder|DoliTransactions withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property string $lead_uuid
 * @property string $doli_id Номер заказа для ДОЛИ
 * @property array|null $items Приобретаемые товары по заказу
 * @property float|null $amount
 * @property float|null $refund_amount Сумма возврата
 * @property array|null $returned_items
 * @property string|null $x_correlation_id
 * @property string|null $state
 * @property string|null $completed_at Данные успеха
 * @property string|null $refund_at Данные возврата
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $refund_type
 * @property string|null $refund_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereDoliId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereLeadUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereRefundAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereRefundAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereRefundId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereRefundType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereReturnedItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\DoliTransactions whereXCorrelationId($value)
 */
class DoliTransactions extends Model
{
    use SoftDeletes;

    protected $casts = [
        "items" => "array",
        "returned_items" => "array"
    ];

   protected $fillable = ["doli_id","amount","prepaid_amount","x_correlation_id","returned_items","state","items","lead_uuid",
                          "created_at","updated_at","completed_at","refund_at","deleted_at"];

    public function leads(){

        return $this->belongsTo(Lead::class,'lead_uuid','uuid');
    }

}
