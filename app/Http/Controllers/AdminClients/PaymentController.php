<?php

namespace App\Http\Controllers\AdminClients;

use App\Http\Controllers\Classes\DoliApi;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\ClientSettings;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\BonusTransactions;
use App\Http\Models\Common\Payments;

use Illuminate\Support\Facades\Log;


class PaymentController extends Controller
{
    private function redirect_router($status, $message = false) {
        $route = 'admin-clients.orders.list';
        switch ($status) {
            case 'message': return redirect()->route($route)->with('message', $message);
            case 'error' : return redirect()->route($route)->with('error', 'Произошла ошибка, обратитесь к администратору');
        }
        return false;
    }

    /**
     * Оплата вещи
     *
     * @param Request $request
     * @return Redirector|RedirectResponse|View
     */

    public function show($lead_uuid)
    {
        $client = Auth::guard('admin-clients')->user();

        if(!$lead = $client->leads->where('uuid', $lead_uuid)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        $payment_sum = $lead->total;

        //бонусы
        $points = 0;
        if(auth()->guard('admin-clients')->user()->bonuses) {
            $points = auth()->guard('admin-clients')->user()->bonuses->points;
        }

        //смотрим какие скидки применять
        $client_settings = ClientSettings::where('name', 'discounts')->first();
        $client_discount =  $client_settings->params;

        if($points && $client_discount['bonuses'] == "yes" && $payment_sum > 1) {
            $points = (string) $points;

            $last_number = $points[strlen($points)-1];
            $bonuses_text = '';
            if(in_array($last_number, [0, 5, 6, 7, 8, 9])) $bonuses_text = 'бонусов';
            if(in_array($last_number, [1])) $bonuses_text = 'бонус';
            if(in_array($last_number, [2, 3, 4])) $bonuses_text = 'бонуса';
            $bonuses_text = $points. ' ' . $bonuses_text;
        }

        $feedback_general = FeedbackgeneralQuize::where('lead_id', $lead_uuid)->latest()->first();

        return view('admin-clients.feedback.payment', [
            'title'=>'Капсула #' .$lead->client_num. ' Оплата',
            'payment_sum' => $payment_sum,
            'lead_id' =>$lead_uuid,
            'feedback_uuid' => $feedback_general->uuid,
            'bonuses_text'=> $bonuses_text ?? ''
        ]);
    }

    public function bonuses_store(Request $request)
    {

        $validated = $request->validate([
            'lead_id' => 'required|uuid|exists:leads,uuid',
            'points'  => 'integer| nullable',
        ], [
            'lead_id.uuid' => 'произошла неизвестная ошибка, обратитесь к администратору',
            'lead_id.required' => 'произошла неизвестная ошибка, обратитесь к администратору',
            'lead_id.exists' => 'произошла неизвестная ошибка, обратитесь к администратору',
            'points.integer'=> 'введите целое число',
            'points.required' => 'заполните поле',
        ]);

        $client = Auth::guard('admin-clients')->user();

        if(!$lead = $client->leads->where('uuid', $request->lead_id)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $request->lead_id . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        if(!$request->points) return redirect()->route('admin-clients.payment.show-method', $request->lead_id);
        $points_exists = auth()->guard('admin-clients')->user()->bonuses->points;
        $validated = $request->validate(
            [ 'points'  => 'integer| max:' . $points_exists], ['points.max' => 'введите не более ' . $points_exists]);

        $lead = Lead::find($request->lead_id);

        if(($lead->total - $request->points) <= 0) {
            $points = min($points_exists, $lead->total - 1);
        } else $points = $request->points;

        $bonusTransactions= BonusTransactions::create([
            'client_id' => auth()->guard('admin-clients')->user()->uuid,
            'lead_uuid' => $lead->uuid,
            'points' => $points,
            'type' => 'sub',
            'paid_by' => 'clothes'
        ]);

        $lead->total -= $points;
        $lead->discount += $points;

        $lead->save();

        $bonuses= auth()->guard('admin-clients')->user()->bonuses;
        $bonuses->points -=  $points;
        $bonuses->save();

        $payment_sum = $lead->total;

        //бонусы
        $points = 0;
        if(auth()->guard('admin-clients')->user()->bonuses) {
            $points = auth()->guard('admin-clients')->user()->bonuses->points;
        }

        //смотрим какие скидки применять
        $client_settings = ClientSettings::where('name', 'discounts')->first();
        $client_discount =  $client_settings->params;

        if($points && $client_discount['bonuses'] == "yes" && $payment_sum > 1) {
            $points = (string) $points;

            $last_number = $points[strlen($points)-1];
            $bonuses_text = '';
            if(in_array($last_number, [0, 5, 6, 7, 8, 9])) $bonuses_text = 'бонусов';
            if(in_array($last_number, [1])) $bonuses_text = 'бонус';
            if(in_array($last_number, [2, 3, 4])) $bonuses_text = 'бонуса';
            $bonuses_text = $points. ' ' . $bonuses_text;

        }

        return redirect()->route('admin-clients.payment.show-method', $lead->uuid);
    }

    public function show_method(Request $request, $lead_uuid){

        $client = Auth::guard('admin-clients')->user();

        if(!$lead = $client->leads->where('uuid', $lead_uuid)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        $feedback_general = FeedbackgeneralQuize::where('lead_id', $lead_uuid)->latest()->first();

        if($request->isMethod('post')) {
            switch ($request->input('pay_method')) {
                case 'doli':
                    return redirect()->route('admin-clients.payment.method-doli', $feedback_general->uuid);
                    break;
                case'card':
                    return redirect()->route('admin-clients.payment.send', $lead_uuid);
                    break;
            }
        }

        if($payment_sum = $lead->total > 1) {


            if(!$feedbacks = $feedback_general->feedbackgQuize->where('action_result','buy')) {
                Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Оплата ЛК Клиента  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] нет выбранных продуктов на оплату']);
                return self::redirect_router('error');
            }

            $products = [];
            $total = 0;
            foreach ($feedbacks as $feedback) {

                $name = (!empty($feedback->product->amo_name)) ? $feedback->product->amo_name : $feedback->product->name . ' ' . $feedback->product->brands->name;

                $products[] = [
                    'feedback_id' => $feedback->id,
                    'price' => $feedback->price,
                    'name' => $name
                ];

                $total+= $feedback->price;
            }

            if($total == 0) {
                Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Оплата ЛК Клиента  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] сумма всех выбранных продуктов = 0']);
                return self::redirect_router('error');
            }

            //считаем чек
            $discount = $lead->discount;

            if($discount > 0 && $lead->total > count($products)) {
                $discount_perc = bcdiv($discount * 100 / ($discount + $lead->total), 1, 0);

                $discount_remains = $discount;
                for ($i = 0; $i < count($products); $i++) {
                    $discount_item =  bcdiv($discount_perc * $products[$i]['price']/100, 1, 2);

                    if(($products[$i]['price'] - $discount_item) > 0) {
                        $products[$i]['price'] -= $discount_item;
                        $discount_remains -= $discount_item;
                    }
                }

                //вычитаем остаток от скидки
                if($discount_remains > 0) {

                    for ($i = 0; $i < count($products); $i++) {

                        if(($products[$i]['price'] - $discount_remains) > 0) {
                            $products[$i]['price'] -= $discount_remains;
                            break;
                        }
                    }
                }
            } else if($discount > 0) {
                $discount_remains = $total -$discount;
                for ($i = 0; $i < count($products); $i++) {
                    if($discount_remains > 0 )
                        $products[$i]['price'] = 1;
                    else $products[$i]['price'] = 0;
                    $discount_remains --;
                }
            }

            //записываем в фидбек цену со скидкой
            foreach ($products as $product) {
                $feedback = FeedbackQuize::find($product['feedback_id']);
                $feedback->discount_price = $product['price'];
                $feedback->save();
            }
        }

        return view('admin-clients.feedback.payment-method', [
            'title'=>'Капсула #' .$lead->client_num. ' Оплата',
            'feedback_uuid' => $feedback_general->uuid,
            'lead_uuid' => $lead_uuid
        ]);
    }

    public function send($lead_uuid)
    {
        $client = Auth::guard('admin-clients')->user();

        if(!$lead = $client->leads->where('uuid', $lead_uuid)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        //выбранные продукты
        $feedback_general = FeedbackgeneralQuize::where('lead_id', $lead_uuid)->latest()->first();


        if(!$feedbacks = $feedback_general->feedbackgQuize->where('action_result','buy')) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Оплата ЛК Клиента  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] нет выбранных продуктов на оплату']);
            return self::redirect_router('error');
        }

        $products = [];

        foreach ($feedbacks as $feedback) {

            $name = (!empty($feedback->product->amo_name)) ? $feedback->product->amo_name : $feedback->product->name . ' ' . $feedback->product->brands->name;

            $products[] = [
                'feedback_id' => $feedback->id,
                'price' => $feedback->discount_price,
                'name' => $name
            ];

        }

        return view('admin-clients.feedback.payform', [
            'fbuuid'=> 'fb' . (string)$feedback_general->uuid,
            'lead' => $lead,
            'products' => $products
        ]);
    }

    public function method_doli($feedback_uuid) {

        $client = Auth::guard('admin-clients')->user();

        if(!$fb_main = $client->feedbacks->where('uuid', $feedback_uuid)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : ФИДБЕК [' . $feedback_uuid . '] НЕ НАЙДЕН']);
            return self::redirect_router('error');
        }

        $fb_main = FeedbackgeneralQuize::where('uuid', $feedback_uuid)->first();
        $api = new DoliApi();
        if($response = $api->createOrder($fb_main)) {
            return redirect($response);
        }

        // Страница ошибки обработки запроса или предыдущая
        toastr()->error('Сервис временно недоступен, попробуйте еще раз!');
        return redirect()->back();
    }
}