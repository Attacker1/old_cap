<?php

namespace App\Http\Models\Common;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Common\BonusRef
 *
 * @property int $id
 * @property string|null $name
 * @property int $points
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef query()
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef wherePoints($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BonusRef whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BonusRef extends Model
{
    //
}
