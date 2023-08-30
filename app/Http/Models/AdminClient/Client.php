<?php


namespace App\Http\Models\AdminClient;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\Lead;
use \Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;

/**
 * App\Http\Models\AdminClient\Client
 *
 * @property string $uuid
 * @property string|null $login
 * @property string|null $password
 * @property string|null $name
 * @property string|null $second_name
 * @property string|null $patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $comments
 * @property string|null $socialmedia_links
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $client_status_id
 * @property int|null $amo_client_id
 * @property string|null $referal_code
 * @property string|null $address
 * @property string|null $auth_token
 * @property-read Bonus $bonuses
 * @property-read \App\Http\Models\AdminClient\ClientStatus|null $client_status
 * @property-read \Illuminate\Database\Eloquent\Collection|FeedbackgeneralQuize[] $feedbacks
 * @property-read int|null $feedbacks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Lead[] $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Client fio($uuid)
 * @method static \Illuminate\Database\Eloquent\Builder|Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Client newQuery()
 * @method static \Illuminate\Database\Query\Builder|Client onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAmoClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereClientStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereReferalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSecondName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereSocialmediaLinks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Client whereAuthToken($value)
 * @method static \Illuminate\Database\Query\Builder|Client withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Client withoutTrashed()
 * @mixin \Eloquent
 */
class Client extends Authenticatable
{
    use SoftDeletes;

    /**
     * Основной ключ таблицы
     * @var string
     */

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * Массовые назначаемые поля/аттрибуты для модели
     *
     * @var array
     */

    protected $fillable = ['name', 'second_name', 'patronymic', 'phone', 'email', 'comments',
        'referal_code', 'client_status_id', 'socialmedia_links','amo_client_id','address',
        'auth_token',];

    /**
     * Скрытые аттрибуты
     *
     * @var array
     */
    protected $hidden = ['password'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->setAttribute($model->getKeyName(), (string)Str::uuid());
            // TODO: Проверить отработку на стейдж ? На проде генерит ошибку
            //$model->setAttribute($model->auth_token, (string)Str::uuid());
        });
    }


    /**
     * Получить статус, которому принадлежит этот клиент.
     */
    public function client_status()
    {
        return $this->belongsTo(ClientStatus::class, 'client_status_id');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class, 'client_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(FeedbackgeneralQuize::class, 'client_uuid');
    }

    /**
     * Добавить клиента в АМО CRM
     * @param Client $client
     * @return bool
     */
    public static function toAmo(Client $client)
    {

        $data = [
            'name' => '!TEST ' . $client->name ?? false,
            'phone' => $client->phone ?? false,
            'email' => $client->email ?? false,
        ];

        $amo = new AmoCrm();

        if (!$resp = $amo->add_contact($data))
            return false;

        if (!isset($resp['_embedded']) || !isset($resp['_embedded']['contacts'][0]['id']))
            return false;

        return $resp['_embedded']['contacts'][0]['id'] ?? false;
    }

    public function bonuses(){

        return $this->belongsTo(Bonus::class,'uuid','client_id');
    }

    public static function bonusHistory($uuid){

        if (!$item = Bonus::where('client_id',$uuid)->first())
            return false;

        return $item->revisionHistory ?? false;
    }

    // scopes
    public function scopeFio($query, $uuid): string
    {
        $needle = $query->where('uuid', $uuid)->first();
        $arr = [];
        !$needle->name ?: $arr[] = $needle->name;
        !$needle->patronymic ?: $arr[] = $needle->patronymic;
        !$needle->second_name ?: $arr[] = $needle->second_name;
        return implode(' ',$arr);
    }


}
