<?php

namespace App\Http\Models\Catalog;

use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Common\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Bnb\Laravel\Attachments\HasAttachment;

/**
 * App\Http\Models\Catalog\Note
 *
 * @property int $id
 * @property int|null $order_id
 * @property int|null $user_id
 * @property string|null $content
 * @property string|null $comment
 * @property int $state
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property float|null $paid
 * @property string|null $content_advice
 * @property-read \App\Http\Models\Catalog\NotesAdvice|null $advice
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Http\Models\Catalog\NoteCustomAdvice|null $customAdvice
 * @property-read Lead|null $leads
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Catalog\Product[] $products
 * @property-read int|null $products_count
 * @property-read AdminUser|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Query\Builder|Note onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereContentAdvice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Note withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Note withoutTrashed()
 * @mixin \Eloquent
 */
class Note extends Model
{
    use SoftDeletes,HasAttachment;

    /**
     * @var array
     */
    protected $fillable = ['order_id','content','user_id','comment','content','state','created_at','updated_at'];

    /**
     * Связь с таблицей товаров
     * @return BelongsToMany
     */
    public function products(){

        return $this->belongsToMany(Product::class,'notes_products','note_id','product_id')->withPivot(['order','advice']);
    }

    /**
     * Связь с таблицей сочетаний
     * @return HasOne
     */
    public function advice(){

        return $this->hasOne(NotesAdvice::class);
    }

    /**
     * Связь с таблицей сочетаний произвольных
     * @return HasOne
     */
    public function customAdvice(){

        return $this->hasOne(NoteCustomAdvice::class);
    }


    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function users(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }

    /**
     * Обновление сочетаний самих продуктов
     * @param $values
     * @return bool
     */
    public function updateAdvice($values){

        $advice = $this->advice;
        if(!$advice){
            $advice = new NotesAdvice();
            $advice->note_id = $this->id;
        }
        $advice->value = $values;
        $advice->push();

        return true;

    }

    /**
     * Обновление сочетаний самих продуктов
     * @param $values
     * @return bool
     */
    public function updateCustomAdvice($values){

        $advice = $this->customAdvice;
        if(!$advice){
            $advice = new NoteCustomAdvice();
            $advice->note_id = $this->id;
        }
        $advice->value = $values;
        $advice->push();

        return true;

    }

    /**
     * Связь с таблицей Сделок
     * @return BelongsTo
     */
    public function leads(){

        return $this->belongsTo(Lead::class,'order_id','amo_lead_id');
    }
}
