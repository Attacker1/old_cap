<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;

/**
 * Модель цветов каталога
 * Class ColorsRef
 *
 * @package App\Http\Models\Catalog
 * @property int $id
 * @property string|null $name
 * @property string|null $hex
 * @property int|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef whereHex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColorsRef whereValue($value)
 * @mixin \Eloquent
 */
class ColorsRef extends Model
{
    /**
     * Таблица
     * @var string
     */
    protected $table = 'colors_refs';

    /**
     * Заполняемые атрибуты
     * @var array
     */
    protected $fillable = ['name','hex','value','created_at','updated_at'];
}
