<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Lead;

class AnketaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $anketas = Questionnaire::whereMonth('created_at', now()->month)->get();
        //$anketas = Questionnaire::all();
        $report = [];
        foreach($anketas as $anketa) {
            if(!$anketa->amo_id) {
                if($lead = Lead::where('anketa_uuid', $anketa->uuid)->first()) {
                    $anketa->amo_id = $lead->amo_lead_id;
                }
            }
            if(!is_array($anketa->data)) continue;
            if(count($anketa->data) == 0) continue;

            if(isset($anketa->data['anketa']['question'][71]['option'][$anketa->data['anketa']['question'][71]['answer']])) {
                $city = $anketa->data['anketa']['question'][71]['option'][$anketa->data['anketa']['question'][71]['answer']]['text'];
            }

            if(isset($anketa->data['anketa']['question'][69]['option'][$anketa->data['anketa']['question'][69]['answer']])) {
                $know_from = $anketa->data['anketa']['question'][69]['option'][$anketa->data['anketa']['question'][69]['answer']]['text'];
            }

            $report[] =[
                'data' => $anketa->created_at,
                'status' => $anketa->status,
                'amo_id' => $anketa->amo_id,
                'coupon' => $anketa->data['coupon'] ?? '',
                'amount' => $anketa->data['anketa']['amount'] ?? '',
                'email' => $anketa->data['anketa']['question'][14]['answer'],
                'phone' => $anketa->data['anketa']['question'][15]['answer'],
                'name' => $anketa->data['anketa']['question'][0]['answer'],
                'second_name' => $anketa->data['anketa']['question'][67]['answer'],
                'city' => $city ?? '',
                'know_from' => $know_from ?? ''
            ];
        }
        return new Collection($report);
    }

    public function headings(): array
    {
        return [
            'Дата', 'Статус', 'AMO ID', 'Купон', 'Сумма','Почта', 'Тел', 'Имя', 'Фамилия', 'Город', 'Откуда узнала'
        ];
    }
}