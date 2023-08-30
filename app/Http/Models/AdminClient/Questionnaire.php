<?php
namespace App\Http\Models\AdminClient;

use App\Http\Filters\Filter;
use App\Http\Models\Common\Payments;
use App\Http\Models\Common\Utm;
use Bnb\Laravel\Attachments\Attachment;
use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Models\Common\Lead;

/**
 * App\Http\Models\AdminClient\Questionnaire
 *
 * @property string $uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $id
 * @property string|null $code
 * @property array|null $data
 * @property array|null $anketa
 * @property int|null $is_test
 * @property int $is_new
 * @property float|null $amount
 * @property int|null $amo_id
 * @property string|null $client_uuid
 * @property string|null $manager_comment
 * @property string|null $filename
 * @property string $source
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $data_name
 * @property string|null $data_social
 * @property string|null $data_email
 * @property string|null $data_phone
 * @property string|null $data_sizes_top
 * @property string|null $data_sizes_bottom
 * @property int|null $photos_allow
 * @property-read \Illuminate\Database\Eloquent\Collection|Attachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Http\Models\AdminClient\Client|null $client
 * @property-read \App\Http\Models\AdminClient\Client|null $hasClient
 * @property-read \Illuminate\Database\Eloquent\Collection|Lead[] $hasLeadLatest
 * @property-read int|null $has_lead_latest_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Lead[] $hasLids
 * @property-read int|null $has_lids_count
 * @property-read Lead|null $leads
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Attachment[] $tabPhotos
 * @property-read int|null $tab_photos_count
 * @property-read Utm|null $utm
 * @method static Builder|Questionnaire filter(\App\Http\Filters\Filter $filters)
 * @method static Builder|Questionnaire newModelQuery()
 * @method static Builder|Questionnaire newQuery()
 * @method static Builder|Questionnaire query()
 * @method static Builder|Questionnaire whereAmoId($value)
 * @method static Builder|Questionnaire whereAmount($value)
 * @method static Builder|Questionnaire whereAnketa($value)
 * @method static Builder|Questionnaire whereClientUuid($value)
 * @method static Builder|Questionnaire whereCode($value)
 * @method static Builder|Questionnaire whereCreatedAt($value)
 * @method static Builder|Questionnaire whereData($value)
 * @method static Builder|Questionnaire whereDataEmail($value)
 * @method static Builder|Questionnaire whereDataName($value)
 * @method static Builder|Questionnaire whereDataPhone($value)
 * @method static Builder|Questionnaire whereDataSizesBottom($value)
 * @method static Builder|Questionnaire whereDataSizesTop($value)
 * @method static Builder|Questionnaire whereDataSocial($value)
 * @method static Builder|Questionnaire whereDeletedAt($value)
 * @method static Builder|Questionnaire whereFilename($value)
 * @method static Builder|Questionnaire whereId($value)
 * @method static Builder|Questionnaire whereIsNew($value)
 * @method static Builder|Questionnaire whereIsTest($value)
 * @method static Builder|Questionnaire whereManagerComment($value)
 * @method static Builder|Questionnaire wherePhotosAllow($value)
 * @method static Builder|Questionnaire whereSource($value)
 * @method static Builder|Questionnaire whereStatus($value)
 * @method static Builder|Questionnaire whereUpdatedAt($value)
 * @method static Builder|Questionnaire whereUuid($value)
 * @mixin \Eloquent
 */
class Questionnaire extends Model
{
    use Notifiable, HasAttachment;

    /**
     * Основной ключ таблицы
     * @var string
     */

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['code', 'data', 'status', 'amount', 'amo_id', 'manager_comment'];

    protected $casts = [
        'data' => 'array',
        'anketa' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->setAttribute($model->getKeyName(), (string)Str::uuid());
        });
    }

    static function createCode()
    {
        $code = null;

        do {
            $code = md5(Str::uuid());
            $res = DB::table('questionnaires')->select('code')->where('code', $code)->first();
        } while($res !== null);

        return $code;
    }

    /**
     * Связь с таблицей клиенты
     * @return BelongsTo
     */
    public function client(){

        return $this->belongsTo(Client::class,'client_uuid','uuid');
    }

    public function hasClient() {
        return $this->hasOne(Client::class,'uuid','client_uuid');
    }

    /**
     * Связь с таблицей Сделки
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leads(){

        return $this->belongsTo(Lead::class,'id','anketa_id');
    }

    public function hasLids(){
        return $this->hasMany(Lead::class,'anketa_uuid','uuid');
    }

    public function hasLeadLatest(){
        return $this->hasMany(Lead::class,'anketa_uuid','uuid')->latest();
    }

    /**
     * Связь с таблицей attachments
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tabPhotos()
    {
        return $this->hasMany(Attachment::class,'model_uuid','uuid');
    }

    public function utm() {
        return $this->hasOne(Utm::class,'amo_id','amo_id');
    }

    public function scopeFilter(Builder $builder, Filter $filters)
    {
        return $filters->apply($builder);
    }
}
