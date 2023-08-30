<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

/**
 * Class SberStatus - Связь внутренних статусов и статусов для клиентов Сбербанка
 *
 * @package App\Http\Models\Common
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\LeadRef[] $leadref
 * @property-read int|null $leadref_count
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SberStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'sber_statuses_ref';

    /**
     * Связь через пивот таблицу к статусам сделок
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leadref()
    {
        return $this->belongsToMany(LeadRef::class,'sber_leadref',
            'sber_state_id',);
    }

    /** Выдаем относительно статуса сделки - клиентский статус
     * @param $lead_state_id
     * @return bool|mixed
     */
    public static function getSberState(int $lead_state_id){

        return self::with('leadref')->whereHas('leadref',function($q) use ($lead_state_id) {
            $q->where('id', $lead_state_id);
        })->first()->name ?? false;


    }
}
