<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Catalog\Preset
 *
 * @property int $id
 * @property string $name
 * @property array|null $params
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Preset array()
 * @method static \Illuminate\Database\Eloquent\Builder|Preset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Preset newQuery()
 * @method static \Illuminate\Database\Query\Builder|Preset onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Preset query()
 * @method static \Illuminate\Database\Eloquent\Builder|Preset whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preset whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Preset whereParams($value)
 * @method static \Illuminate\Database\Query\Builder|Preset withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Preset withoutTrashed()
 * @mixin \Eloquent
 */
class Preset extends Model
{
    use SoftDeletes;

    protected $casts = [
        'params' => 'array'
    ];

    public $timestamps = false;

    public $table = 'presets';

    public function scopeArray($q){
        return $q->orderBy('name','asc')->pluck('name','id');
    }
}
