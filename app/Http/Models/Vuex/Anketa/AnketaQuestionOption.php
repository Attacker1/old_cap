<?php

namespace App\Http\Models\Vuex\Anketa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Vuex\Anketa\AnketaQuestionOption
 *
 * @property int $id
 * @property int|null $question_id
 * @property int $active
 * @property int|null $sort
 * @property int|null $group_id
 * @property string|null $slug
 * @property int|null $old_key
 * @property string|null $text
 * @property string|null $type Тип инпута
 * @property string|null $placeholder
 * @property int $text_thumb
 * @property int|null $next_question
 * @property string|null $emoji
 * @property string|null $image
 * @property int|null $position_top
 * @property int|null $position_left
 * @property string|null $pallete
 * @property int|null $option_key
 * @property int|null $select_all
 * @property int $required
 * @property string|null $error_text
 * @property string|null $related_to
 * @property int|null $checked
 * @property string|null $default_value
 * @property int|null $unselect_all
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read array $pallete_colors
 * @property-read mixed $typed
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestion|null $question
 * @property-read AnketaQuestionOption|null $recursiveParent
 * @property-read \App\Http\Models\Vuex\Anketa\AnketaQuestion|null $recursiveQuestion
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestionOption onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereDefaultValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereEmoji($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereErrorText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereNextQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereOldKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereOptionKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption wherePallete($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption wherePlaceholder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption wherePositionLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption wherePositionTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereRelatedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereSelectAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereTextThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereUnselectAll($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionOption whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestionOption withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestionOption withoutTrashed()
 * @mixin \Eloquent
 */
class AnketaQuestionOption extends Model
{
    use SoftDeletes;

    protected $table = 'anketa_question_options';
    protected $guarded = [];

    public function question() : HasOne
    {
        return $this->hasOne(AnketaQuestion::class,'id','question_id');
    }

    public function getPalleteColorsAttribute(): array
    {
        return array_values(explode(',',$this->attributes['pallete']) ?: []);
    }

    public function setPalleteAttribute($value)
    {
        $this->attributes['pallete'] = preg_replace("/[^,0-9A-z#]/", '', $value);
    }

    public function getTypedAttribute()
    {
        return $this->attributes['type'];
    }

    public function getRelatedToAttribute(){
        return $this->attributes['related_to'] ? explode(',', $this->attributes['related_to']) : null;
    }

    public function setRelatedToAttribute($value){
        $this->attributes['related_to'] = $value ? implode(',',$value) : null;
    }

    public function setNextQuestionAttribute($value) {
        $this->attributes['next_question'] = $value == 0 ? NULL : $value;
    }

    public function recursiveQuestion(): HasOne
    {
        return $this->hasOne(AnketaQuestion::class,'id','next_question')->with('recursiveOptions');
    }

    public function recursiveParent(){
       return $this->hasOne(self::class,'next_question','question_id')->with('recursiveParent');
    }

//    public function setRequiredAttribute($value) {
////        dd($value);
//    }
//
//    public function setErrorTextAttribute($value) {
//        $this->attributes['error_text'] = $value ?: NULL;
//    }




}
