<?php

namespace App\Http\Models\Common;

use App\Http\Models\AdminClient\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Common\BonusTransactions
 *
 * @property int $id
 * @property string|null $client_id
 * @property string|null $lead_uuid
 * @property int|null $points
 * @property string|null $promocode
 * @property string|null $type
 * @property string|null $paid_by
 * @property int|null $user_id
 * @property array|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $registered_id
 * @property-read Client|null $clients
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions newQuery()
 * @method static \Illuminate\Database\Query\Builder|BonusTransactions onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions query()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereLeadUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions wherePromocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereRegisteredId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusTransactions whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|BonusTransactions withTrashed()
 * @method static \Illuminate\Database\Query\Builder|BonusTransactions withoutTrashed()
 * @mixin \Eloquent
 */
class BonusTransactions extends Model
{
    use SoftDeletes;

    protected $fillable = ['client_id', 'lead_uuid', 'points', 'type', 'paid_by'];

    protected $casts = [
        'description' => 'array'
    ];

    /**
     * Связь с таблицей Клиентов
     * @return BelongsTo
     */
    public function clients(){

        return $this->belongsTo(Client::class,'client_id','uuid');
    }
}
