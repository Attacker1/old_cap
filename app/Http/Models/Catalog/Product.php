<?php

namespace App\Http\Models\Catalog;
use App\Http\Models\Admin\AdminUser;

use App\Http\Models\Common\LeadCorrections;
use App\Http\Models\Common\LeadCorrectionsProducts;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Модель для работы с товарами
 * Class Product
 *
 * @package App\Http\Models\Catalog
 * @property int $id
 * @property int|null $category_id
 * @property int|null $brand_id
 * @property string|null $sku
 * @property string $name
 * @property string|null $amo_name
 * @property string|null $content
 * @property string|null $price
 * @property string|null $slug
 * @property string|null $note
 * @property int|null $visible
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $external_id
 * @property string|null $size
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Catalog\Attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Http\Models\Catalog\Brand|null $brands
 * @property-read \App\Http\Models\Catalog\Category|null $cats
 * @property-read \Illuminate\Database\Eloquent\Collection|LeadCorrections[] $leadCorrections
 * @property-read int|null $lead_corrections_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Catalog\Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Catalog\Tags[] $tags
 * @property-read int|null $tags_count
 * @property-read AdminUser|null $users
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAmoName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereBrandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereVisible($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes, HasAttachment;
    /**
     * Таблица
     * @var string
     */
    protected $table = 'products';

    protected $fillable = ['name','category_id','brand_id','visible','price','content','slug','sku','created_at','updated_at','external_id','size'];

    /**
     * Связь с таблицей пользователей
     * @return BelongsTo
     */
    public function users(){

        return $this->belongsTo(AdminUser::class,'user_id','id');
    }

    /**
     * Связь с таблицей категорий
     * @return BelongsTo
     */
    public function cats(){

        return $this->belongsTo(Category::class,'category_id','id');
    }

    /**
     * Связь с таблицей категорий
     * @return BelongsTo
     */
    public function brands(){

        return $this->belongsTo(Brand::class,'brand_id','id');
    }

    /**
     * Связь с таблицей атрибутов
     * @return BelongsToMany
     */
    public function attributes(){

        return $this->belongsToMany(Attribute::class,'products_attributes','product_id','attribute_id')->withPivot(['value']);
    }

    /**
     * Связь с таблицей атрибутов
     * @return BelongsToMany
     */
    public function notes(){

        return $this->belongsToMany(Note::class,'notes_products','product_id','note_id')->withPivot(['advice']);
    }

    /**
     * Связь с таблицей Тегов
     * @return BelongsToMany
     */
    public function tags(){

        return $this->belongsToMany(Tags::class,'products_tags','product_id','tag_id');
    }

    /**
     * Связь с таблицей атрибутов
     * @return BelongsToMany
     */
    public function leadCorrections(){

        return $this->belongsToMany(LeadCorrections::class,LeadCorrectionsProducts::class,'correction_id','product_id',
            "id","id")->withPivot(["price","lead_uuid"]);
    }

    public static function getProductById($id){

        return self::with(["brands","attachments"])->where("id",$id)->first();
    }



}
