<?php

namespace App\Http\Models\Admin;

use App\Http\Models\Common\LeadRef;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Admin\Role
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Common\LeadRef[] $leadref
 * @property-read int|null $leadref_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Admin\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role array()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Admin\Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Admin\Role withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Models\Admin\Role withoutTrashed()
 * @mixin \Eloquent
 */
class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    public function leadref()
    {
        return $this->belongsToMany(LeadRef::class,'leadref_roles');
    }


    public function scopeArray($q){
        return $q->orderBy('name','asc')->pluck('name','id');
    }
}
