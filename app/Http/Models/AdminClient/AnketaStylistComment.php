<?php
namespace App\Http\Models\AdminClient;

use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Models\Admin\AdminUser;

/**
 * App\Http\Models\AdminClient\AnketaStylistComment
 *
 * @property int $id
 * @property int|null $stylist_id
 * @property int|null $anketa_id
 * @property string|null $anketa_uuid
 * @property string|null $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read AdminUser|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaStylistComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereAnketaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereAnketaUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereStylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaStylistComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaStylistComment withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaStylistComment withoutTrashed()
 * @mixin \Eloquent
 */
class AnketaStylistComment extends Model
{
    use SoftDeletes;
    protected $fillable = ['stylist_id', 'anketa_id', 'content'];

    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function user(){

        return $this->belongsTo(AdminUser::class,'stylist_id', 'id');
    }
}