<?php

namespace App\Http\Models\Stock;

use App\Http\Filters\Leads\LeadQueryBuilder;
use App\Http\Filters\Stock\StockProductQueryBuilder;
use Bnb\Laravel\Attachments\HasAttachment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockProducts
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $name
 * @property string|null $description
 * @property int|null $category_id
 * @property int|null $supplier_id
 * @property string|null $article
 * @property string|null $code Штрихкод/код товара
 * @property string|null $externalCode
 * @property string|null $pathName
 * @property int|null $price
 * @property int|null $salePrice
 * @property int|null $stock
 * @property int|null $quantity
 * @property int $reserve
 * @property int|null $inTransit
 * @property string|null $uuidHref
 * @property string|null $slug
 * @property int $visible
 * @property string|null $external_uuid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Stock\StockAttributes[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\Http\Models\Stock\StockCategories|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Stock\StockPrices[] $prices
 * @property-read int|null $prices_count
 * @property-read \App\Http\Models\Stock\StockSupplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts with($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereArticle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereExternalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereInTransit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts wherePathName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereReserve($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereSalePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereUuidHref($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereVisible($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Bnb\Laravel\Attachments\Attachment[] $attachments
 * @property-read int|null $attachments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Stock\StockCarts[] $carts
 * @property-read int|null $carts_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockProducts whereExternalUuid($value)
 */
class StockProducts extends Model
{
    use HasAttachment;

    protected $casts = [
        'productFolder' => 'array',
    ];

    protected $fillable = [ 'name','uuid','pathName','description','article','externalCode','code','price','salePrice',
                            'reserve','quantity','stock','inTransit',"external_uuid"];

    public function category() {
        return $this->belongsTo(StockCategories::class, 'category_id', 'id');
    }

    public function supplier() {
        return $this->belongsTo(StockSupplier::class, 'supplier_id', 'id');
    }

    public function prices() {
        return $this->hasMany(StockPrices::class);
    }

//    public function attributes() {
//        return $this->hasMany(StockAttributes::class,'stock_product_id', 'id');
//    }

    public function attributes() {
        return $this->belongsToMany(StockAttributes::class,'stock_product_attributes','product_id','attribute_id')->withPivot(['value']);;
    }

    public function carts() {
        return $this->belongsToMany(StockCarts::class, 'stock_carts_stock_products', 'stock_products_id', 'stock_carts_id')->withTimestamps();
    }

    // ******************** vuex ********************

    // встроенные методы

    /**
     * Реализует фильтрацию
     * @param $query
     * @return StockProductQueryBuilder
     */
    public function newEloquentBuilder($query): StockProductQueryBuilder
    {
        return new StockProductQueryBuilder($query);
    }
}
