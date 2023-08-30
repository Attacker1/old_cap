<?php

namespace App\Http\Models\Vuex\Settings;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Vuex\Settings\ManageBlockItem
 *
 * @property int $id
 * @property string $key
 * @property int $sort
 * @property array|null $roles
 * @property int $block_id
 * @property string $title
 * @property string $position
 * @property string|null $tooltip
 * @property string|null $input_type
 * @property string|null $input_value
 * @property mixed|null $input_rules
 * @property string|null $input_mask
 * @property string|null $filter
 * @property int $sortable
 * @property int|null $disabled
 * @property string|null $view_type
 * @property string|null $view_class
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereBlockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereFilter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereInputMask($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereInputRules($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereInputType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereInputValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereRoles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereSortable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereTooltip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereViewClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Vuex\Settings\ManageBlockItem whereViewType($value)
 * @mixin \Eloquent
 */
class ManageBlockItem extends Model
{
    // ******************** vuex ********************

    protected $guarded = [];

    public $casts = [
        'roles' => 'array'
    ];

}
