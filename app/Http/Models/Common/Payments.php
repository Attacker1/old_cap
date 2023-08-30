<?php

namespace App\Http\Models\Common;

use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use phpseclib\Math\BigInteger;

/**
 * App\Http\Models\Common\Payments
 *
 * @property int $id
 * @property string|null $lead_id
 * @property float|null $amount
 * @property string|null $paid_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $pay_for
 * @property string|null $payload
 * @property string|null $source
 * @property int|null $payment_id
 * @property-read \App\Http\Models\Common\Lead|null $leads
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Payments onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments wherePayFor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Payments withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Payments withoutTrashed()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|Payments wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payments whereSource($value)
 */
class Payments extends Model
{
    use SoftDeletes;

    protected $fillable = ['lead_id', 'amount', 'paid_at', 'pay_for','payment_id','payload','source'];

    public $table = 'payments';

    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function leads(){

        return $this->belongsTo(Lead::class,'lead_id','uuid');
    }

    public function createDoliPayment(FeedbackgeneralQuize $feedback){

        $discount_price = $feedback->paidItems()->sum('discount_price');
        $price = $feedback->paidItems()->sum('price');

        $this->lead_id   = $feedback->lead_id;
        $this->amount    = !empty($discount_price) ? $discount_price : $price;
        $this->paid_at   = now();
        $this->pay_for   = 'products';
        $this->payload   = json_encode(json_encode(['OrderId' => $feedback->uuid]));
        $this->source   = 'doli';
        $this->payment_id   = $feedback->id;
        $this->save();
        return true;

    }

}
