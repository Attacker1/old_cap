<?php

namespace App\Imports;

use App\Http\Models\Common\Coupon;
use App\Http\Models\Stock\StockOtherImages;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class StockOtherImagesImport implements ToModel, WithValidation
{
    /**
     * @param array $row
     *
     * @return StockOtherImages
     */
    public function model(array $row)
    {
        return new StockOtherImages([
            'uuid'  => $row[0],
            'url'  => $row[1],
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required | string',
            '1' => 'required | string',
        ];
    }
}
