<?php

namespace App\Http\Models\Catalog;

use App\Http\Models\Admin\AdminUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Работа с категориями
 * Class Category
 *
 * @package App\Http\Models\Catalog
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $content
 * @property string $slug
 * @property int|null $user_id
 * @property int|null $visible
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Catalog\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read AdminUser|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Category array()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Query\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereVisible($value)
 * @method static \Illuminate\Database\Query\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Category withoutTrashed()
 * @mixin \Eloquent
 */
class Category extends Model
{
    use SoftDeletes;

    /**
     * Таблица
     * @var string
     */
    protected $table = 'categories';

    protected $fillable = ['name','user_id','comment','content','state','created_at','updated_at','slug','parent_id'];

    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function users(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }

    /**
     * Связь с таблицей атрибутов
     * @return BelongsToMany
     */
    public function attributes(){

        return $this->belongsToMany(Attribute::class,'categories_attributes');
    }

    public function scopeArray($q){
        return $q->whereNotIn('id',[1])->whereNull('visible')->orderBy('name','asc')->pluck('name','id');
    }
}
