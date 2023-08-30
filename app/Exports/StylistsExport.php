<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StylistsExport implements FromCollection, ShouldAutoSize, WithHeadings
{

    public function collection()
    {
        $report = DB::select('
            SELECT l.amo_lead_id, u.name AS stylist ,CONCAT(IFNULL(c.name, \'\'), \' \', IFNULL(c.second_name , \'\')) AS CLIENT, lr.name AS status , l.created_at, l.updated_at
            FROM leads AS l 
            INNER JOIN users `u` ON l.stylist_id = u.id
            LEFT JOIN clients `c` ON l.client_id = c.uuid
            LEFT JOIN leads_refs `lr` ON l.state_id = lr.id
            WHERE l.stylist_id IS NOT NULL ORDER BY stylist_id');

        return new Collection($report);
    }

    public function headings(): array
    {
        return [
            'amo_id', 'Стилист', 'Клиент', 'Статус', 'Дата создания', 'Дата изменения'
        ];
    }
}