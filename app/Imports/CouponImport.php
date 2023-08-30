<?php

namespace App\Imports;

use App\Http\Models\Common\Coupon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class CouponImport implements ToModel, WithValidation
{
    /**
     * @param array $row
     *
     * @return Coupon
     */
    public function model(array $row)
    {
        return new Coupon([
            'type'  => $row[0],
            'name'  => $row[1],
            'price' => $row[2],
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required | string',
            '1' => 'required | string',
            '2' => 'required | numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '1.unique' => 'Повторное значение :attribute.',
        ];
    }
}
