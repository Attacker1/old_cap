<?php

namespace App\Http\Models\Admin;

use App\Http\Models\Common\LeadRef;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Models\Admin\ClientLeadRef
 *
 * @property int $client_state_id
 * @property int $lead_ref_id
 * @method static \Illuminate\Database\Eloquent\Builder|ClientLeadRef newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientLeadRef newQuery()
 * @method static \Illuminate\Database\Query\Builder|ClientLeadRef onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientLeadRef query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClientLeadRef whereClientStateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClientLeadRef whereLeadRefId($value)
 * @method static \Illuminate\Database\Query\Builder|ClientLeadRef withTrashed()
 * @method static \Illuminate\Database\Query\Builder|ClientLeadRef withoutTrashed()
 * @mixin \Eloquent
 * @property int $id_
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Admin\ClientLeadRef whereId($value)
 */
class ClientLeadRef extends Model
{
    use SoftDeletes;

    protected $table = 'states_leadref';

}
