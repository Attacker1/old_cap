<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Logsis
 * @method static getStatus($amo_lead_id,$ext_order_id = null)
 * @method static isDelivered($amo_lead_id)
 * @method static getStatusV3(string $from, string $to)
 * @method static getZStatus(string $inner_n, string $zorder_id)
 * @method static getAllStatusModel()
 * @method static createOrder(array $params, array $goods)
 * @method static updateOrder(array $params, array $goods)
 * @method static confirmOrder(array $params)
 * @method static changeDate(array $params)
 * @method static orderLabels(array $params)
 * @method static orderLabelsData(array $params)
 * @method static testKey(string $key)
 * @method static sendAct(array $orders)
 * @method static calculate(array $params)
 * @method static returnActs(array $params)
 * @method static newZOrder(array $params)
 */

class LogsisFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\Deliveries\Logsis::class;
    }
}