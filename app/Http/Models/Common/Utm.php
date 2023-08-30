<?php

namespace App\Http\Models\Common;

use App\Http\Models\AdminClient\Questionnaire;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Http\Models\Common\Utm
 *
 * @property int $id
 * @property int|null $amo_id
 * @property string|null $lead_uuid
 * @property string|null $utm_source
 * @property string|null $utm_medium
 * @property string|null $utm_campaign
 * @property string|null $utm_content
 * @property string|null $utm_term
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Questionnaire|null $questionnarie
 * @method static \Illuminate\Database\Eloquent\Builder|Utm newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Utm newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Utm query()
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereAmoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereLeadUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereUtmCampaign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereUtmContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereUtmMedium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereUtmSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Utm whereUtmTerm($value)
 * @mixin \Eloquent
 */
class Utm extends Model
{

    protected $fillable = ['anketa_uuid', 'amo_id', 'lead_uuid', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];

    public function questionnarie() {
        return $this->belongsTo(Questionnaire::class, 'amo_id','amo_id');
    }
}
