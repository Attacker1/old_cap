<?php

namespace App\Http\Models\Common;

use App\Http\Models\Admin\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * App\Http\Models\Common\LeadRef
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Role[] $leadref
 * @property-read int|null $leadref_count
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef newQuery()
 * @method static \Illuminate\Database\Query\Builder|LeadRef onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef query()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LeadRef whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|LeadRef withTrashed()
 * @method static \Illuminate\Database\Query\Builder|LeadRef withoutTrashed()
 * @mixin \Eloquent
 */
class LeadRef extends Model
{
    use SoftDeletes;
    /**
     * primaryKey
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    public $table = 'leads_refs';


    /**
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = ['id', 'name'];

    public function leadref()
    {
        return $this->belongsToMany(Role::class,'leadref_roles');
    }

    /**
     * @param $role_name
     * @return bool|Collection
     */
    public static function getList($role_name){

        if( !$role = Role::where('slug',$role_name)->first())
            return false;

        return self::with('leadref')->whereHas('leadref',function($q) use ($role){
            $q->where('role_id',$role->id);
        })->whereNull('parent_id')
            ->orderBy('id')
            ->pluck('name','id');
    }


    /**
     * Получение подстатусов
     * @param $role_name
     * @param $state_id
     * @return LeadRef[]|bool|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getSubList($role_name, $state_id)
    {

        if (!$role = Role::where('slug', $role_name)->first())
            return false;

        $items = self::with('leadref')->whereHas('leadref', function ($q) use ($role,$state_id) {
            $q->where('role_id', $role->id)
                ->where('parent_id', $state_id);
        })
            ->orderBy('id')->get();

        return $items->count() > 0 ?  $items : false;
    }
}
