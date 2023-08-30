<?php

namespace App\Http\Models\Common;

use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Filters\Filter;
use App\Http\Filters\Leads\LeadQueryBuilder;
use App\Http\Filters\VuexFilter;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\CustomRevisionableTrait;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Tags;
use App\Mail\StylistNotify;
use App\Mail\Test;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Models\Common\BonusTransactions;
use Spatie\Backtrace\Backtrace;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Support\Facades\Log;


/**
 * Class Lead
 *
 * @package App\Http\Models\Common
 * @property string $uuid
 * @property string|null $client_id
 * @property int|null $stylist_id
 * @property int|null $amo_lead_id
 * @property int|null $anketa_id
 * @property string|null $anketa_uuid
 * @property float|null $total
 * @property float|null $summ
 * @property float|null $discount
 * @property int|null $state_logistic
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $state_id
 * @property int|null $substate_id
 * @property string|null $description
 * @property string|null $tag
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $client_num
 * @property int|null $amo_link_contact_id
 * @property string|null $deadline_at
 * @property string|null $delivery_at
 * @property string|null $create_type
 * @property-read \App\Http\Models\AdminClient\Questionnaire|null $ankets
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\BonusTransactions[] $bonus_transactions
 * @property-read int|null $bonus_transactions_count
 * @property-read \App\Http\Models\AdminClient\Client|null $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\Delivery[] $delivery
 * @property-read int|null $delivery_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\Payments[] $hasPayments
 * @property-read int|null $has_payments_count
 * @property-read \App\Http\Models\Common\Payments $payments
 * @property-read \App\Http\Models\AdminClient\Questionnaire|null $questionnaire
 * @property-read \App\Http\Models\Common\LeadRef|null $states
 * @property-read \App\Http\Models\Admin\AdminUser|null $stylists
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead filter(\App\Http\Filters\Filter $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Lead onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereAmoLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereAmoLinkContactId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereAnketaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereAnketaUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereClientNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereCreateType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereDeadlineAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereDeliveryAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereStateLogistic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereStylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereSubstateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereSumm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Lead withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Common\Lead withoutTrashed()
 * @mixin \Eloquent
 * @property string $source
 * @property-read \App\Http\Models\Common\DoliTransactions|null $doli
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\LeadCorrections[] $leadCorrections
 * @property-read int|null $lead_corrections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Admin\CustomRevision[] $revisionHistory
 * @property-read int|null $revision_history_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Tags[] $tags
 * @property-read int|null $tags_count
 * @property-read AdminUser $userResponsible
 * @method static Builder|Lead whereSource($value)
 * @property int|null $state
 * @property-read \App\Http\Models\AdminClient\Client|null $leadClient
 * @property-read \App\Http\Models\Common\LeadControl|null $leadControl
 * @property-read \App\Http\Models\Common\LeadRef|null $leadState
 * @property-read \App\Http\Models\Catalog\Tags|null $leadTag
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead filters(\App\Http\Filters\VuexFilter $filters)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Lead whereState($value)
 */
class Lead extends Model
{
    use SoftDeletes, RevisionableTrait;

    private static $lead_to_log = [30318279]; // TODO ПОИСК ОШИБКИ перезаписи данных по ТОТАЛ


    protected $revisionFormattedFieldNames = [
        'amo_lead_id' => 'AMO ID',
        'anketa_id' => 'ID анкеты',
        'total' => 'Сумма',
        'stylist_id' => 'ID стилиста',
        'state_id' => 'ID статуса',
        'user_id' => 'ID Пользователя',
    ];

    protected $revisionFormattedFields = [
        'amo_lead_id'      => 'string:<strong>%s</strong>',
        'public'     => 'boolean:No|Yes',
        'updated_at'   => 'datetime:m/d/Y g:i A',
        'deleted_at' => 'isEmpty:Active|Deleted'
    ];

    protected $keepRevisionOf = ['amo_lead_id','anketa_id','total','stylist_id','state_id','user_id'];

    protected $revisionCleanup = true;
    protected $historyLimit = 100;
    protected $revisionCreationsEnabled = true;

    public $table = 'leads';

    /**
     * primaryKey
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var string
     */
    protected $keyType = 'string';

    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = ['client_id','amo_lead_id','anketa_id','total','state_id','anketa_uuid','client_num','create_type','date_delivery','tag','amo_link_contact_id'];

