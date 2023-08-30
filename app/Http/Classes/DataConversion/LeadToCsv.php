<?php


namespace App\Http\Classes\DataConversion;


use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Lead;
use Carbon\Carbon;
use Mockery\Exception;

class LeadToCsv
{

    private $result = [];
    private $row = [];
    private $rowHeader = [];

    private $headings = true;
    private $items;
    private $type;

    public function __construct($items = [], $type = 'logsis')
    {
        $this->items = $items;
        $this->type = $type;
    }

    public function make()
    {

        $leads =  Lead::with('clients')->whereIn('uuid', $this->items)->get();



        foreach ($leads as $lead) {

            try {
                // заголовки
                if($this->headings) {
                    $this->answers($lead);
                    array_push($this->result, $this->rowHeader);
                }

                // Тело
                if ($lead && $lead->state_id >= 6 && ($lead->tag == 'logsys' || empty($lead->tag))) {
                    $this->answers($lead);
                    array_push($this->result, $this->row);
                    $this->row = [];
                }

            } catch (\Exception $e) {
//                dump($rowNr, $q->uuid, $q->data, $e);
            }

        }


        return [
            'data' => $this->result
        ];


    }

    private function answers($data)
    {
//        dd($data->questionnaire);
//    dd($data->questionnaire->data['anketa']['question'][65]['option'][(int)$data->questionnaire->data['anketa']['question'][65]['answer']]['text']);

        $oldFormat = isset($data->questionnaire->data['anketa']);

        // ФИО
        $fio = ($data->clients->second_name ?? '') . ' '
            . ($data->clients->name ?? '') . ' '
            . ($data->clients->patronymic ?? '');


        // Интервал доставки
        $timeDelivery = [];
        $options = [
                0 => ['text' => '10:00 - 14:00', 'link' => 0],
                1 => ['text' => '14:00 - 18:00', 'link' => 0],
                2 => ['text' => '18:00 - 22:00', 'link' => 0],
                3 => ['text' => '12:00 - 18:00', 'link' => 1],
                4 => ['text' => '18:00 - 22:00', 'link' => 1],

        ];
        if (!isset($data->data['time_delivery'])) {
            if ($oldFormat) {
                if ($data->questionnaire && !empty($data->questionnaire->data['anketa']['question'][65]['answer'])) {
                    $timeDelivery = explode(" - ", $data->questionnaire->data['anketa']['question'][65]['option'][$data->questionnaire->data['anketa']['question'][65]['answer']]['text']);
                }
            } else {
                if (!empty($data->questionnaire->data[65])) {
                    $timeDelivery = is_array($data->questionnaire->data[65]) ?
                        explode(" - ", $options[$data->questionnaire->data[65][0]]) :
                        explode(" - ", $options[$data->questionnaire->data[65]]);
                }
            }
        } else {
            $timeDelivery = explode(" - ", $data->data['time_delivery']);
        }


        // Город доставки
        $city = '';
        if ($oldFormat) {
            if($data->questionnaire && $data->questionnaire->data['anketa']['question'][71]['answer'] === 0) {
                $city = 1;
            }

            if($data->questionnaire && $data->questionnaire->data['anketa']['question'][71]['answer'] === 1) {
                $city = 2;
            }
        } else {
            if (!empty($data->questionnaire->data[71]) && $data->questionnaire->data[71] === 0) {
                $city = 1;
            }
            if (!empty($data->questionnaire->data[71]) && $data->questionnaire->data[71] === 1) {
                $city = 2;
            }
        }

        //Дата доставки
        $delivery_at = $data['delivery_at'];
        if ($oldFormat) {
            if (!$delivery_at && $data->questionnaire) {
                $delivery_at = @Carbon::parse($data->questionnaire->data['anketa']['question'][64]['answer']);
            }
        } else {
            if (!$delivery_at && !empty($data->questionnaire->data[64])) {
                $delivery_at = @Carbon::parse($data->questionnaire->data[64]);
            }
        }
        $delivery_at = @Carbon::parse($delivery_at)->format('Y-m-d');


        // Адрес получения
        $delivery_address = '';
        if (!isset($data->data['address_delivery'])) {
            if ($oldFormat) {
                if ($data->questionnaire && $data->questionnaire->data['anketa']['question'][63]['answer']) {
                    $delivery_address = $data->questionnaire->data['anketa']['question'][63]['answer'];
                }
            } else {
                if (!empty($data->questionnaire->data[63])) {
                    $delivery_address = $data->questionnaire->data[63];
                }
            }
        } else {
            $delivery_address = $data->data['address_delivery'];
        }


        // Телефон получателя
        $phone = '';
        if ($oldFormat) {
            if ($data->questionnaire && $data->questionnaire->data['anketa']['question'][15]['answer']) {
                $phone = $data->questionnaire->data['anketa']['question'][15]['answer'];
            }
        } else {
            if (!empty($data->questionnaire->data[15])) {
                $phone = $data->questionnaire->data[15];
            }
        }


        $topItems = [
            ['label' => 'номер накладной, внутренний №', 'answer' => $this->type == 'logsis' ? $data['amo_lead_id'] ?? '' : $data['amo_lead_id'] . '_1' ?? '' ],
            ['label' => 'дата доставки', 'answer' => $delivery_at ?? '' ],
            ['label' => 'Интервал: с', 'answer' => !empty($timeDelivery) ? $timeDelivery[0] : ''],
            ['label' => 'Интервал: до', 'answer' => !empty($timeDelivery) ? $timeDelivery[1] : ''],
            ['label' => 'КЛАДР города получения', 'answer' => '' ],
            ['label' => 'Адрес получения', 'answer' => $delivery_address ?? '' ],
            ['label' => 'Получатель', 'answer' => $fio],
            ['label' => 'Телефон получателя', 'answer' => $phone ],
            ['label' => 'Вес отправления', 'answer' => '1' ],
            ['label' => 'Оценочная стоимость', 'answer' => '0' ],
            ['label' => 'Наложеный платеж', 'answer' => '0' ],
            ['label' => 'Комментарий к заказу', 'answer' => 'Просьба за час позвонить' ],
            ['label' => 'Габарит 1, см', 'answer' => '28' ],
            ['label' => 'Габарит 2, см', 'answer' => '28' ],
            ['label' => 'Габарит 3, см', 'answer' => '30,5' ],
            ['label' => 'sms- информирование', 'answer' => '0' ],
            ['label' => 'Артикул', 'answer' => '1' ],
            ['label' => 'Описание вложений', 'answer' => 'Одежда' ],
            ['label' => 'Количество', 'answer' => '1' ],
            ['label' => 'Стоимость', 'answer' => '0' ],
            ['label' => 'Ставка НДС', 'answer' => '2' ],
            ['label' => 'Мест в заказе', 'answer' => '1' ],
            ['label' => 'Вскрытие заказа', 'answer' => '0' ],
            ['label' => 'Частичная выдача', 'answer' => '0' ],
            ['label' => 'примерка', 'answer' => '0' ],
            ['label' => 'Доп звонок', 'answer' => '0' ],
            ['label' => 'Возврат документов', 'answer' => '0' ],
            ['label' => 'подъем кгт', 'answer' => '0' ],
            ['label' => 'Грузовой лифт', 'answer' => '0' ],
            ['label' => 'этаж', 'answer' => '0' ],
            ['label' => 'Маркировка в base64 (поставить 1, если это так)', 'answer' => '0' ],
            ['label' => 'Маркировка', 'answer' => '0' ],
            ['label' => 'Город отправления (1-Москва,2-Санкт Петербург)', 'answer' => $city ]
        ];


        foreach ($topItems as $items) {
            $this->headings
                ? array_push($this->rowHeader, $items['label'])
                : array_push($this->row, $items['answer']);
        }

        // заколовки только один раз
        $this->headings = false;
    }
}

