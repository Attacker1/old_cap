<?php

namespace App\Http\Models\Catalog;

use App\Http\Models\Common\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Http\Models\Catalog\Tags
 *
 * @property int $id
 * @property string $name
 * @property string|null $color
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Lead[] $leads
 * @property-read int|null $leads_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tags newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tags whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tags extends Model
{

    protected $fillable = ['name'];

    public static function checkTag($tags){
        $tags_db = self::all();
        $array_ids = [];
        foreach ($tags as $k=>$v ){
            if ($tags_db->where('name',$v)->count() == 0){
                $array_ids[] = self::create(['name' => $v])->id;
            }
            else {
                $array_ids[] = $tags_db->where('name',$v)->first()->id;
            }
        }

        return $array_ids;


    }

    /**
     * Связь с таблицей Сделок
     * @return BelongsToMany
     */
    public function leads(){

        return $this->belongsToMany(Lead::class, 'leads_tags', 'tag_id', 'lead_uuid')->withTimestamps();
    }
}
