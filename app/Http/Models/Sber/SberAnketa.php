<?php

namespace App\Http\Models\Sber;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Sber\SberAnketa
 *
 * @property string $uuid
 * @property int $id
 * @property string $answer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa query()
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SberAnketa whereUuid($value)
 * @mixin \Eloquent
 * @property string|null $client_uuid
 * @property array|null $data
 * @property int|null $primary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sber\SberAnketa whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sber\SberAnketa whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Sber\SberAnketa wherePrimary($value)
 */
class SberAnketa extends Model
{

    protected $primaryKey = 'id';
    protected $casts = [
        'data' => 'array'
    ];

    protected $fillable = ['uuid','id','answer','client_uuid','id'];


}
