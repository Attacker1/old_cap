<?php

namespace App\Http\Models\Vuex\Anketa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Http\Models\Vuex\Anketa\AnketaOptionsGroup
 *
 * @property int $id
 * @property string $title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Vuex\Anketa\AnketaQuestionOption[] $optionsPrints
 * @property-read int|null $options_prints_count
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaOptionsGroup onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaOptionsGroup whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaOptionsGroup withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaOptionsGroup withoutTrashed()
 * @mixin \Eloquent
 */
class AnketaOptionsGroup extends Model
{
    use SoftDeletes;

    protected $table = 'anketa_options_groups';


    public function optionsPrints(){
        return $this->hasMany(AnketaQuestionOption::class,'group_id','id')
            ->whereNull('question_id')->whereNotNull('group_id');
    }

}
