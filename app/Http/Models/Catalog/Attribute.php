<?php

namespace App\Http\Models\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Модель для работы с атрибутами/характеристиками товара
 * Class Attribute
 *
 * @package App\Http\Models\Catalog
 * @property int $id
 * @property string $name
 * @property array|null $params
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $preset_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Catalog\Category[] $categories
 * @property-read int|null $categories_count
 * @property-read \App\Http\Models\Catalog\Preset|null $presets
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute array()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Query\Builder|Attribute onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute wherePresetId($value)
 * @method static \Illuminate\Database\Query\Builder|Attribute withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Attribute withoutTrashed()
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    use SoftDeletes;

    protected $casts = [
        'params' => 'array'
    ];

    public $timestamps = false;

    public $table = 'attributes';

    public function scopeArray($q){
        return $q->orderBy('name','asc')->pluck('name','id');
    }

    /**
     * Связь с таблицей категорий
     * @return BelongsToMany
     */
    public function categories(){

        return $this->belongsToMany(Category::class,'categories_attributes');
    }

    public function presets(){

            return $this->belongsTo(Preset::class,'preset_id','id');
    }


}
