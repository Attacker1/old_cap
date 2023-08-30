<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class ClientStatus - Связь внутренних статусов и статусов для клиентов
 *
 * @package App\Http\Models\Common
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\LeadRef[] $leadref
 * @property-read int|null $leadref_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'client_statuses_ref';

    /**
     * Связь через пивот таблицу к статусам сделок
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leadref()
    {
        return $this->belongsToMany(LeadRef::class,'states_leadref',
            'client_state_id',);
    }

    /** Выдаем относительно статуса сделки - клиентский статус
     * @param $lead_state_id
     * @return bool|mixed
     */
    public static function getClientState(int $lead_state_id){

        return self::with('leadref')->whereHas('leadref',function($q) use ($lead_state_id) {
            $q->where('id', $lead_state_id);
        })->first()->name ?? false;


    }
}
