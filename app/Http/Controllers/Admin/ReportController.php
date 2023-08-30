<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Exports\StylistsExport;
use App\Exports\AnketaExport;
use App\Exports\AnketaCouponsPRExport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class ReportController extends Controller
{
    /**
     * Отчет для з/п стилистам
     *
     * @return void
     */

    public function stylists_salary()
    {
        return Excel::download(new StylistsExport, 'stylists.xlsx');
    }

    /**
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */

    public function anketa()
    {
        return Excel::download(new AnketaExport, 'anketa.xlsx');
    }

    /**
     * Отчет по анкетам для PR
     */

    public function anketa_coupons_pr(Request $request) {
        if (request()->isMethod('post')) {

            $validator = Validator::make(request()->all(), [
                'date_report_from' => 'string|required|max:36',
                'date_report_to' => 'string|required|max:36',
                'coupon' => 'string|nullable|max:36'
            ], Message::messages());

            if($validator->fails()) {
                toastr()->error('ошибка ввода данных');
                return redirect()->back();
            }
            $date_report_from = new Carbon(request()->date_report_from.' 00:00:00');
            $date_report_to = new Carbon(request()->date_report_to.' 23:59:59');

            $new_date_report_from = new Carbon(request()->date_report_from.' 00:00:00');

            if($date_report_to > $new_date_report_from->addMonth()) {
                toastr()->error('введите период не больше 1 месяца');
                return redirect()->back();
            }

            if($date_report_from > $date_report_to) {
                toastr()->error('неверный период');
                return redirect()->back();
            }

            return Excel::download(new AnketaCouponsPRExport(
                [
                    'date_report_from' => $date_report_from,
                    'date_report_to' => $date_report_to,
                    'coupon' => request()->coupon
                ]), 'AnketaCouponsPR.xlsx');
        }

        return view('admin.analytics.pr-report', [
            'title' => 'Отчет для PR',
            'date_report_from' => Carbon::now()->startOfMonth()->format('d-m-Y'),
            'date_report_to' => Carbon::now()->format('d-m-Y')
        ]);

    }
}