<?php

namespace App\Http\Controllers\Classes;

use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm as AmoCrm;
use App\Http\Models\Common\Delivery;
use App\Http\Models\Common\Lead;
use App\Services\ClientService;
use App\Services\LeadService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use WildTuna\BoxberrySdk\Client;
use WildTuna\BoxberrySdk\Entity\Intake;
use WildTuna\BoxberrySdk\Entity\Customer;
use WildTuna\BoxberrySdk\Entity\Order;
use WildTuna\BoxberrySdk\Exception\BoxBerryException;
use WildTuna\BoxberrySdk\Entity\Place;
use WildTuna\BoxberrySdk\Entity\Item;
use WildTuna\BoxberrySdk\Entity\RussianPostParams;

/**
 * Class Boxberry
 * @package App\Http\Controllers\Classes
 */
class BoxberryApi
{

    const STATUS_OK = 'Выдано';
    private  int $default_period = 7;
    protected  Lead $lead;
    private  bool $error = false;
    private $boxberry;

    public function __construct()
    {
        $this->boxberry = self::initApi();
    }

    /**
     * @return Client
     */
    private function initApi()
    {

        try {

            $bb = new Client(120, 'https://api.boxberry.ru/json.php');
            $bb->setToken('main', config('config.API_BOXBERRY_TOKEN'));
            $bb->setCurrentToken('main');

            return $bb;


        } catch (\Exception $e) {

            $message = "Ошибка создания заказа по сделке: {$e->getMessage()}";
            Log::channel('boxberry')->error($message);

            // Пишем исключению в Деливери класс сделки
            if (!empty(self::$lead))
                self::$lead->delivery()->first()->update(['exception' => $message]);
        }
    }

    /** Валидация перед созданием заказа
     * @param Lead $lead
     * @return array|string
     */
    private function validate(Lead $lead)
    {

        $fullname = $lead->clients->name .  ' ' . @$lead->clients->patronymic . ' ' . @$lead->clients->second_name;
        $params = [
            // TODO: Переделать на  $lead->clients->name
            'name' => $fullname ,
            'phone' => $lead->clients()->first()->phone,
            'email' => $lead->clients()->first()->email,
            'amo_lead_id' => $lead->amo_lead_id,
            'pvz_id' => @$lead->delivery()->first()->delivery_point_id,
            'address' => $lead->delivery()->first()->delivery_address,
        ];

        $validator = Validator::make($params, [
            'name' => 'required | string',
            'phone' => 'required | string ',
            'email' => 'required | email ',
            'amo_lead_id' => 'required | integer ',
            'pvz_id' => 'required | string | min:2 | max:6 ',
            'address' => 'required | string ',
        ]);

        if ($validator->fails()) {
            $this->error = true;
            return $validator->errors()->first();
        }

        Log::channel('boxberry')->debug("ФИО: {$lead->amo_lead_id} | $fullname 1) {$lead->clients->name} 2) {$lead->clients->patronymic} 3) {$lead->clients->second_name}" );

        return (object)$validator->validated();

    }

    /**
     * Удаление заказа
     * @param $lead
     * @return bool
     * @throws \Exception
     */
    public function deleteOrder(Lead $lead)
    {

        try {
            $api = self::initApi();
            if ($res = $api->deleteOrder($lead->delivery()->first()->delivery_id)) {
                $lead->delivery()->first()->delete();
                Log::channel('boxberry')->alert("Заказ УСПЕШНО удален по сделке: " . $lead->uuid . ' Трек номер:' . $lead->delivery()->first()->delivery_id);
                return true;
            }
            return false;

        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->alert("Заказ НЕ удален по сделке:" . $lead->uuid);
        }

    }

    /**
     * Удаление заказа
     * @param $lead
     * @return bool
     * @throws \Exception
     */
    public function deleteById(string $delivery_id)
    {

        try {
            $api = self::initApi();
            if ($res = $api->deleteOrder($delivery_id)) {

                Log::channel('boxberry')->alert("Заказ УСПЕШНО удален из ББ: $delivery_id");
                return true;
            }
            return false;

        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->alert("Заказ НЕ удален из ББ:" .$delivery_id);
        }

    }

