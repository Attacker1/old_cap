<?php

namespace App\Http\Models\Admin;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Admin\ServiceToken
 *
 * @property int $id
 * @property string|null $service
 * @property string|null $token
 * @property string|null $refresh_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $expired_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereService($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ServiceToken extends Model
{
   //
}
