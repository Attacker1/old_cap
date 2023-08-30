<?php

namespace App\Http\Models\Vuex\Anketa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Vuex\Anketa\AnketaBuilder
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $slug
 * @property int|null $is_current
 * @property array|null $questions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaBuilder onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder questions($slug)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder queue($slug, $questionID)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereQuestions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaBuilder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaBuilder withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaBuilder withoutTrashed()
 * @mixin \Eloquent
 */
class AnketaBuilder extends Model
{
    use SoftDeletes;

    protected $table = 'anketa_builder';
    protected $fillable = ['title','slug','is_current','questions'];

    protected  $casts = [
        'questions' => 'array'
    ];


    // scoups

    /**
     * @param $query
     * @param $slug
     * @return mixed
     */
    public function scopeQuestions($query,$slug){
        return $query->where('slug', $slug)->first()->questions;
    }

    public function scopeQueue($query,$slug,$questionID){

    }

}
