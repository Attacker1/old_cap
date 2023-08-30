<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockSupplier
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $title
 * @property string|null $description
 * @property string|null $slug
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Models\Stock\StockProducts[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockSupplier whereUuid($value)
 * @mixin \Eloquent
 */
class StockSupplier extends Model
{
    public function products() {
        return $this->hasMany(StockProducts::class);
    }
}