    // TODO Это все унести в Амо по возможности, уведомление тоже убрать
    protected static function boot()
    {

        parent::boot();
        static::creating(function ($model) {
            $model->setAttribute($model->getKeyName(), (string) Str::uuid());
        });

        static::saved(function($model)
        {
            self::backtrace(); // Логирование trace

            $amo = new AmoCrm();
            switch ($model->state_id) {
                // Отправка уведомления При назначении анкеты
                case 4:
                    if (!empty($model->stylist_id))
                        if ($mail = AdminUser::find($model->stylist_id)->email)
                            @Mail::to($mail)->send(new StylistNotify($model->uuid, 4));

                    break;

                // Отправка уведомления При статусе "Проблема с подбором"
                case 5:
                    $leadControl = new LeadControl();
                    $leadControl->leadUuid = $model->uuid;
                    $leadControl->save();
                    break;
                case 14:
                    if ($model->clients->auth_token)
                        $amo->setClientAuthToken($model->amo_lead_id,$model->clients->auth_token);
                    break;
            }

            $pars = [
                'id' => $model->amo_lead_id,
                'state' => $model->state_id,
            ];
            // Статусы исключения
            if (in_array($model->state_id,[8,9,11,19,20,21,22,23,24,25])){
                $amo->update_lead($pars);
            }
            elseif ($model->state_id != 5 && $model->amo_lead_id) {
                if (!empty($model->amo_lead_id) && $model->state_id > 1) {
                    $pars['prev_state'] = $model->getOriginal('state_id');
                    $amo->updateLeadWithCheck($pars);
                }
            }

        });

    }

    /**
     * Связь с таблицей Клиентов
     * @return BelongsTo
     */
    public function clients(){

        return $this->belongsTo(Client::class,'client_id','uuid');
    }

    /**
     * Связь с таблицей Тегов
     * @return BelongsToMany
     */
    public function tags(){

        return $this->belongsToMany(Tags::class, 'leads_tags', 'lead_uuid', 'tag_id')->withTimestamps();
    }

    public function userResponsible(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }

    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function states(){

        return $this->belongsTo(LeadRef::class,'state_id','id');
    }

    /**
     * Связь с таблицей Оплат
     * @return BelongsTo
     */
    public function payments(){

        return $this->belongsTo(Payments::class,'uuid','lead_id');
    }

    /**
     * Связь с таблицей Пользователей
     * @return BelongsTo
     */
    public function stylists(){

        return $this->belongsTo(AdminUser::class,'stylist_id','id');
    }

    /**
     * Связь с таблицей Транзакции с бонусами
     * @return hasMany
     */

    public function bonus_transactions()
    {
        return $this->hasMany(BonusTransactions::class);
    }

    /**
     * TODO:: Подумать куда перенести
     * @param $client_uuid
     * @param $anketa_id
     * @param int $state_id
     * @param array $params
     * @param bool $contact_id
     * @return Lead|bool
     */
    public static function addLeadAmo($client_uuid, $anketa_uuid, $create_type, $state_id = 1, $delivery_at, $tag, $contact_id = false, $amo_params = false) {

        if (!$client = Client::where('uuid',$client_uuid)->first())
            return ['result_lead' => false, 'result_amo' => false, 'return_link'=>false, 'error' => 'no_client'];

        $data = [
            'name' => $client->name ?? false,
            'phone' => $client->phone ?? false,
            'email' => $client->email ?? false,
        ];

        if (isset($contact_id))
            $data['contact_id'] = $contact_id;

        if($amo_params) {
            $data = array_merge($data, $amo_params);
            $amo = new AmoCrm();
            $resp = $amo->add_lead($data, true);

            if(!$resp['status']) {
                $return_amo =  ['result_amo' => false, 'error' => $resp];
            } else if(!isset($resp['_embedded']) || !isset($resp['_embedded']['leads'][0]['id']) ) {
                $return_amo = ['result_amo' => false, 'error' => 'no_lead'];
            }

        } else $return_amo = ['result_amo'=> false, 'error' => 'no_params'];

        if(empty($return_amo) && !empty($resp))
            $return_amo = ['result_amo'=> true, 'amo_lead_id' => $resp['_embedded']['leads'][0]['id']];

        $lead = new Lead();
        $lead->client_id = $client_uuid;
        $lead->anketa_uuid = $anketa_uuid;
        if($return_amo['result_amo']) $lead->amo_lead_id = $resp['_embedded']['leads'][0]['id'];
        $lead->state_id = $state_id;
        $lead->delivery_at = $delivery_at;
        $lead->create_type = $create_type;
        $lead->tag = $tag;

        $lead->save();
        if($tag == 'BOXBERRY') {
            $delivery = new Delivery();
            $delivery->source = 'BOXBERRY';
            $delivery->delivery_point_id = Common::updatePvzID($amo_params['delivery_pvz']);
            $delivery->delivery_address = $amo_params['address_delivery'] ?? '';
            $delivery->lead()->associate($lead);
            $delivery->save();
        }

        return array_merge(['new_lead' => $lead, 'result_lead' => true], $return_amo);

    }

