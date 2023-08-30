<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockAttributes
 *
 * @property-read \App\Http\Models\Stock\StockProducts $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $uuid
 * @property string|null $external_id
 * @property string|null $name
 * @property string|null $type
 * @property string|null $description
 * @property int|null $required
 * @property array|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Models\Stock\StockAttributes whereValue($value)
 */
class StockAttributes extends Model
{

    protected $casts = [
        'value' => 'array'
    ];

    protected $fillable = ['uuid','name','type','description','external_id','required','value'];

    public function product() {

        return $this->belongsTo(StockProducts::class);
    }
}
