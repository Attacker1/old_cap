<?php

namespace App\Services;


use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\ClientSettings;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class FeedbackAdapter
{
    private $products;
    private Lead $lead;
    private Client $client;
    private FeedbackgeneralQuize $feedback;
    private float $sum = 0; // Сумма купленных вещей
    private array $buy_products = [];

    public function __construct(){}

    private function redirect_router($status, $message = false) {
        $route = 'admin-clients.orders.list';
        switch ($status) {
            case 'message': return redirect()->route($route)->with('message', $message);
            case 'error' : return redirect()->route($route)->with('error', 'Произошла ошибка, обратитесь к администратору');
        }
        return false;
    }

    private function validate($params){

        $validator = Validator::make($params, [
            'comments.*'          => ['max:500'],
            'action_result'       => 'array|size:' . count($this->products),
            'size_result'         => 'nullable|array|max:' . count($this->products),
            'product_length'      => 'nullable|array|max:' . count($this->products),
            'stylist_note_wanted' => ['max:500'],
            'other_any_comments'  => ['max:500'],
            'mark_reason'         => ['max:500'],
            'mark_up_actions'     => ['max:500'],
            'repeat_date_own'     => ['max:500'],
            'delivery_comment'    => ['max:500'],
        ]);

        return $params;

        if ($validator->fails())
            return false;

        return $validator->validated();
    }

    // $input_data должен содержать [action_result] = buy при копупке
    public function create(Lead $lead, $input_data = [], $need_routing = true){

        $this->lead = $lead;
        $this->client = Client::where('uuid',$lead->client_id)->first();
        $this->products = self::getProducts($lead->amo_lead_id);

        $input = self::validate($input_data);
        $data = self::prepare($input);

        //dd($data,$this->products);

        $this->feedback = new FeedbackgeneralQuize();
        $this->feedback->fill($data);
        $this->feedback->save();

        $shoping = self::addProducts($input);
        self::updateAmo();

        $response = ($shoping)
            ? self::leadPaymentSave()
            : self::leadClose(); // сделка закрыта

        if ($need_routing)
            return $response;

        return $this->feedback->uuid;

    }


    private function leadPaymentSave(){

        //смотрим какие скидки применять
        $client_settings = ClientSettings::where('name', 'discounts')->first();
        $client_discount =  $client_settings->params;
        $discount = []; $total = $this->sum;

        if($client_discount['minus_stylist_cost'] == 'yes') {
            //смотрим стоимость подбора
            $payment = Payments::where('lead_id', $this->lead->uuid)->where('pay_for', 'stylist')->first();
            $discount['stylist_cost'] = ($payment) ? $payment->amount : 0;
        }

        if((int)$client_discount['buy_all_products'] > 0  && count($this->products) == count($this->buy_products)) {
            $discount_perc = (int)$client_discount['buy_all_products'];
            $discount['buy_all_products'] = bcdiv(( $discount_perc * $total / 100), 1, 2);
        }

        $discount_total = array_sum($discount);
        if(($total-$discount_total) < 0 ) {
            $discount_total = $total-1;
            $total = 1;
        } else
            $total = $total-$discount_total;


        self::leadState(12);
        self::leadSumSave($total,$discount_total);


        return redirect()->route('admin-clients.payment.show', $this->lead->uuid);
    }

    private function addProducts($input_data){

        if (!empty($this->products)) {
            foreach ($this->products as $product) {

                $product_sklad = Product::find($product->id);
                $arr_quize = [
                    'client_uuid' => $this->client->uuid,
                    'product_id' => $product->id,
                    'order_id' => $this->lead->uuid,
                    'feedbackgeneral_quize_id' => $this->feedback->id,
                    'action_result' => $input_data['action_result'][$product->id] ?? 'return',
                    'size_result' => $input_data['size_result'][$product->id] ?? null,
                    'quality_opinion' => $input_data['quality_opinion'][$product->id] ?? null,
                    'price_opinion' => $input_data['price_opinion'][$product->id] ?? null,
                    'style_opinion' => $input_data['style_opinion'][$product->id] ?? null,
                    'comments' => $input_data['comments'][$product->id] ?? null,
                    'price' => $product_sklad->price,
                    'data' => ['product_length' => $input_data['product_length'][$product->id] ?? null]
                ];

                if ($arr_quize['action_result'] == 'buy')
                    $this->buy_products[] = $product_sklad->price;

                $quizeModel = new FeedbackQuize();
                $quizeModel->fill($arr_quize);
                $quizeModel->save();

            }
        }
        $this->sum = array_sum($this->buy_products);
        return $this->sum > 0 ? true : false;

    }

    // TODO Позже Перенести в Сервис по сделке
    private function leadState($state_id){
        $this->lead->state_id = $state_id;
        $this->lead->save();
        return true;
    }

    // TODO Позже Перенести в Сервис по сделке
    private function leadClose(){
        self::leadState(14);
        return self::redirect_router('message','Спасибо, форма успешно отправлена');
    }

    // TODO Позже Перенести в Сервис по сделке
    private function leadSumSave($total, $discount_total){
        $this->lead->discount = $discount_total;
        $this->lead->total = $total;
        $this->lead->save();
    }

    private function updateAmo(){
        Log::channel('amo')->error("ОС: АМО: $this->lead->amo_lead_id" . @json_encode($this->sum));
        $amo = new AmoCrm();
        $amo->purchasedProducts($lead->amo_lead_id ?? 1, $this->sum , $this->feedback->recommended, $this->feedback->repeat_date ?? false);
    }

    private function prepare($input_data){

        $requestData = $input_data;

        //если выбраны продукты для покупки
        $array_generalquize = [
            'uuid' => (string) (string) Str::uuid(),
            'client_uuid' => $this->client->uuid,
            'lead_id' => $this->lead->uuid,
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

        return $array_generalquize;

    }



    private function getProducts(int $amo_lead_id){

        $products = false;
        if ($note = Note::with(['products'])->where('order_id', $amo_lead_id)->orderBy('id', 'desc')->first())
            if (!empty($note->products))
                return $products = $note->products;

        return $products;

    }
}