<?php

namespace App\Http\Controllers\Common;

use App\Http\Classes\DataConversion\PaymentSberbank;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Catalog\Tags;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use App\Http\Models\Common\Payother;
use App\Http\Requests\Common\PaymentsFromRequest;
use Carbon\Carbon;
use App\Http\Controllers\Classes\AmoCrm;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\BonusTransactions;

/**
 * Контроллел по отображению оплаты клиентов
 * Class LeadController
 * @package App\Http\Controllers\Common
 */
class PaymentsController extends Controller
{
    /**
     * Список оплат
     *
     * @return void
     * @throws \Exception
     */
    public function list()
    {
        if(request()->ajax()){

            $payments = Payments::with('leads');
            if (!empty(\request()->input('client')))
                $payments = $payments->wherehas('leads',function($q){
                   $q->whereHas('clients',function($q2){
                       $q2->where('name','like',"%" . trim(\request()->input('client')) . "%")
                           ->orWhere('phone','like',"%" . trim(\request()->input('client')) . "%");
                   });
                });

            $dt = DataTables::eloquent($payments);

            $dt->editColumn('created_at', function($data)
            {
                return Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at)->format('d.m.Y / H:i');
            });

            $dt->editColumn('client', function($data)
            {
                return @$data->leads->clients->name .' '.@$data->leads->clients->second_name ;
            });

            $dt->editColumn('pay_for', function($data)
            {
                return @$data->pay_for;
            });

            $dt->addColumn('order', function($data)
            {
                if (!empty($data->payload) && $json1 = json_decode($data->payload) )
                    if (is_string($json1))
                        return (json_decode($json1))->OrderId;

                return '';
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';

                //$buttons .='<a href = "'. route('payments.edit',$data->id) .'" title = "Редактирование оплаты'. $data->id .'" ><button class="btn btn-dark btn-sm px-1 ml-1" ><i class="fa fa-pencil-square-o" aria-hidden= "true" ></i ></button ></a >';

                if (auth()->guard('admin')->user()->hasPermission('destroy-payments'))
                    $buttons .='<a data-route-destroy = "' . route('payments.destroy', @$data->id) .'" href = "'. route('payments.destroy',$data->id) .'"  class="ml-3 modal-payment-delete" title = "Удаление оплаты '. $data->id .'" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.payments.index', [
            'title' => 'Оплаты'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PaymentsFromRequest $request
     * @return void
     */
    public function store(PaymentsFromRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        $item = Payments::find($id);

        return view('admin.payments.edit',[
            'title' => 'Редактирование: '. @$item->id,
            'data' => $item,
        ]);
    }

    /**
     * @param PaymentsFromRequest $request
     * @param int $id
     */
    public function update(PaymentsFromRequest $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id)
    {
        $item = Payments::where('id',$id)->delete();
        toastr()->info('Оплата удалена');
        return redirect()->route('payments.list');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function webhook()
    {

        Storage::disk('local')->append('logs/payments.txt', now()->format("d/m/Y H:i") .': '. json_encode(\request()->all()));
        return \response()->json([true],200);
    }


    public function webhookTest()
    {

        $request = $_SERVER['REQUEST_URI'];

        $get = $_SERVER['QUERY_STRING'];
        $input = file_get_contents('php://input');
        $post = print_r($_POST, true);
        $headers = apache_request_headers();
        $log = date('d.m.Y H:i:s')."\n".$_SERVER['REQUEST_METHOD']." URL:".$request."\n-------------------\nGET: ".$get."\nPOST: ".$post."\nINPUT: ".$input."\n".print_r($headers, true)."\n======================\n";
        Storage::disk('local')->append('logs/payments_'.date("Y_m_d").'_Test.txt', $log);

        if ( \request()->input('Status') == 'CONFIRMED' && \request()->input('Success') == true) {
            $pay_type = substr(\request()->input('OrderId'), 0, 2);

            if ($payment = Payments::where('payment_id',\request()->input('PaymentId'))->first()){
                Log::channel('payments')->warning('Повторный webhook по PaymentId' . @\request()->input('PaymentId'));
                echo 'OK';
                return true;
            }


            switch($pay_type) {
                case 're':
                case 'fa':
                    try {
                        $v_uuid_anketa = strval(substr(\request()->input('OrderId'), 2));
                        $v_amount = floatval(\request()->input('Amount') / 100);
                        $payment = new Payments();
                        $payment->amount = $v_amount;
                        $payment->paid_at = now();
                        $payment->payload = json_encode($input);
                        $payment->payment_id = @\request()->input('PaymentId') ?? null;
                        $payment->save();

                        if (!$item = Questionnaire::find($v_uuid_anketa)) {
                            Storage::disk('local')->append('logs/payments_'.date("Y_m_d").'_error_.txt', date('d.m.Y H:i:s') .': АНКЕТА [' . $v_uuid_anketa.'] : Не найдена АНКЕТА В БД');
                        }

                        $arr_anketa_data = $item->data;

                        if (!$lead = Lead::with('tags')->where('anketa_uuid', $item->uuid)->first()) {
                            Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', date('d.m.Y H:i:s') . ': АНКЕТА [' . $v_uuid_anketa . '] : Нет CДЕЛКИ');
                        }

                        if (!empty($lead) && isset($lead->uuid)) {
                            $payment->lead_id = $lead->uuid;
                            $payment->pay_for = 'stylist';
                            $payment->save();

                            // Сделка должна перейти в статус ОПЛАЧЕНО в АМО
                            $lead->state_id = 2;
                            if ($lead->tag == 'BOXBERRY' && Tags::find(5)) {
                                $lead->tags()->attach(5);
                            }
                            if ($lead->tag == 'logsys' && Tags::find(4)) {
                                $lead->tags()->attach(4);
                            }
                            $lead->client_num = (int) Lead::where('client_id',$lead->client_id)->max('client_num') + 1;
                            $lead->save();
                        }

                        $promocode = '';
                        if(isset($arr_anketa_data['rf'])) {
                            if ($arr_anketa_data['rf'] !== false) {
                                $promocode = $arr_anketa_data['rf'];
                                if (!$bonuses = Bonus::where('promocode', $promocode)->first())
                                    Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', date('d.m.Y H:i:s') . ': АНКЕТА [' . $v_uuid_anketa . '] : Не найден ПРОМОКОД [' . $promocode . '] В БД');
                            }
                        }

                        if(isset($arr_anketa_data['coupon']) && $promocode == '') {
                            {
                                if ($bonuses = Bonus::where('promocode', $arr_anketa_data['coupon'])->first())
                                    $promocode = $arr_anketa_data['coupon'];
                            }
                        }

                        if($promocode != '') {

                            $points = 500;
                            $bonusTransaction = new BonusTransactions();

                            $bonusTransaction->client_id = $bonuses->client_id;
                            $bonusTransaction->points = $points;
                            $bonusTransaction->type = 'add';
                            $bonusTransaction->promocode = $promocode;
                            $bonusTransaction->description = [
                                'anketa_uuid'=>$item->uuid,
                                'lead_uuid'=>$lead->uuid ?? '',
                                'client_uuid'=>$lead->client_id ?? '' ];
                            $bonusTransaction->save();

                            $bonuses->points = $bonuses->points + $points;
                            $bonuses->save();

                        }

                        //отправка в АМО
                        $pars = ['id'=> $lead->amo_lead_id, 'paid_price' => $v_amount];
                        $anketa= Questionnaire::where('uuid', $lead->anketa_uuid)->latest()->first();
                        if(!empty($anketa->data['coupon'])) $pars['coupon'] = $anketa->data['coupon'];
                        $amo = new AmoCrm();
                        $res_amo = $amo->update_lead($pars);
                        //Storage::disk('local')->append('logs/payments_'.date("Y_m_d").'_error_.txt', date('d.m.Y H:i:s') .': АНКЕТА [' . $v_uuid_anketa.'] : АМО не передан тег оплаты [' . json_encode(array_merge($pars, $res_amo), JSON_UNESCAPED_UNICODE).']');

                    }
                    catch
                        (\Exception $e){
                            Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', $e);
                        }

                        break;

                case 'fb':

                    try{
                        $v_uuid_feedback = strval(substr(\request()->input('OrderId'), 2));

                        $v_amount = floatval(\request()->input('Amount') / 100);

                        $payment = new Payments();
                        $payment->amount = $v_amount;
                        $payment->paid_at = now();
                        $payment->payload = json_encode($input);
                        $payment->pay_for = 'products';
                        $payment->payment_id = \request()->input('PaymentId') ?? null;

                        $payment->save();

                        if (!$feedback = FeedbackgeneralQuize::where('uuid', $v_uuid_feedback)->first()) {
                            Storage::disk('local')->append('logs/payments_'.date("Y_m_d").'_error_.txt', date('d.m.Y H:i:s') .': ФИДБЕК [' . $v_uuid_feedback.'] : Не найден ФИДБЕК В БД');
                        }

                        if (!$lead = Lead::find($feedback->lead_id)) {
                            Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', date('d.m.Y H:i:s') . ': ФИДБЕК [' . $v_uuid_feedback . '] : Нет CДЕЛКИ');
                        }

                        if (!empty($lead) && isset($lead->uuid)) {
                            $payment->lead_id = $lead->uuid;
                            $payment->save();

                            // Сделка должна перейти в статус СДЕЛКА ЗАКРЫТА
                            $lead->state_id = 14;
                            $lead->save();

                        }
                    }
                    catch
                    (\Exception $e){
                        Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', $e);
                    }
                    break;

                case 'le':
                        try {
                            $v_id_lead = strval(substr(\request()->input('OrderId'), 2));
                            $v_amount = floatval(\request()->input('Amount') / 100);
                            if (!$lead = Lead::find($v_id_lead)) {
                                Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', date('d.m.Y H:i:s') . ': ОПЛАТА ПО СДЕЛКЕ [' . $v_id_lead . '] : Нет CДЕЛКИ');
                            }

                            if (!empty($lead) && isset($lead->uuid)) {
                                $payment = new Payments();
                                $payment->amount = $v_amount;
                                $payment->paid_at = now();
                                $payment->payload = json_encode($input);
                                $payment->pay_for = 'stylist';
                                $payment->lead_id = $lead->uuid;
                                $payment->payment_id = @\request()->input('PaymentId') ?? null;

                                $payment->save();

                                // Сделка должна перейти в статус СДЕЛА ОПЛАЧЕНА
                                $lead->state_id = 2;
                                if ($lead->tag == 'BOXBERRY' && Tags::find(5)) {
                                    $lead->tags()->attach(5);
                                }
                                if ($lead->tag == 'logsys' && Tags::find(4)) {
                                    $lead->tags()->attach(4);
                                }
                                $lead->client_num = (int) Lead::where('client_id',$lead->client_id)->max('client_num') + 1;
                                $lead->save();
                            }

                            //отправка в АМО
                            $pars = ['id'=> $lead->amo_lead_id, 'paid_price' => $v_amount];
                            $anketa= Questionnaire::where('uuid', $lead->anketa_uuid)->latest()->first();
                            $amo = new AmoCrm();
                            $res_amo = $amo->update_lead($pars);
                        }
                        catch
                        (\Exception $e){
                            Storage::disk('local')->append('logs/payments_' . date("Y_m_d") . '_error_.txt', $e);
                        }
                    break;

                case 'cp':
                    $v_amount = floatval(\request()->input('Amount') / 100);
                    $payother = new Payother();
                    $payother->amount = $v_amount;
                    $payother->payload = request()->all();
                    $payother->save();
                break;

            }

        }

        echo 'OK';
    }

    public function paymentSberbank() {
        $paymentSberbank = new PaymentSberbank();
        return view('admin.payments.sberbank-table',['data' => $paymentSberbank->decodedData()]);
    }

}