    public function createOrder(Lead $lead, $delivery_type = 'PVZ')
    {
        $data = self::validate($lead);
        if ($this->error)
            return $data;

        try {

            $this->lead = $lead;
            $result = false;

            if ($customer = self::setCustomer($data->name, $data->phone, $data->email))
                // Заполняем заказ и привязываем клиента
                if($order = self::setOrder($customer, $data->amo_lead_id, $data->pvz_id))
                    // Посылаем заказа в API
                    $result = $this->boxberry->createOrder($order);


            // Успешный ответ от API
            if (!empty($result['track'])) {
                Log::channel('boxberry')->info("Успешно! Создан заказ по сделке: {$lead->amo_lead_id}. Присвоен Трек номер:" . @$result['track']);
                $lead->delivery()->first()->update(['delivery_id' => $result['track'], 'state' => 1]);
                return $result;
            }

            self::logError();
            return false;

        } // Ловим и пишем ошибку
        catch (BoxBerryException $e) {
            Log::channel('boxberry')->critical("Ошибка создания : {$lead->amo_lead_id} | Текст ошибки" . $e->getMessage());
            $lead->delivery()->first()->update(['exception' => $e->getMessage()]);
            return false;
        } catch (\Exception $e) {
            Log::alert($e);
        }
    }

    /**
     * Лоигруем ошибку по созданию заказа
     */
    private function logError()
    {
        // Пишем ошибку
        $message = "Ошибка создания заказа по сделке: " . self::$lead->uuid;
        self::$lead->delivery()->first()->update(['exception' => $message]);
        Log::channel('boxberry')->critical($message);
    }

    /**
     * Инициализация заказа с установкой клиента
     * @param Customer $customer
     * @param $amo_lead_id
     * @param $delivery_point_id
     * @return Order
     */
    private function setOrder(Customer $customer, $amo_lead_id, $delivery_point_id)
    {

        $order = new Order();
        //$order->setDeliveryDate('2019-05-10'); // Дата доставки от +1 день до +5 дней от текущий даты (только для доставки по Москве, МО и Санкт-Петербургу)
        $order->setOrderId((string)$amo_lead_id); // ID заказа в ИМ
        //$order->setComment('Тестовый заказ');
        $order->setVid(Order::PVZ); // Тип доставки (1 - ПВЗ, 2 - КД, 3 - Почта России)
        $order->setPvzCode($delivery_point_id); // Код ПВЗ
        //$order->setPointOfEntry(config('config.API_BOXBERRY_ENTRY')); // Код пункта поступления
        $order->setCustomer($customer);

        // Создаем места в заказе
        $place = new Place();
        $place->setWeight(2000); // Вес места в граммах
        //$place->setBarcode('1234567890'); // ШК места
        $order->setPlaces($place);

        $item = new Item();
        $item->setId((string)$amo_lead_id);
        $item->setQuantity(1);
        $order->setItems($item);
        $order->setPaymentAmount(0);
        $order->setValuatedAmount(7000);

        // Для отправления Почтой России необходимо заполнить дополнительные параметры
        $russianPostParams = new RussianPostParams();
        $russianPostParams->setType(RussianPostParams::PT_POSILKA); // Тип отправления (см. константы класса)
        $russianPostParams->setOptimize(true); // Оптимизация тарифа
        $russianPostParams->setPackingType(RussianPostParams::PACKAGE_IM_MORE_160); // Тип упаковки (см. константы класса)
        $russianPostParams->setPackingStrict(false); // Строгая упаковка

        // Габариты тарного места (см) Обязательны для доставки Почтой России.
        $russianPostParams->setLength(32);
        $russianPostParams->setWidth(32);
        $russianPostParams->setHeight(32);

        $order->setRussianPostParams($russianPostParams);

        return $order;

    }

    /**
     * Инициализация клиента для ПВЗ
     * @param $fio
     * @param $phone
     * @param $email
     * @return Customer
     */
    private function setCustomer($fio, $phone, $email)
    {

        $customer = new Customer();
        $customer->setFio($fio); // ФИО получателя
        $customer->setPhone($phone); // Контактный номер телефона
        $customer->setEmail($email); // E-mail для оповещений
        return $customer;

    }

