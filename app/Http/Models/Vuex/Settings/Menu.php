<?php

namespace App\Http\Models\Vuex\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Http\Models\Vuex\Settings\Menu
 *
 * @property int $id
 * @property int|null $sort
 * @property int|null $active
 * @property string|null $title
 * @property int|null $parent
 * @property string|null $icon
 * @property string|null $h_icon
 * @property string|null $page
 * @property string|null $route_name
 * @property string|null $user_rights
 * @property int|null $new-tab
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $root
 * @property string|null $bullet
 * @property string|null $newTab
 * @property-read \Illuminate\Database\Eloquent\Collection|Menu[] $children
 * @property-read int|null $children_count
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu query()
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereBullet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereHIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereNewTab($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu wherePage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereRoot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Menu whereUserRights($value)
 * @mixin \Eloquent
 */
class Menu extends Model
{

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class,'parent','id');
    }
}
