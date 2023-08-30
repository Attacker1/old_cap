<?php

namespace App\Http\Controllers\Classes;

use App\Abstracts\Doli;
use App\Exceptions\DoliApiException;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Common\DoliTransactions;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use App\Services\Doli\CancelDoli;
use App\Services\Doli\CommitDoli;
use App\Services\Doli\CreateDoli;
use App\Services\Doli\RefundDoli;
use App\Services\Doli\StoreTransactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

/**
 * Работа с API DOLI От Тинькофф
 * Class DoliApi
 * @package App\Http\Controllers\Classes
 */
class DoliApi
{
    private $stage = true;
    private string $url;
    const METHOD_REFUND = "refund";
    const METHOD_CANCEL = "cancel";
    const ORDER_ID = "test_2109144";
    const ORDER_PART_SUMM = 50;
    private StoreTransactions $store;

    public function __construct()
    {
        $this->store = new StoreTransactions();
    }

    public function createOrder(FeedbackgeneralQuize $feedback, $postix_id = false)
    {

        $doli = new CreateDoli($postix_id);
        $doli->setBody($feedback);

        $response = $this->send($doli);
        if (!self::checkCreation($doli,$feedback,$postix_id,$response))
            return false;

        return !empty($response->link)
            ? $response->link
            : false;
    }

    public function commitOrder(FeedbackgeneralQuize $feedback)
    {
        $doli = new CommitDoli();
        $doli->setBody($feedback);

        return $this->send($doli);

    }

    public function refundOrder(Model $id)
    {
        $doli = new RefundDoli();
        $doli->setBody($id);

        return $this->send($doli);

    }

    public function cancelOrder(Model $lead)
    {
        $doli = new CancelDoli();
        $doli->setBody($lead);

        return  $this->send($doli);

    }

    public function createRaw(Model $lead)
    {
        $doli = new CreateDoli();
        $doli->setBody($lead);

        return  $this->send($doli);

    }


    /** Переделать на Вебхук
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function webhook($uuid = false)
    {
        try {
            @Log::channel('doli')->info(json_encode(request()->all()));
            @Log::channel('doli')->info('webhook feedback uuid: ' . @$uuid);

            $doli_id    = (string) request()->input('id');
            $status     = (string) request()->input('status');
            $feedback   = @FeedbackgeneralQuize::with('feedbackgQuize')->whereUuid($uuid)->first();

            if ($uuid) {
                switch ($status) {
                    case "wait_for_commit":
                        @self::commitOrder($feedback);
                        break;

                    case "completed":
                    case "committed":
                        $client = @Client::find($feedback->client_uuid);
                        $lead = @$client->leads->where('uuid', $feedback->lead_id)->first();
                        self::savePayment($feedback, $lead);
                        break;

                    default:
                        break;
                }
            }
        }
        catch (\Exception $e){
            @Log::channel('err_doli')->alert('Ошибка UUID: ' . @$uuid . " " .$e->getMessage());
        }

        if ($this->store->update($doli_id, $status))
            @Log::channel('doli')->info('Успех UUID: ' . @$uuid . " " . $status);

        return response(["result" => true], 200);
    }

    public function fail($uuid)
    {
        @Log::channel('doli')->info('Fail: ' . @request()->input('feedback_uuid'));
        $feedback = @FeedbackgeneralQuize::with('feedbackgQuize')->whereUuid($uuid)->first();
        toastr()->error('Оплата Долями не прошла');
        if (!empty($feedback->lead_id))
            return redirect()->route('admin-clients.payment.show', $feedback->lead_id);
        return redirect()->route('admin-clients.orders.list');
    }

    public function success($uuid = false)
    {

        @Log::channel('doli')->info('COMMIT Success: ' . @$uuid);
        toastr()->success('Оплата Долями успешно завершена! Спасибо за покупку');
        $feedback = @FeedbackgeneralQuize::with('feedbackgQuize')->whereUuid($uuid)->first();
        $client = @Client::find($feedback->client_uuid);
        $lead = @$client->leads->where('uuid', $feedback->lead_id)->first();
        $discont_price = $feedback->paidItems()->sum('discount_price');

        if ($feedback)
            return view('admin-clients.doli.success-doli', [
                'title' => 'Заказ #' . @$lead->client_num,
                'payment_sum' => !empty($discont_price) ? $discont_price : $feedback->paidItems()->sum('price'),
                'amo_id' => @$feedback->lead()->first()->amo_lead_id
            ]);

        return redirect('admin-clients.orders.list');
    }

    private function savePayment(FeedbackgeneralQuize $feedback, Lead $lead)
    {

        if (empty($payment = Payments::where('lead_id', $feedback->lead_id)->where('source', 'doli')->count())) {
            $payment = new Payments();
            $payment->createDoliPayment($feedback);
        }

        $lead->state_id = 14; //Сделка закрыта
        $lead->save();
        return true;
    }

    private function send(Doli $doli)
    {
        try {
            $this->stage = config('app.env') == 'production' ? false : true;
            $login = $this->stage === true ? config("doli.auth.stage.login") : config("doli.auth.prod.login");
            $pass = $this->stage === true ? config("doli.auth.stage.pass") : config("doli.auth.prod.pass");

            $headers1 = [
                "X-Correlation-ID: " . uuid_v4(),
                "Authorization: Basic " . base64_encode($login . ":" . $pass)
            ];
            $response = Curl::to($doli->getUrl())
                ->withContentType("application/json")
                ->withHeaders($headers1)
                ->withData($doli->getBody())
                ->withOption("SSL_VERIFYHOST", 0)
                ->withOption("SSL_VERIFYPEER", 1)
                ->withOption("SSLCERTTYPE", "PEM")
                ->withOption("SSLCERT", storage_path() . '/certificates/doli/' . 'open-api-cert.pem')
                ->withOption("SSLKEY", storage_path() . '/certificates/doli/' . 'private.key')
                //->enableDebug(storage_path() .'/logs/doli/'.date('Y-m-d').'.log')
                ->post();

            Log::channel('doli')->info($response);

            return !empty($response) ? json_decode($response) : false;
        }
        catch (\Exception $e){
            Log::channel('doli')->critical("Ошибка обработки:" . $e->getMessage());
            return false;
        }
    }

    private function checkCreation($doli,$feedback, $postix_id, $response){

        $postix_id = +$postix_id;

        if ($response && !empty($response->code))
            if ($response->code == 'UNPROCESSABLE_ENTITY')
                $this->createOrder($feedback,$postix_id++);

        if ($lead = Lead::whereUuid($feedback->lead_id)->first())
            $this->store->created($doli,$lead->uuid);

        return true;
    }


}