    /**
     * Список созданных заказов
     * @param bool $from
     * @param bool $to
     * @return array
     */
    public function listOrders($from = false, $to = false)
    {


        // Если не переданы параметры по датам, делаем выборку за период по-умолчанию
        if (empty($from) || empty($to)) {
            $from = now()->subDays($this->default_period)->format("Y-m-d");
            $to = now()->format("Y-m-d");
        }

        try {
            return $this->boxberry->getOrderList($from, $to);
        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->error("Ошибка получения заказов: " . $e->getMessage());
        }

    }

    /**
     * Список доставляющихся заказов
     * @return array
     */
    public function deliveringOrders()
    {
        try {
            return $this->boxberry->getOrdersInProgress();
        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->error("Ошибка получения доставляющихся заказов: " . $e->getMessage());
        }

    }

    /**
     * Список доставляющихся заказов
     * @return array|mixed
     * @throws BoxBerryException
     */
    public function deliveredOrders()
    {
        $process_list = [];

        if (empty($items = $this->boxberry->getOrdersInProgress()))
            return $process_list;

        foreach ($items as $item){
            if ($item['Status'] == self::STATUS_OK) {
                $process_list[] = $item;
                Log::channel('boxberry')->info('Заказ: ' . $item['imid'] . ' готов к выдаче!');
            }
        }

        return $process_list;
    }

    /**
     * Статусы заказа в системе Боксбери
     * @param $boxberry_order_id - трек-номер / номер ИМ
     * @return array
     */
    public function statusOrder($boxberry_order_id)
    {

        try {
            return $this->boxberry->getOrderStatuses($boxberry_order_id);

        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->error("Ошибка получения статусов по заказу {$boxberry_order_id}: " . $e->getMessage());
        }

    }

    /**
     * Информация по заказу
     * @param $boxberry_order_id - трек-номер / номер ИМ
     * @return array
     */
    public function infoOrder($boxberry_order_id)
    {

        try {
            return $this->boxberry->getOrderInfo($boxberry_order_id);

        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->error("Ошибка получения информации по заказу {$boxberry_order_id}: " . $e->getMessage());
        }

    }

    /**
     * Информация по заказу
     * @param $boxberry_order_id - трек-номер / номер ИМ
     * @return array
     */
    public function updatePvzList()
    {

        try {
            $data = $this->boxberry->getPvzList();

            Storage::disk('local')->put('api/boxberry/pvz.json', json_encode($data));
            return true;

        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->error("Ошибка получения Адресов ПВЗ: " . $e->getMessage());
        }

    }

    /**
     * Информация по заказу
     * @param $address
     * @return array | bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getPvzByAddress($address)
    {

        try {

            if (!$data = collect(json_decode(Storage::disk('local')->get('api/boxberry/pvz.json'))))
                return false;

            if($point = $data->where('Address',$address)->first())
                return $point->Code;
            elseif ($point = $data->where('Address','like',"%{$address}%")->first())
                return $point->Code;

                return  false;


        } catch (BoxBerryException $e) {
            Log::channel('boxberry')->error("Ошибка получения Адреса ПВЗ: " . $e->getMessage());
        }

    }

    /**
     * Получение списка из АМО
     * @param int $amo_flow
     * @return array
     */
    public function getAmoLeads($amo_flow = 31798657){

        $api = new AmoCrm();
        $res = $api->filter_leads($amo_flow);

        if (!empty($res['_embedded']['leads'])){
            $data = [];
            foreach ($res['_embedded']['leads'] as $k=>$v){

                $lead = Lead::where('amo_lead_id',$v['id'])->first() ?? false;

                if (!empty($v['_embedded']['tags'])) {
                    foreach ($v['_embedded']['tags'] as $k2 => $item) {
                        if (in_array($item['name'], ['BOXBERRY', 'boxberry']) ) {
                            $data[$v['id']] = [
                                "id" => @$v['id'],
                                "name" => @$v['name'],
                                "phone" => @$v['phone'],
                                "price" => @$v['price'],
                                "tag" => @$v['_embedded']['tags'],
                                "uuid" => !empty($lead) ? $lead->uuid : false
                            ];

                            foreach ($v['custom_fields_values'] as $k3 => $v3) {

                                if ($v3['field_id'] == 773895)
                                    $data[$v['id']]['delivery_point_id'] = Common::getDigits($v3['values'][0]['value']);

                                if ($v3['field_id'] == 684415)
                                    $data[$v['id']]['delivery_address'] = $v3['values'][0]['value'];

                                if ($v3['field_id'] == 675953)
                                    $data[$v['id']]['phone'] = $v3['values'][0]['value'];
                            }
                        }
                    }
                }

            }
        }

        return $data ?? [];
    }

