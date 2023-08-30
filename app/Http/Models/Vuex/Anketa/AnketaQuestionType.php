<?php

namespace App\Http\Models\Vuex\Anketa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Anketa\AnketaQuestionType
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestionType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestionType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaQuestionType withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $sample
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaQuestionType whereSample($value)
 */
class AnketaQuestionType extends Model
{
    use SoftDeletes;

    protected $table = 'anketa_question_types';
}
