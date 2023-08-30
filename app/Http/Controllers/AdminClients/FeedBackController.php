<?php

namespace App\Http\Controllers\AdminClients;

use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Common\Lead;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\ClientSettings;
use App\Http\Models\Common\Payments;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FeedBackController extends Controller
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
     * Форма обратной связи
     *
     * @param $lead_uuid
     * @return View
     */

    public function create($lead_uuid)
    {
        $client = Auth::guard('admin-clients')->user();

        if(!$lead = $client->leads->where('uuid', $lead_uuid)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $lead_uuid . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        if (FeedbackgeneralQuize::where('lead_id', $lead_uuid)->first()) {
            return self::redirect_router('message','Фидбек уже заполнен');
        }

        $amo_id = $lead->amo_lead_id;

        // Обновление продукции
        Common::updateNoterProductNames($lead);

        $note = Note::with(['products'])->where('order_id', $amo_id)->orderBy('id', 'desc')->first();
        if (!$note) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : ЗАПИСКА ПО АМО ID [' . $amo_id . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        $products = $note->products;

        if (count($products) == 0) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : ТОВАРЫ ПО ЗАПИСКЕ [' . $note->id . '] НЕ НАЙДЕНЫ']);
            return self::redirect_router('error');
        }

        $order_items = [];

        foreach ($products as $product) {
            $order_items[] = [
                'id' => $product->id,
                'img_url' => $product->attachments()->where('main', 1)->first()->url,
                'brand_name' => $product->brands->name,
                'product_name' => $product->name,
                'product_price' => $product->price,
            ];
        }

        return view('admin-clients.feedback.show-edit', [
            'title' => 'Капсула #'.$lead->client_num,
            'lead_id' => $lead_uuid,
            'order_items' => $order_items,
            'action' => 'create'
        ]);
    }

    /**
     * Сохранение формы Обратной связи
     *
     * @param Request $request
     * @return Redirector|RedirectResponse|View
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'lead_id' => 'string|required|uuid|max:36'
        ]);

        $client = Auth::guard('admin-clients')->user();

        if(!$lead = $client->leads->where('uuid', $request->lead_id)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : CДЕЛКА [' . $request->lead_id . '] НЕ НАЙДЕНА']);
            return self::redirect_router('error');
        }

        $products = Note::with(['products'])->where('order_id', $lead->amo_lead_id)->orderBy('id', 'desc')->first()->products;

        $validated = $request->validate([
            'comments.*'          => ['max:500'],
            'action_result'       => 'array|size:' . count($products),
            'size_result'         => 'nullable|array|max:' . count($products),
            'product_length'      => 'nullable|array|max:' . count($products),
            'stylist_note_wanted' => ['max:500'],
            'other_any_comments'  => ['max:500'],
            'mark_reason'         => ['max:500'],
            'mark_up_actions'     => ['max:500'],
            'repeat_date_own'     => ['max:500'],
            'delivery_comment'    => ['max:500'],
        ]);

        $requestData = $request->all();

        //если выбраны продукты для покупки
        $array_generalquize = [
            'uuid' => (string) (string) Str::uuid(),
            'client_uuid' => Auth::guard('admin-clients')->user()->uuid,
            'lead_id' => $lead->uuid,
            'personal_attitude' => $requestData['personal_attitude'] ?? null,
            'data' => ['clothing_external_look' => $requestData['clothing_external_look'] ?? null],
            'buy_more' => $requestData['buy_more'] ?? null,
            'stylist_note_wanted' => $requestData['stylist_note_wanted'] ?? null,
            'other_any_comments' => $requestData['other_any_comments'] ?? null,
            'new_stylist' => $requestData['new_stylist'] ?? null,
            'design' => $requestData['design'] ?? null,
            'recommended' => $requestData['recommended'] ?? null,
            'mark_reason' => $requestData['mark_reason'] ?? null,
            'mark_up_actions' => $requestData['mark_up_actions'] ?? null,
            'repeat_date' => $requestData['repeat_date'] ?? null,
            'repeat_date_own' => $requestData['repeat_date_own'] ?? null,
            'delivery_mark' => $requestData['delivery_mark'] ?? null,
            'delivery_comment' => $requestData['delivery_comment'] ?? null,
            'статус' => 'завершен'
        ];

        if(!$quizeGeneralModel = FeedbackgeneralQuize::where('lead_id', $lead->uuid)->first()) {
            $quizeGeneralModel = new FeedbackgeneralQuize();
        }

        $quizeGeneralModel->fill($array_generalquize);
        $quizeGeneralModel->save();

        $buy_products = [];
        foreach ($products as $product) {

            $product_sklad = Product::find($product->id);

            $arr_quize = [
                'client_uuid'              => Auth::guard('admin-clients')->user()->uuid,
                'product_id'               => $product->id,
                'order_id'                 => $lead->uuid,
                'feedbackgeneral_quize_id' => $quizeGeneralModel->id,
                'action_result'            => $requestData['action_result'][$product->id] ?? null,
                'size_result'              => $requestData['size_result'][$product->id] ?? null,
                'quality_opinion'          => $requestData['quality_opinion'][$product->id] ?? null,
                'price_opinion'            => $requestData['price_opinion'][$product->id] ?? null,
                'style_opinion'            => $requestData['style_opinion'][$product->id] ?? null,
                'comments'                 => $requestData['comments'][$product->id] ?? null,
                'price'                    => $product_sklad->price,
                'data'                     => ['product_length' => $requestData['product_length'][$product->id] ?? null]
            ];


            if($arr_quize['action_result'] == 'buy')
                $buy_products[] = $product_sklad->price;

            if(!$quizeModel = FeedbackQuize::where('feedbackgeneral_quize_id', $quizeGeneralModel->id)->where('product_id', $product->id)->first()) {
                $quizeModel = new FeedbackQuize();
            }

            $quizeModel->fill($arr_quize);
            $quizeModel->save();

        }//end foreach

        $lead= Lead::find($lead->uuid);
        $amo = new AmoCrm();
        Log::channel('amo')->error("ОС: АМО: $lead->amo_lead_id" . @json_encode(array_sum($buy_products) ?? 0));
        $amo->purchasedProducts($lead->amo_lead_id ?? 1, array_sum($buy_products) ?? 0 , $quizeGeneralModel->recommended, $quizeGeneralModel->repeat_date ?? false);


        if (array_sum($buy_products) > 0) {
            $lead->state_id = 12; //требуется оплата

            //смотрим какие скидки применять
            $client_settings = ClientSettings::where('name', 'discounts')->first();
            $client_discount =  $client_settings->params;
            $discount = []; $total = array_sum($buy_products);

            if($client_discount['minus_stylist_cost'] == 'yes') {
                //смотрим стоимость подбора
                $payment = Payments::where('lead_id', $lead->uuid)->where('pay_for', 'stylist')->first();
                $discount['stylist_cost'] = ($payment) ? $payment->amount : 0;
            }

            if(isset($client_discount['buy_all_products']) && count($products) == count($buy_products)) {
                if((int)$client_discount['buy_all_products'] > 0 ) {
                    $discount_perc = (int)$client_discount['buy_all_products'];
                    $discount['buy_all_products'] = bcdiv(( $discount_perc * ($total-$discount['stylist_cost']) / 100), 1, 2);
                }
            }

            if(isset($client_discount['3_4_5_goods']) && count($products) != count($buy_products)) {
                if((int)$client_discount['3_4_5_goods'] >0 && count($buy_products)>=3 && count($buy_products)<=5) {
                    $discount_perc = (int)$client_discount['3_4_5_goods'];
                    $discount['3_4_5_goods'] = bcdiv(( $discount_perc * ($total-$discount['stylist_cost']) / 100), 1, 2);
                }
            }

            if(isset($client_discount['3_4_5_goods']) && count($products) != count($buy_products)) {
                if((int)$client_discount['3_4_5_goods'] >0 && count($buy_products)>=3 && count($buy_products)<=5) {
                    $discount_perc = (int)$client_discount['3_4_5_goods'];
                    $discount['3_4_5_goods'] = bcdiv(( $discount_perc * ($total-$discount['stylist_cost']) / 100), 1, 2);
                }
            }

            $discount_total = array_sum($discount);
            if(($total-$discount_total) < 0 ) {
                $discount_total = $total-1;
                $total = 1;
            } else {
                $total = $total-$discount_total;
            }

            $lead->discount = $discount_total;
            $lead->total = $total;

            $lead->save();

            return redirect()->route('admin-clients.payment.show', $lead->uuid);
        }

        $lead->state_id = 14; //Сделка закрыта (заказ оплачен - клиент)
        $lead->save();

        return self::redirect_router('message','Спасибо, форма успешно отправлена');
    }


    /**
     * Редактирование формы Обратной связи
     *
     * @param Request $request
     * @return Redirector|RedirectResponse|View
     */
    function edit($fb_uuid) {

        $client = Auth::guard('admin-clients')->user();

        if(!$fb_main = $client->feedbacks->where('uuid', $fb_uuid)->first()) {
            Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Фидбек Клиент  [' . $client->uuid . '] : ФИДБЕК [' . $fb_uuid . '] НЕ НАЙДЕН']);
            return self::redirect_router('error');
        }

        if(!$fb_main = FeedbackgeneralQuize::where('uuid', $fb_uuid)->first())
            return redirect()->route('admin-clients.orders.list');

        if( $fb_main->client_uuid != $client->uuid)
            return redirect()->route('admin-clients.orders.list');

        $fbs = $fb_main->feedbackgQuize;
        foreach ($fbs as $fb) {
            $product = Product::find($fb->product_id);
            $order_items[] = [
                'id' => $product->id,
                'img_url' => $product->attachments()->where('main', 1)->first()->url,
                'brand_name' => $product->brands->name,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'fb' => $fb
            ];

        }

        return view('admin-clients.feedback.show-edit', [
            'title' => 'Капсула #' . $fb_main->lead->client_num,
            'lead_id' => $fb_main->lead_id,
            'order_items' => $order_items,
            'fb_main' => $fb_main,
            'action' => 'edit'
        ]);
    }

}
