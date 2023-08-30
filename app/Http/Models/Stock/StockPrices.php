<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockPrices
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $title
 * @property string $type
 * @property int|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Models\Stock\StockProducts $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockPrices whereValue($value)
 * @mixin \Eloquent
 */
class StockPrices extends Model
{
    public function product() {
        return $this->belongsTo(StockProducts::class);
    }
}