    /**
     * Связка контакта и сделки
     * @param $lead_id
     * @param $contact_id
     * @return bool|mixed
     */
    public static function linkLead($amo_lead_id, $contact_id){

        $amo = new AmoCrm();

        if (!$resp = $amo->link_lead_contact($amo_lead_id, $contact_id))
            return false;

        $lead = Lead::where('amo_lead_id',(int)$amo_lead_id)->first();
        $lead->amo_link_contact_id = $contact_id;
        $lead->save();

        return $lead;

    }

    /**
     * Связь с таблицей Анкет
     * @return BelongsTo
     */
    public function ankets(){

        return $this->belongsTo(Questionnaire::class,'anketa_id','id');
    }

    /**
     * Cвязь с таблицей Анкет по uuid
     * @return HasOne
     */
    public function questionnaire(){
        return $this->hasOne(Questionnaire::class,'uuid','anketa_uuid');
    }

    public function hasPayments(){
        return $this->hasMany(Payments::class,'lead_id','uuid');
    }

    function scopeFilter(Builder $builder, Filter $filters) {
        return $filters->apply($builder);
    }

    public function delivery(){
        return $this->hasMany(Delivery::class,'lead_id','uuid');
    }

    /**
     * Смена статуса сделки
     * @param Lead $lead
     * @param $state_id
     * @return bool
     */
    public static function stateChange(Lead $lead, $state_id){

        try {
            $lead->state_id = $state_id;
            $lead->save();
            return true;
        }
        catch (\Exception $e){
            return  false;
        }

    }

    // Пока есть АмоCRM используем amo_lead_id
    private function saveStateToAmo(int $amo_lead_id,int $new_local_id) {

        $pars = [
            'id' => 30008425,
            'state' => $new_local_id,
            'prev_state' => 9,
        ];

        $amo = new AmoCrm();
        $amo->updateLeadWithCheck($pars);


    }

    // Смена статуса c Анкета у клиента на ОС отправлена
    // 9 = Анкета у клиента
    // 10 = ОС отправлена
    public static function hourlyFromState($state = 9){

        self::whereStateId($state)->where("updated_at",'<=', now()->subHours(1)->format("Y-m-d H:i:s"))
            ->update(['state_id' => 10]);
        return true;

    }

    // TODO ПОИСК ОШИБКИ перезаписи данных по ТОТАЛ
    private function catch($id){
        Storage::disk('local')->put("logs/lead_catch_$id.log",json_encode(debug_backtrace(8,7)));
    }

    public function notes(){

        return $this->hasMany(Note::class,"order_id","amo_lead_id");
    }

    public function leadCorrections()
    {
        return $this->hasMany(LeadCorrections::class,"lead_uuid","uuid");
    }

    public function doli()
    {
        return $this->hasOne(DoliTransactions::class,"lead_uuid","uuid");
    }

    public function leadControl() {
        return $this->hasOne(LeadControl::class, 'leadUuid', 'uuid');
    }

    private static function backtrace(){


        $backtrace = Backtrace::create()->withArguments()->applicationPath(base_path());
        $data = [
            "auth" => !empty(auth()->user()) ? auth()->user()->name : "Процесс",
            "" => request()->server(),
            "trace" => $backtrace->frames()
        ];

        Log::channel('backtrace_lead')->info(json_encode($data));
        return true;
    }

    // ******************** vuex ********************

    // встроенные методы

    /**
     * Реализует фильтрацию
     * @param $query
     * @return LeadQueryBuilder
     */
    public function newEloquentBuilder($query): LeadQueryBuilder
    {
        return new LeadQueryBuilder($query);
    }

    // relations

    public function leadClient(): HasOne
    {
        return $this->hasOne(Client::class, 'uuid', 'client_id');
    }

    public function leadState(): HasOne
    {
        return $this->hasOne(LeadRef::class, 'id', 'state_id');
    }

    public function leadTag(): HasOne
    {
        return $this->hasOne(Tags::class, 'name', 'tag');
    }

}
