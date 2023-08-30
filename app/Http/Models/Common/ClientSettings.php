<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Common\ClientSettings
 *
 * @property int $id
 * @property string|null $name
 * @property array|null $params
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientSettings onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientSettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ClientSettings withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientSettings withoutTrashed()
 * @mixin \Eloquent
 */
class ClientSettings extends Model
{
    use SoftDeletes;

    protected $casts = [
        'params' => 'array'
    ];


}
