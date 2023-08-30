<?php

namespace App\Imports;

use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Coupon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProductSizeImport implements ToModel, WithValidation
{
    use Importable;
    /**
     * @param array $row
     *
     * @return Coupon
     */
    public function model(array $row)
    {
        return Product::where('external_id', $row[0])->update(['size' => $row[1]]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required | string | max:36',
            '1' => 'required | string | max:20',
        ];
    }

    public function customValidationMessages()
    {

    }
}
