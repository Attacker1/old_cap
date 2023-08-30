<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Http\Models\Common\Payother
 *
 * @property int $id
 * @property float|null $amount
 * @property array|null $payload
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\Payother whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payother extends Model
{
    protected $fillable = ['amount', 'payload'];
    protected $casts = [
        'payload' => 'array'
    ];
}
