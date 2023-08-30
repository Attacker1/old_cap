<?php

namespace App\Services\Deliveries;

use Carbon\Traits\Date;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Carbon;

/**
 * Class Logsis
 * @property string $apikey
 * @property Client $client
 * @property string $base_uri
 *
 */
class Logsis
{

    protected $apikey;
    protected $client;
    protected $base_uri;
    protected $STATUS_DELIVERED = 5;

    public function __construct()
    {
        $this->apikey   = config('services.logsis.api_key');
        $this->base_uri = config('services.logsis.uri');
        $this->client   = new Client(['base_uri' => $this->base_uri]);
    }


    /**
     * Примечание: inner_n = amo_lead_id
     * @param int $amo_lead_id
     * @param null $ext_order_id
     * @return array
     * @throws GuzzleException
     */

    public function getStatus(int $amo_lead_id,$ext_order_id = null): array
    {
        $params = [
            'key' => $this->apikey,
            'inner_n' => $amo_lead_id,
            'order_id' => $ext_order_id
        ];

        $response = $this->client->get('getstatus', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }


    /**
     * Проверка, доставлен ли заказ
     * @param int $amo_lead_id
     * @return bool
     * @throws GuzzleException
     */
    public function isDelivered(int $amo_lead_id): bool
    {
        $params = [
            'key' => $this->apikey,
            'inner_n' => $amo_lead_id,
        ];

        $response = $this->client->get('getstatus', ['query' => $params]);
        if ($decoded = json_decode($response->getBody(), true)){
            if ($decoded['response']['status'] == $this->STATUS_DELIVERED)
                return true;
        }
        return false;
    }

    /**
     * @param string $from
     * @param string $to
     * @return array
     * @throws GuzzleException
     */

    public function getStatusV3(string $from, string $to): array
    {
        $params = [
            'key' => $this->apikey,
            'from' => $from,
            'to' => $to
        ];

        $response = $this->client->post('getstatusv3', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $query
     * @return array
     * @throws GuzzleException
     */

    public function getZStatus(string $inner_id, string $zorder_id) : array {
        $params = [
            'key' => $this->apikey,
            'inner_n' => $inner_id,
            'zorder_id' => $zorder_id
        ];

        $response = $this->client->get('getzstatus', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @return array
     * @throws GuzzleException
     */

    public function getAllStatusModel(): array
    {
        $params = [
            'key' => $this->apikey
        ];

        $response = $this->client->post('getallstatusmodel', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @param array $goods
     * @return mixed
     * @throws GuzzleException
     */

    public function createOrder(array $params, array $goods) : array {
        $params = array_merge($params, [
            'key' => $this->apikey,
            'goods' => $goods
        ]);

        $response = $this->client->post('createorder', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @param array $goods
     * @return mixed
     * @throws GuzzleException
     */
    public function updateOrder(array $params, array $goods) : array {
        $params = array_merge($params, [
            'key' => $this->apikey,
            'goods' => $goods
        ]);

        $response = $this->client->post('updateOrder', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function confirmOrder(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey
        ]);

        $response = $this->client->post('confirmorder', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function changeDate(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey
        ]);

        $response = $this->client->post('changeDate', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function newZOrder(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey
        ]);

        $response = $this->client->post('newzorder', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function orderLabels(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey,
        ]);

        $response = $this->client->post('order-labels', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function orderLabelsData(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey,
        ]);

        $response = $this->client->post('order-labels-data', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param string $key
     * @return mixed
     * @throws GuzzleException
     */
    public function testKey(string $key) : array {
        $response = $this->client->get('testkey', ['query' => [
            'key' => $key
        ]]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $orders
     * @return mixed
     * @throws GuzzleException
     */
    public function sendAct(array $orders) : array {
        $params = [
            'key' => $this->apikey,
            'orders' => $orders
        ];

        $response = $this->client->post('sendact', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */
    public function calculate(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey,
        ]);

        $client = new Client();
        $response = $client->post('https://'.$this->base_uri.'/public/calculate', ['form_params' => $params]);

        return json_decode($response->getBody(), true);
    }

    /**
     * @param array $params
     * @return mixed
     * @throws GuzzleException
     */

    public function returnActs(array $params) : array {
        $params = array_merge($params, [
            'key' => $this->apikey,
        ]);

        $response = $this->client->get('return-acts', ['query' => $params]);

        return json_decode($response->getBody(), true);
    }
}