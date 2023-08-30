<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockCategories
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $name
 * @property string|null $description
 * @property int|null $parent_id
 * @property string|null $slug
 * @property int|null $visible
 * @property string|null $externalCode
 * @property string|null $externalId
 * @property array|null $productFolder
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Stock\StockProducts[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereExternalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereProductFolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories whereVisible($value)
 * @mixin \Eloquent
 * @property string|null $pathName
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockCategories wherePathName($value)
 */
class StockCategories extends Model
{
    protected $casts = [
        'productFolder' => 'array',
    ];

    protected $fillable = [ 'name', 'uuid', 'origin', 'pathName','externalCode','productFolder','externalId','parent_id','visible','external_name'];

    public function products()
    {
        return $this->hasMany(StockProducts::class);
    }

}
