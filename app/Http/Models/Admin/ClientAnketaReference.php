<?php

namespace App\Http\Models\Admin;

use App\Http\Models\AdminClient\Client;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Admin\ClientAnketaReference
 *
 * @property int $id
 * @property string $client_uuid
 * @property string $anketa_uuid
 * @property string $photo
 * @property string $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference whereAnketaUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference whereClientUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientAnketaReference whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientAnketaReference extends Model
{
    protected $fillable = ['client_uuid','anketa_uuid','photo','comment'];

}