    // Создание заказов по ВОРОНКЕ АМО
    public function amoToBoxberry(){

        try {

                if ($data = $this->getAmoLeads(43203331)) {


                foreach ($data as $k => $v) {

                    if (!empty($v['uuid'])) {
                        $lead = Lead::whereUuid($v['uuid'])->first();

                        // Ручная сделка - временно заполняем за них
                        if (!empty($lead) && empty($lead->delivery()->first())) {
                            $delivery = new Delivery();
                            $delivery->source = 'BOXBERRY';
                            $delivery->delivery_point_id = $this->getPvzByAddress($v['delivery_address']) ?? $v['delivery_point_id']; // Common::updatePvzID($v['delivery_point_id']);
                            $delivery->delivery_address = $v['delivery_address'];
                            $delivery->lead()->associate($lead);
                            $delivery->save();
                        }


                        if (!empty($lead->delivery()->first()) &&
                            !empty($lead->delivery()->first()->delivery_point_id) &&
                            empty($lead->delivery()->first()->delivery_id)
                        ) {

                            if ($order = $this->createOrder($lead)) {
                                $lead->tag = "BOXBERRY";
                                //$lead->state_id = 8; // TODO: жестко прописан АМО идентификатор
                                $lead->save();

                            }
                        }

                    }
                }
            }
        } catch (\Exception $e){
            Log::channel('boxberry')->error("Ошибка Создания заказа: " . $e->getMessage());
        }

        return true;

    }

