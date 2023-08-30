<?php

namespace App\Http\Models\Stock;

use Illuminate\Database\Eloquent\Model;

class StockProductAttributesUnique extends Model
{

    protected $table = 'stock_product_attributes_unique';

    public $timestamps = false;

    protected $casts = [
        'value' => 'array',
    ];

    protected $fillable = [ 'attribute_id','value' ];

}
