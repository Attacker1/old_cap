<?php

namespace App\Http\Models\Admin\Trans;

use App\Http\Models\Common\LeadRef;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Admin\Trans\OldAnketa
 *
 * @method static \Illuminate\Database\Eloquent\Builder|OldAnketa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldAnketa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OldAnketa query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $code
 * @property string $created
 * @property string $updated
 * @property array|null $data
 * @property string|null $status
 * @property float|null $amount
 * @property int|null $amo_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereAmoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Trans\OldAnketa whereUpdated($value)
 */
class OldAnketa extends Model
{
    protected $casts = [
        'data' => 'array'
    ];

    public $table = 'anketa';


}