    // Временное, до автоматизации. Установка статуса в ЛК ОТГРУЖЕНО
    public function leadStateToDelivering(){
        return true;

        $items = \DB::select(\DB::raw("select uuid, amo_lead_id, delivery_id,state_id,d.created_at
from leads
         left join deliveries d on leads.uuid = d.lead_id
where d.source = 'BOXBERRY' and leads.state_id between  6 and 7"));

        $items_to_delivery = collect($items);

        try {
            $result = self::deliveringOrders();
            $i = 0;
            foreach ($result as $k=>$v){
                if ($items_to_delivery->where('amo_lead_id',$v['ID'])->count() > 0 ) {
                    $lead = Lead::whereAmoLeadId((int)$v['ID'])->first();
                    //$lead->state_id = 8;
                    $lead->save();
                    $leads[] = (int)$v['ID'];
                    $i++;
                }

            }

            Log::channel('boxberry')->info('Сделок изменили статус на ОТГРУЖЕНО: ' . $i);
            return true;

        } catch (BoxBerryException $e) {
            dd($e);
        }

    }

    public function testStory(){

        $response = $this->boxberry->getActsList(now()->subDays(7)->format("Y-m-d"),now()->format("Y-m-d"));
        dd($response);
        $this->boxberry->getActsList();
    }

    public function updateDeliveredOrders(){

        // Получаем Отгруженные заказы В ЛК КАПСУЛА
        $leads = Lead::whereStateId(8)->get();
        $result = self::deliveringOrders();
        $box_collection = collect(json_decode(json_encode($result)));

        $i=0;
        foreach ($leads as $lead){

            $isset = $box_collection->where('ID',$lead->amo_lead_id)->count();

            if ($isset == 0 ) {
                $status = self::statusOrder($lead->amo_lead_id);
                if (!empty($status)) {
                    foreach ($status as $k2 => $v2) {

                        switch ($v2["Name"]) {
                            case "Отправлено в пункт приема":
                            case "Возвращено в ИМ":
                            case "Возвращено с курьерской доставки":
                            case "Возвращено в пункт приема":
                            case "Готовится к возврату":
                                self::setDeliveredStatus($lead, 28); // 28 = Не забрали заказ
                                Log::channel('boxberry')->info('ВОЗВРАТ: ' . @$lead->amo_lead_id);
                                $i++;
                                break 2;

                            case "Выдано":
                                self::setDeliveredStatus($lead, 9); // 9 - Одежда у клиента
                                Log::channel('boxberry')->info('ВЫДАНО КЛИЕНТУ: ' . @$lead->amo_lead_id);
                                $i++;
                                break 2;
                            default:
                                break;
                        }
                    }
                }
            }
        }

        return $i;

    }

    private function setDeliveredStatus($lead,$state_id = 9){

        $lead->state_id = $state_id;
        $lead->save();

        return true;
    }

    /**
     * Получение списка из АМО все страницы
     * @param int $amo_flow
     * @return array
     */
    public function getAmoLeadsTest($amo_flow = 31798657){

        $api = new AmoCrm();
        $api_data = $api->filter_leads_all($amo_flow);
        if (!empty($api_data) && is_array($api_data)) {
            $data = [];
            foreach ($api_data as $k3=>$res) {
                if (!empty($res['_embedded']['leads'])) {
                    foreach ($res['_embedded']['leads'] as $k => $v) {
                        if (!empty($v['_embedded']['tags'])) {
                            foreach ($v['_embedded']['tags'] as $k2 => $item) {
                                if (in_array($item['name'], ['BOXBERRY', 'boxberry'])) {
                                    $data[] = $v['id'];
                                }
                            }
                        }

                    }
                }
            }
        }

        return $data ?? [];
    }

    // Получаем Отгруженные заказы В ЛК КАПСУЛА ИЗ АМО
    public function deliveredTest($leads){

        $result = self::deliveringOrders();

        foreach ($result as $item) {
            $delivering_ids[] = (int)$item['ID'];
        }
        $to_check_leads = array_diff($leads,$delivering_ids);
        $i=0; $returned = []; $gifted = [];
        foreach ($to_check_leads as $lead_id){
                $status = self::statusOrder($lead_id);
                if (!empty($status)) {
                    foreach ($status as $k2 => $v2) {

                        switch ($v2["Name"]) {
                            case "Отправлено в пункт приема":
                            case "Возвращено в ИМ":
                            case "Возвращено с курьерской доставки":
                            case "Возвращено в пункт приема":
                            case "Готовится к возврату":
                                if ($lead = Lead::whereAmoLeadId($lead_id)->first())
                                    self::setDeliveredStatus($lead, 11);
                                $returned[] = $lead_id;
                                Log::channel('boxberry')->info('ВОЗВРАТ: ' . @$lead->amo_lead_id);
                                $i++;
                                break 2;

                            case "Выдано":
                                if ($lead = Lead::whereAmoLeadId($lead_id)->first())
                                    self::setDeliveredStatus($lead, 9);

                                $gifted[] = $lead_id;
                                Log::channel('boxberry')->info('ВЫДАНО КЛИЕНТУ: ' . @$lead_id);
                                $i++;
                                break 2;
                            default:
                                break;
                        }
                    }
                }

        }

        return $gifted;

    }

    /**
     * Обновление сделок по статусу получения посылки до ПВЗ
     * @return bool
     */
    public function deliveredToPvz(){

        try {
            if (!$items = self::deliveringOrders())
                return false;

            $arrived_state = 'На отделении-получателе';
            $items = collect(json_decode(json_encode($items)));
            $arrived_ids = $items->where('Status', $arrived_state)->pluck('ID')->toArray();
            $service = new LeadService();
            $updated_leads_count = $service->arrivedToPvz($arrived_ids);
            if ($updated_leads_count)
                Log::channel('boxberry')->info("Доставлено на пункт выдачи ПВЗ: $updated_leads_count");

            return true;
        } catch (\Exception $e){
            Log::channel('boxberry')->error("Ошибка обновления статуса (deliveredToPvz): {$e->getMessage()}");
            return false;
        }
    }



}