<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Lead;

class AnketaCouponsPRExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public function __construct($params)
    {
        $this->date_report_from = $params['date_report_from'];
        $this->date_report_to = $params['date_report_to'];
        $this->coupon = $params['coupon'];
    }

    /**
     * @return Collection|\Illuminate\Support\Collection
     */

    public function collection()
    {
        $date_report_from = $this->date_report_from->toDateTimeString();
        $date_report_to = $this->date_report_to->toDateTimeString();
        $coupon = $this->coupon;


        $anketas = Questionnaire::with(['hasLeadLatest' => function($q) { $q->with('hasPayments'); }])
            ->whereHas('hasLeadLatest')
            ->where('created_at', '>=', $date_report_from)
            ->where('created_at', '<=', $date_report_to)
            ->get();


        $report = [];
        foreach($anketas as $anketa) {

            $lead = $anketa->hasLeadLatest->first();
            $payment = $lead->hasPayments->first();

            if(!empty($coupon)) {
                if(!isset($anketa->data["coupon"])) continue;
                if($anketa->data["coupon"] != $coupon) continue;
            }

            $report[] = [
                'id' => $anketa->id,
                'created_at' => $anketa->created_at->format('d-m-Y'),
                'updated_at' => $anketa->updated_at->format('d-m-Y'),
                'amo_id' => $lead->amo_lead_id ?? '',
                'lead_id' => $lead->uuid,
                'amount' => $payment ? $payment->amount : '',
                'coupon' => $anketa->data["coupon"] ?? '',
                'name' => $anketa->data[0] ?? '',
                'sname' => $anketa->data[79] ?? '',
                'email' => $anketa->data[14] ?? '',
                'phone' => $anketa->data[15] ?? '',
            ];
        }
        return new Collection($report);
    }

    /**
     * @return array|string[]
     */

    public function headings(): array
    {
        return [
            'id', 'Дата создания', 'Дата изменения', 'AMO ID', 'Сделка',  'Сумма', 'Купон', 'Имя', 'Фамилия', 'E-mail', 'Тел'
        ];
    }
}