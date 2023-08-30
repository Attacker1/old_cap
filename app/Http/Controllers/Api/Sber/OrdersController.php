<?php

namespace App\Http\Controllers\Api\Sber;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Common\Coupon;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use App\Services\FeedbackAdapter;
use App\Services\LeadService;
use Bnb\Laravel\Attachments\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;

class OrdersController extends Controller
{
    private int $default_stylist_price = 990;
    private $test_note_id = 30221469;
    protected $model;
    protected $description = '';

    //TODO:: Переместить в настраиваемый модуль как у клиентов
    protected $states = [
        0 => 'new',  1 => 'new',
        2 => 'processing',   3 => 'processing',  4 => 'processing',  5 => 'processing', 6 => 'processing',
        15 => 'processing',  16 => 'processing',  17 => 'processing', 18 => 'processing', 19 => 'processing',
        7 => 'sent', 8 => 'sent', 9 => 'sent', 10 => 'sent', 11 => 'sent',
        12 => 'Payment required',
        13 => 'completed', 14 => 'completed',
        20 => 'cancelled',  21 => 'cancelled',  22 => 'cancelled', 23 => 'cancelled', 24, 25 => 'cancelled'
    ];

    public function __construct(Lead $model)
    {
        $this->model = $model;
    }

    /**
     * Все заказы пользователя
     * @param $user_id
     * @return JsonResponse
     */
    public function index($user_id)
    {
        $client = Common::getUser($user_id);

        if(empty($client->error)) {
            if (!$orders = $this->model->select('uuid as order_id','state_id')->whereClientId($client->model->uuid)
                ->get())
                $this->description = "Заказы не найдены";

        }
        else
            $this->description = $client->error;

        foreach ($orders as $order)
            $orders_array[] = ["order_id" => $order->order_id, "status" => $this->states[$order->state_id] ?? 'new'];


        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => $this->description ?? '',
            'items' => $orders_array ?? [],
        ])->setStatusCode(Response::HTTP_OK);
    }

    public function create($user_id)
    {


    }

    private function getAnketa($client_id){

        if($anketa = Questionnaire::where('client_uuid', $client_id)->first())
            return $anketa->uuid;

        return false;

    }

    /** POST
     * @param $user_id
     * @return JsonResponse
     */
    public function store($user_id)
    {

        $validator = Validator::make(\request()->all(), ['promo_code' => 'nullable | string | max:100',], Message::messages());
        if ($validator->fails())
            return \response()->json(['status' => 'error','description' => $validator->errors()],Response::HTTP_BAD_REQUEST);

        if (!empty(\request()->input('promo_code')))
            $coupon = Coupon::whereName(\request()->input('promo_code'))->first();

        $service = new LeadService('sber');
        $service->create($user_id,['anketa_uuid' => self::getAnketa($user_id)])
            ->amoAddLead([
                'coupon' => \request()->input('promo_code'),
                'source' => 'SBERBANK'
        ]);

        $lead = $service->get();

        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => $this->description ?? '',
            'order_id' => @$lead->uuid ?? false,
            'price' => !empty($coupon->price) ? $coupon->price : $this->default_stylist_price
        ],Response::HTTP_OK);
    }

    /**
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function show($user_id, $id)
    {
        $client = Common::getUser($user_id);
        if (empty($client->error)) {

            if (!empty($lead = Lead::find($id))) {
                if ($note = Note::with('products')->whereOrderId($lead->amo_lead_id)->first()){
                    foreach ($note->products()->get() as $k=>$v)
                        $products[] = [
                            "item_id" => $v->id,
                            "title" => $v->name,
                            "description" => '',
                            "price" => $v->price,
                            "image" => $v->attachments()->first()->url
                        ];

                }
                else
                    $this->description = 'Нет товаров в заказе';

            } else
                $this->description = 'Первичный заказ не найден';
        }
        else
            $this->description = 'Клиент не найден';


        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => @$this->description ?? '',
            'items' => @$products ?? [],
        ])
            ->setStatusCode(Response::HTTP_OK);
    }

    // TODO: Расширить Payment На того кто оплатил? Сбер или сами юзеры через сайт
    public function edit($user_id, $id)
    {
        $client = Common::getUser($user_id);
        @Log::channel('sber_clients')->info('Клиент:'  . $user_id. ' ОПЛАТА за Стилиста:' . json_encode(\request()->all()));

        $validator = Validator::make(\request()->all(), [
            'invoice_id' => 'required |integer',
            'amount' => 'required | integer',
        ], Message::messages());

        if ($validator->fails()) return \response()->json(['status' => 'error','description' => $validator->errors()],400);

        if (empty($client->error)) {

            if (!empty($lead = Lead::find($id))) {
                $amount = \request()->input('amount');
                $save_amount =  $amount > 0 ? $amount / 100 : $amount;
                Payments::create([
                    'lead_id' => $id,
                    'pay_for' => 'stylist',
                    'source' => 'sber',
                    'paid_at' => now(),
                    'payment_id' => \request()->input('invoice_id'),
                    'amount' => $save_amount,
                    'payload' => json_encode(\request()->all()),
                ]);
                $lead->state_id = 2;
                $lead->save();
            } else
                $this->description = 'Заказ не найден';
        }
        else
            $this->description = 'Клиент не найден';


        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => @$this->description ?? '',
        ])
            ->setStatusCode(Response::HTTP_OK);

    }

    /**
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function update($user_id, $id)
    {
        $client = Common::getUser($user_id);
        @Log::channel('sber_clients')->info('Клиент:'  . $user_id. ' ОПЛАТА Products/Feedback:' . json_encode(\request()->all()));

        if (empty($client->error)) {

            if (!empty($lead = Lead::find($id))) {
                $payment = Payments::create([
                    'lead_id' => $id,
                    'paid_at' => now(),
                    'source' => 'sber',
                    'pay_for' => 'products',
                    'payload' => json_encode(\request()->all()),
                    'payment_id' => @\request()->input('payment')['invoice_id'],
                    'amount' => @\request()->input('payment')['amount'] ?? 0 ,
                ]);

                if ($payment)
                    self::feedback($payment->id);

            } else $this->description = 'Заказ не найден';
        }
        else $this->description = 'Клиент не найден';

        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => @$this->description ?? '',
        ])->setStatusCode(Response::HTTP_OK);
    }

    // Адаптер под фидбек (временно криво)
    private function feedback($payment_id){

        try {
            $replace = [
                0 => "Размер — ",
                1 => "Качество — ",
                2 => "Цена — ",
                3 => "Стиль — ",
                4 => "Комментарий —",
            ];

            $input = Payments::find($payment_id);
            $request = json_decode($input->payload, 1);
            if (!empty($request['items'])) {
                foreach ($request['items'] as $item) {
                    $data['action_result'][$item['item_id']] = $item['status'] != 'returned' ? "buy" : 'return';

                    $id = $item['item_id'];
                    $raw = preg_split('/(\s*,*\s*)*,+(\s*,*\s*)*/', $item['review']);

                    $size_raw = str_replace($replace[0], "", $raw[0]);
                    switch ($size_raw) {
                        case "Как раз":
                            $size = 'ok';
                            break;
                        case "Мал":
                            $size = 'small';
                            break;
                        default:
                            $size = 'big';
                            break;
                    }

                    $data['size_result'][$id] = $size;
                    $data['quality_opinion'][$id] = trim(str_replace($replace[1], "", $raw[1]));
                    $data['price_opinion'][$id] = trim(str_replace($replace[2], "", $raw[2]));
                    $data['style_opinion'][$id] = trim(str_replace($replace[3], "", $raw[3]));
                    $data['comments'][$id] = trim(str_replace($replace[4], "", $raw[4]));
                }
            }
            if ($data) {
                if ($lead = Lead::whereUuid($input->lead_id)->first()) {
                    $feedback = new FeedbackAdapter();
                    $feedback_uuid = $feedback->create($lead, $data, false);
                }
            }

            Log::channel('sber_clients')->info('Обратная связь: ' . @$feedback_uuid);
            return true;
        }
        catch (\Exception $e){
            Log::channel('sber_clients')->alert("Ошибка ОС по оплате $payment_id: ". @$e->getMessage());
        }
    }

    /**
     * @param $user_id
     * @param $id
     * @return JsonResponse
     */
    public function destroy($user_id, $id)
    {
        $client = Common::getUser($user_id);

        if (empty($client->error)) {

            if (!empty($lead = Lead::find($id))) {
                $lead->delete();
            } else
                $this->description = 'Первичный заказ не найден';
        }
        else
            $this->description = 'Клиент не найден';


        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => @$this->description ?? '',
        ])
            ->setStatusCode(Response::HTTP_OK);
    }

}
