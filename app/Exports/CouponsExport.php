<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CouponsExport implements FromCollection, ShouldAutoSize, WithHeadings
{

    public function collection()
    {
        $report = DB::select(' SELECT name,type,price FROM coupons');

        return new Collection($report);
    }

    public function headings(): array
    {
        return [
            'Наименование', 'Тип', 'Величина'
        ];
    }
}