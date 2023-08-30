<?php

namespace App\Http\Models;
use \Illuminate\Database\Eloquent\Model;
use App\Http\Models\AdminClient\Client;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * App\Http\Models\Order
 *
 * @property string $uuid
 * @property int $feedbackgeneral_quize_id
 * @property string|null $sum
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $client_uuid
 * @property-read Client|null $clients
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFeedbackgeneralQuizeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @mixin \Eloquent
 * @property int $id
 * @property int|null $amo_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Order whereAmoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Order whereId($value)
 */
class Order extends Model
{
    use SoftDeletes;

    /**
     * Основной ключ таблицы
     * @var string
     */

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    /**
     * Массовые назначаемые поля/аттрибуты для модели
     *
     * @var array
     */

    protected $fillable = ['client_uuid', 'feedbackgeneral_quize_id', 'sum', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($client) {
            $client->{$client->getKeyName()} = (string) Str::uuid();
        });
    }

    /**
     * Связь с таблицей Клиенты
     * @return BelongsTo
     */
    public function clients(){

        return $this->belongsTo(Client::class,'client_uuid','uuid');
    }
}