<?php
namespace App\Http\Models\AdminClient;

use App\Http\Models\Common\Lead;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\AdminClient\FeedbackgeneralQuize
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $client_uuid
 * @property string|null $lead_id
 * @property string|null $status
 * @property string|null $personal_attitude
 * @property string|null $general_impression
 * @property string|null $buy_more
 * @property string|null $stylist_note_wanted
 * @property string|null $other_any_comments
 * @property string|null $new_stylist
 * @property string|null $design
 * @property string|null $recommended
 * @property string|null $mark_reason
 * @property string|null $mark_up_actions
 * @property string|null $repeat_date
 * @property string|null $repeat_date_own
 * @property string|null $delivery_mark
 * @property string|null $delivery_comment
 * @property int|null $amo_id
 * @property int|null $old_id
 * @property array|null $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Http\Models\AdminClient\Client|null $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\AdminClient\FeedbackQuize[] $feedbackgQuize
 * @property-read int|null $feedbackg_quize_count
 * @property-read Lead|null $lead
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize newQuery()
 * @method static \Illuminate\Database\Query\Builder|FeedbackgeneralQuize onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereAmoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereBuyMore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereDeliveryComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereDeliveryMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereDesign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereGeneralImpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereLeadId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereMarkReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereMarkUpActions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereNewStylist($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereOldId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereOtherAnyComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize wherePersonalAttitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereRecommended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereRepeatDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereRepeatDateOwn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereStylistNoteWanted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedbackgeneralQuize whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|FeedbackgeneralQuize withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FeedbackgeneralQuize withoutTrashed()
 * @mixin \Eloquent
 */
class FeedbackgeneralQuize extends Model
{

    use SoftDeletes;
    protected $fillable = ['uuid', 'client_uuid', 'lead_id', 'personal_attitude', 'general_impression', 'buy_more', 'stylist_note_wanted', 'other_any_comments', 'new_stylist', 'design', 'recommended', 'mark_reason', 'mark_up_actions', 'repeat_date', 'repeat_date_own', 'delivery_mark', 'delivery_comment', 'data'];

    protected $casts = [
        'data' => 'array'
    ];

    public function feedbackgQuize()
    {
        return $this->hasMany(FeedbackQuize::class);
    }

    /**
     * Связь с таблицей клиенты
     * @return BelongsTo
     */
    public function client() {
        return $this->belongsTo(Client::class,'client_uuid','uuid','feedbackgeneral_quizes');
    }

    /**
     * Связь с таблицей сделки
     * @return BelongsTo
     */
    public function lead() {
        return $this->belongsTo(Lead::class,'lead_id','uuid','feedbackgeneral_quizes');
    }

    public function paidItems()
    {
        return self::feedbackgQuize()->where('action_result',"buy")->get();
    }

    // TODO: Перенести в Сервис или празднить вместе с АМО

}