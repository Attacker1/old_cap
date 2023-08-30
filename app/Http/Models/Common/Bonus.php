<?php

namespace App\Http\Models\Common;

use App\Http\Models\AdminClient\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Venturecraft\Revisionable\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Http\Models\Common\Bonus
 *
 * @property int $id
 * @property string|null $client_id
 * @property int|null $points
 * @property string|null $promocode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Client|null $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Admin\CustomRevision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus query()
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus wherePromocode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Bonus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Bonus extends Model
{
    use RevisionableTrait;

    protected $revisionFormattedFieldNames = [
        'points'      => 'Бонусы',
        'promocode' => 'Промокод',
    ];


    /**
     * Связь с таблицей Клиентов
     * @return BelongsTo
     */
    public function clients(){
        return $this->belongsTo(Client::class,'client_id','uuid');
    }

}
