<?php

namespace App\Http\Models\Common;

use App\Http\Models\Admin\AdminUser;
use App\Jobs\SendEmailJob;
use App\Mail\StylistNotify;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Jobs\NoterNamesJob;

/**
 * App\Http\Models\Common\LeadControl
 *
 * @property int $id
 * @property string|null $leadUuid
 * @property int $stylistNotified
 * @property int $methodistNotified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Models\Common\Lead|null $lead
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl whereLeadUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl whereMethodistNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl whereStylistNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadControl whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LeadControl extends Model
{
    protected $fillable = ['leadUuid'];

    public function lead() {
        return $this->hasOne(Lead::class, 'uuid', 'leadUuid');
    }

    // Все отлично, dct TODO для идеала

    // TODO: @Dmitry
    // 1) Завернуть отправку почты в очередь
    // 2) Обвесить try-catch, если что то пойдет не так (почтовик отвалится к примеру или в моделе не будет свойства обьекта)?
    public static function checkStylist()
    {

        try {
            // Удаляем сделку с учета, если статус изменили
            /*foreach (LeadControl::get() as $item) {  // TODO: @Dmitry  3) Тут медленно , переделать на выборку сразу в запросе
                $lead = Lead::where('uuid', $item->leadUuid)->first();
                if ($lead->state_id !== 5) {
                    $item->delete();
                }
            }*/
            LeadControl::with('lead')->whereHas('lead',function($q) {
                $q->where('state_id', '!=', 5);
            })->delete();


            // Если капсула больше 2 дней в "Проблеме с подбором"
            $twoDaysFailedLeads = LeadControl::where([
                ['stylistNotified', false],
                ['created_at', '<=', Carbon::now()->subDays(2)->toDateTimeString()]
            ])->get();

            // Если капсула больше 3 дней в "Проблеме с подбором"
            $threeDaysFailedLeads = LeadControl::where([
                ['stylistNotified', true],
                ['methodistNotified', false],
                ['created_at', '<=', Carbon::now()->subDays(3)->toDateTimeString()]
            ])->get();

            foreach ($twoDaysFailedLeads as $item) {
                if ($lead = Lead::where('uuid', $item->leadUuid)->first()) {
                    if (!empty($lead->stylist_id)) {
                        if ($mail = AdminUser::find($lead->stylist_id)->email) {
                            dispatch(new SendEmailJob($mail, $lead->uuid, 5, config('mailData.twoDayFailedMailSubject')));
//                            @Mail::to($mail)->send(new StylistNotify($lead->uuid, 5, config('mailData.twoDayFailedMailSubject')));
                            $item->stylistNotified = true;
                            $item->save();
                        }
                    }
                }
            }

            // TODO: @Dmitry  4) Вынести параметры количества дней, почты, текста (тут и выше в foreach) сообщения в конфиг или в UI (но это дольше конечно)
            // Почты и текста вынесены в конфиг
            foreach ($threeDaysFailedLeads as $item) {
                if ($lead = Lead::where('uuid', $item->leadUuid)->first()) {
                    dispatch(new SendEmailJob(config('mailData.mailMethodist'), $lead->uuid, 5, config('mailData.threeDayFailedMilSubject'), 'methodist'));
//                    @Mail::to(config('mailData.mailMethodist'))->send(new StylistNotify($lead->uuid, 5, config('mailData.threeDayFailedMilSubject'), 'methodist'));
                    $item->methodistNotified = true;
                    $item->save();
                }
            }
        } catch (\Exception $e) {
            Log::error('LeadControl', [$e]);
        }
    }
}
