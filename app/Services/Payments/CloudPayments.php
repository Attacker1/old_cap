<?php

namespace App\Services\Payments;

use App\Abstracts\Payments;
use Ixudra\Curl\Facades\Curl;

class CloudPayments extends Payments
{

    /**
     * Родительский метод - инициализирует данные
     * для сохранения в таблицу платежей
     * @param $model
     */
    public function prepareToCreate($model)
    {
        $this->amount = $model->Amount;
        $this->paid_at = $model->CreatedDateIso;
        $this->payload = json_encode($model, JSON_UNESCAPED_UNICODE);
        $this->payment_id = $model->TransactionId;
        $this->source = 'cloudpayments';
    }

    /**
     * @param $endpoint
     * @param $payload
     * @return mixed
     */
    protected function sendRequest($endpoint, $payload): object
    {

        $publicId = config('config.CLOUD_PAYMENT_PUBLIC_ID');
        $apiSecret = config('config.CLOUD_PAYMENT_SECRET');

        $headers = [
            "Authorization: Basic " . base64_encode($publicId . ":" . $apiSecret)
        ];

        $response = Curl::to($endpoint)
            ->withContentType("application/json")
            ->withHeaders($headers)
            ->withData(json_encode($payload))
            ->post();
        return json_decode($response);
    }


    /**
     * https://developers.cloudpayments.ru/#oplata-po-kriptogramme
     * @return false|string
     */
    public function paymentCharge()
    {
        $endpoint = config('config.CLOUD_PAYMENT_ENDPOINT_CHARGE');
        $payload = [
            'Amount' => request('Amount'),
            "Currency" => request('Currency'),
            'IpAddress' => request()->ip(),
            'Name' => request('Name'),
            'CardCryptogramPacket' => request('CardCryptogramPacket')
        ];

        $CPResponse = $this->sendRequest($endpoint, $payload);
        return $this->checkResponse($CPResponse);
    }

    /**
     * https://developers.cloudpayments.ru/#obrabotka-3-d-secure
     */
    public function threeDSecure()
    {
//        $this->lead_id = request('leadUuid');
        $endpoint =  config('config.CLOUD_PAYMENT_ENDPOINT_3DSECURE');
        $payload = [
            'TransactionId' => (int)request('MD'),
            "PaRes" => request('PaRes'),
        ];

        $CPResponse = $this->sendRequest($endpoint, $payload);
        return $this->checkResponse($CPResponse);
    }


    /**
     * Возвращаем ответ всякий раз на этот метод -
     * в случае успеха класс сохранит платеж в базу (Payment) и вернет его ,
     * в противном случае переведет ответ платежника на фронт
     * @param $CPResponse
     * @return false|string
     */
    public function checkResponse($CPResponse)
    {
        if ($CPResponse->Success) {
            $this->prepareToCreate($CPResponse->Model);
            return $this->create();
        } else {
            return json_encode($CPResponse);
        }
    }


    public function googlePayWidget() {
        $this->amount = $this->request->options['amount'];
        $this->paid_at = date("Y-m-d H:i:s");
        $this->payload = json_encode($this->request->options, JSON_UNESCAPED_UNICODE);
        $this->source = 'cloudpayments';

        return $this->create();
    }

    /**
     * Распределяет задачи в ответ $CPResponse
     * сейчас отключен в checkResponse($CPResponse)
     * нет необходимости пока обрабатываем только 2 вида платежей
     * @param $CPResponse
     * @return false|string
     */
    public function complexPayment($CPResponse)
    {
        if (isset($CPResponse->Model->PaReq)) {
            // Признак 3D secure -> перенаправляем на фронт -> user должен подтвердить платеж
            return json_encode($CPResponse);
        } else {
            return json_encode($CPResponse);
        }
    }








}
