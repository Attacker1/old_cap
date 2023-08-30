<?php

namespace App\Http\Models\Vuex\Anketa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Vuex\Anketa\AnketaInputType
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $hidden
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType newQuery()
 * @method static \Illuminate\Database\Query\Builder|AnketaInputType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereHidden($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnketaInputType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|AnketaInputType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AnketaInputType withoutTrashed()
 * @mixin \Eloquent
 */
class AnketaInputType extends Model
{
    use SoftDeletes;

    protected $table = 'anketa_input_types';
}
