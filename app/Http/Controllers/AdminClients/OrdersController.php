<?php

namespace App\Http\Controllers\AdminClients;

use App\Http\Models\Common\ClientStatus;
use App\Http\Models\Common\Lead;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $client = Auth::guard('admin-clients')->user();

        //для файсбук пиксель Purchase
        if($request->session()->get('pay_status') === true) {
            toastr()->success('Оплата прошла успешно!');
            $fb_amount = $request->session()->get('pay_amount');
        }

        $leads_null_client_num = $client->leads->where('client_num','==', null)->where('state_id','>', 1)->where('state_id','<', 11);
        foreach ($leads_null_client_num as $lead_null_client_num) {
            $lead_null_client_num->client_num = (int) Lead::where('client_id',$lead_null_client_num->client_id)->max('client_num') + 1;
            $lead_null_client_num->save();
        }

        $leads = $client->leads->where('client_num','!=', null)->sortByDesc('client_num');

        if(session('error')) toastr()->info(session('error'),'', ['positionClass'=> 'toast-bottom-right']);
        if(session('message')) toastr()->success(session('message'),'', ['positionClass'=> 'toast-bottom-right']);

        $fb_oc =  $client->feedbacks;
        $months = [
            '01' => 'Января',
            '02' => 'Февраля',
            '03' => 'Марта',
            '04' => 'Апреля',
            '05' => 'Мая',
            '06' => 'Июня',
            '07' => 'Июля',
            '08' => 'Августа',
            '09' => 'Сентября',
            '10' => 'Октября',
            '11' => 'Ноября',
            '12' => 'Декабря',
        ];

        /*$lead = Lead::find('4500b68a-4d10-48b2-94d3-f65d9b4e5def');
        $lead->state_id = 9;
        $lead->save();*///TODO удалить, отладка
        $gmt_time = gmdate("Y/m/d H:i:s");
        $carbone_gmt_time = Carbon::parse($gmt_time);

        foreach ($leads as $lead) {
            $lead->client_state_id = ClientStatus::getClientState($lead->state_id);

            //формирование даты
            $date = Carbon::parse($lead->created_at);
            $day = $date->format('d');
            $month = $months[$date->format('m')];
            $year = $date->format('Y');
            $lead->date = $day . ' ' . $month . ' ' . $year;

            //отображение фидбек
            if($lead->state_id < 9) continue;

            //статус ОС отправлена
            if($lead->state_id == 10) {
                if (!empty($lead->total) || !empty($lead->discount)) {
                    Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Заказы Показ фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $lead->uuid . '] ПОЛЯ total и discount уже заполнены']);
                    continue;
                }
                /*$date_update = Carbon::parse($lead->updated_at);
                $time_diff = $date_update->diff($carbone_gmt_time);
                if(!$time_diff->h && !$time_diff->d) continue;*/
            }

            //OC отправлена но не оплачена
            if($lead->state_id > 10 && $lead->state_id <= 12) {
                $fb_item = $fb_oc->where('lead_id', $lead->uuid)->first();

                if($fb_item) {
                    $lead->feedbeck = true;
                    $lead->feedbeck_link = route('admin-clients.payment.show', $lead->uuid);
                    $lead->feedbeck_text = "Оплатить";
                }

                else Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Список заказов Клиент  [' . $client->uuid . '] : Статус больше или равен "ОС отправлена" сделка  [' . $lead->uuid . '] ФИДБЕК НЕ НАЙДЕН']);
                continue;
            }

            if($lead->state_id > 12) continue;
            $lead->feedbeck = true;
            $lead->feedbeck_link = route('admin-clients.feedback.create', $lead->uuid);
            $lead->feedbeck_text = "Оценить и оплатить капсулу";

        }//endforeach

        return view('admin-clients.orders-list', [
            'title'=>'Мои заказы',
            'leads' => $leads,
            'fb_amount' => $fb_amount ?? ''
        ]);
    }
    public function repeat_order() {
        /*return view('admin-clients.repeat-order', [
            'title'=>'Повторный заказ'
        ]);*/

        $client = Auth::guard('admin-clients')->user();
        if(!$lead = Lead::where('client_id', $client->uuid)->whereNotNull('anketa_uuid')->latest()->first()) {
            config('config.ANKETA_URL');
        }
        return view('admin-clients.anketa.reanketa', [
            'title'=>'Повторный заказ'
        ]);
    }


}