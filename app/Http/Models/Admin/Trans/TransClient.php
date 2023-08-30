<?php


namespace App\Http\Models\Admin\Trans;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\Lead;
use \Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;

class TransClient extends Authenticatable
{
    use SoftDeletes;

    public $table = 'clients_tmp';

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
    
    protected $fillable = ['name', 'second_name', 'phone', 'email', 'comments', 'referal_code', 'client_status_id', 'socialmedia_links','amo_client_id','filename','created_at'];

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
     */
    public static function toAmo($client)
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

}