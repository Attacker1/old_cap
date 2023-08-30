<?php

namespace App\Http\Models\AdminClient;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Http\Models\AdminClient\ClientStatus
 *
 * @property int $id
 * @property string $name
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientStatus onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientStatus whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|ClientStatus withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientStatus withoutTrashed()
 * @mixin \Eloquent
 */
class ClientStatus extends Model
{
    protected $fillable = ['name', 'slug'];
    use SoftDeletes;
}