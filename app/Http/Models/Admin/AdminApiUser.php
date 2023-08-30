<?php

namespace App\Http\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Http\Models\Admin\AdminApiUser
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $password
 * @property string|null $token
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdminApiUser whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdminApiUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'password','updated_at','token'
    ];

}
