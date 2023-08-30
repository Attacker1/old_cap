<?php

namespace App\Http\Models\Vuex\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Http\Models\Vuex\Settings\ManageBlock
 *
 * @property int $id
 * @property string $namespace
 * @property string $slug
 * @property string|null $title
 * @property string $type
 * @property array $roles
 * @property string|null $tooltip
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $sort
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Settings\ManageBlockItem[] $items
 * @property-read int|null $items_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereNamespace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereTooltip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlock whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManageBlock extends Model
{

    // ******************** vuex ********************

    protected $guarded = [];

    public $casts = [
        'roles' => 'array'
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ManageBlockItem::class,'block_id','id')->orderBy('sort','asc');
    }

}
