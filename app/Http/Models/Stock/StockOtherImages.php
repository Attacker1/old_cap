<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Http\Models\Stock\StockOtherImages
 *
 * @property int $id
 * @property string $uuid
 * @property string $url
 * @property string $processed_at
 * @method static \Illuminate\Database\Eloquent\Builder|StockOtherImages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOtherImages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOtherImages query()
 * @method static \Illuminate\Database\Eloquent\Builder|StockOtherImages whereProcessedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOtherImages whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StockOtherImages whereUrl($value)

 * @mixin \Eloquent
 */
class StockOtherImages extends Model
{

    protected $table = 'stock_other_images';

    protected $fillable = ['uuid', 'url', 'processed_at'];

    public $timestamps = false;

    public static $rules = array(
        'uuid' => 'required | string ',
        'url' => 'required | string',
        'processed_at' => 'nullable'
    );

    public function product() {

        return $this->belongsTo(StockProducts::class,"uuid","external_uuid");
    }

}
