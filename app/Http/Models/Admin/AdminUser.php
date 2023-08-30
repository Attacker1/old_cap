<?php
namespace App\Http\Models\Admin;
use App\Http\Classes\Acl;
use App\Http\Models\Catalog\NotesAdvice;
use App\Http\Models\Common\Lead;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasRolesAndPermissions;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Модель для работы с пользователями интерфейса Капсула. Используется для авторизации. Таблица 'user'
 *
 * @package App\Http\Models\Admin
 * @property int $id
 * @property string $name
 * @property string|null $amo_name
 * @property string $email
 * @property string|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $disabled
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|\App\Http\Models\Admin\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read Collection|\App\Http\Models\Admin\Role[] $roles
 * @property-read int|null $roles_count
 * @method static Builder|AdminUser newModelQuery()
 * @method static Builder|AdminUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|AdminUser onlyTrashed()
 * @method static Builder|AdminUser query()
 * @method static Builder|AdminUser whereAmoName($value)
 * @method static Builder|AdminUser whereCreatedAt($value)
 * @method static Builder|AdminUser whereDeletedAt($value)
 * @method static Builder|AdminUser whereDisabled($value)
 * @method static Builder|AdminUser whereEmail($value)
 * @method static Builder|AdminUser whereEmailVerifiedAt($value)
 * @method static Builder|AdminUser whereId($value)
 * @method static Builder|AdminUser whereName($value)
 * @method static Builder|AdminUser wherePassword($value)
 * @method static Builder|AdminUser whereRememberToken($value)
 * @method static Builder|AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AdminUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AdminUser withoutTrashed()
 * @mixin \Eloquent
 */
class AdminUser extends Authenticatable
{
    use SoftDeletes, Notifiable, HasRolesAndPermissions;
    /**
     * Массовые назначаемые поля/аттрибуты для модели
     *
     * @var array
     */
    protected $fillable = [
         'email', 'updated_at','active'
    ];
    /**
     * Скрытые аттрибуты
     *
     * @var array
     */
    protected  $hidden = [
        'password', 'remember_token',
    ];


    protected  $table = "users";

    /**
     * Основной ключ таблицы
     * @var string
     */
    protected  $primaryKey  = 'id';


    /**
     * Поиск по имени роли
     * @param string $role_name
     * @return AdminUser[]|bool|Builder[]|Collection
     */
    public static function byRole(string $role_name){

        if (!empty($role_name)){

            $role_id = Role::where('slug',$role_name)->firstOrFail()->id;

            return AdminUser::with('roles')
                ->whereHas('roles',function ($q) use ($role_id){
                    $q->where('id',$role_id);
            })->where('disabled','!=',1)->orderBy('name')->get();
        }

        return false;
    }



}

