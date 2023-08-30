<?php

namespace App\Http\Models\Common;

use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Http\Models\Common\LeadCorrections
 *
 * @property-read \App\Http\Models\Common\Lead $leads
 * @property-read Note $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read AdminUser $users
 * @method static \Illuminate\Database\Eloquent\Builder|LeadCorrections newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadCorrections newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LeadCorrections query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $lead_uuid Товары из Записки > notes.id
 * @property int|null $note_id Товары из Записки > notes.id
 * @property mixed|null $data
 * @property int|null $user_id Пользователь > users.id
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereLeadUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereNoteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Common\LeadCorrections whereUserId($value)
 */
class LeadCorrections extends Model
{

    protected $fillable = ["lead_uuid","note_id","data","user_id","deleted_at",];


    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function leads(){

        return $this->belongsTo(Lead::class,'lead_uuid','uuid');
    }

    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function notes(){

        return $this->belongsTo(Note::class);
    }

    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function users(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }


    public function products(){

        return $this->belongsToMany(Product::class,LeadCorrectionsProducts::class,
            'correction_id','product_id',"id","id")->withPivot(["price","lead_uuid"]);
    }
}
