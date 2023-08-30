<?php


namespace App\Http\Classes\DataConversion;


use App\Http\Models\AdminClient\Questionnaire;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AnketaXlsShort
{
    public  $perPage;
    public  $pageNr;
    public  $table = [];


    private  $lids_cnt;
    private  $payments_cnt;
    private  $fb;
    private  $fa;
    private  $le;
    private  $f_default;
    private  $sum;

    private  $link = '/storage/anketa_short.csv';

    public function __construct($perPage, $pageNr)
    {
        $this->perPage = $perPage;
        $this->pageNr = $pageNr;
    }

    public function headerColumns()
    {

        $arrHeader = [
//            ['', '-', ' ', ' ', ' ', 'Статусы оплаты и сделки', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', 'Utm метки', ' ', ' '],
            [
                'uuid',
                'Дата',

                'оплата фидбека',
                'анкета первая',
                'ручная сделка',
                'другое',
                'СУММА',
                'к-во сделок',
                'к-во оплат',

                'AMO ID',
                'Купон',
                'Сумма',
                'Почта',
                'Тел',
                'Имя',
                'Фамилия',
                'Город',
                'Социалка',
                'Откуда узнала',

                'utm_source',
                'utm_medium',
                'utm_campaign',
                'utm_content',
                'utm_term',
            ]
        ];

        $this->fill($arrHeader,'w');

    }

    private function  fill($arr,$mode) {
        $f = fopen($_SERVER['DOCUMENT_ROOT'] . $this->link, $mode);
        try {
            foreach ($arr as $line) {
                fputcsv($f, $line);
            }
        } catch (\Exception $e) {
//            Log::error($arr,[$e]);
        }

    }

    private function hasLids($lids)
    {
        $this->lids_cnt += count($lids);
        foreach ($lids as $lid) {
            $this->hasPayments($lid->hasPayments);
        }
    }

    private function hasPayments($payments)
    {
        $this->payments_cnt += count($payments);
        foreach ($payments as $payment) {
            $this->payment($payment);
        }
    }

    private function payment($payment)
    {
        if ($ObPayment = json_decode(json_decode($payment->payload))) {

            $code = mb_substr($ObPayment->OrderId, 0, 2);
            $this->sum += (int)$ObPayment->Amount ?? 0;
            switch ($code) {
                case 'fa' :
                    $this->fa = $ObPayment->Amount ?? 0;
                    break;
                case 'fb' :
                    $this->fb = $ObPayment->Amount ?? 0;
                    break;
                case 'le' :
                    $this->le = $ObPayment->Amount ?? 0;
                    break;
                default:
                    $this->f_default = $ObPayment->Amount ?? 0;
            }
        }
    }

    public function reset()
    {
        $this->lids_cnt = 0;
        $this->payments_cnt = 0;
        $this->fa = 0;
        $this->fb = 0;
        $this->le = 0;
        $this->f_default = 0;
        $this->sum = 0;
    }


    public function make()
    {

        if ($this->pageNr == 1) {
            $this->headerColumns();
        }

        $a = ['bc9fcec9-ca94-419c-9f93-89d38fc8f8d4','5093efe6-31da-4d91-ac17-ed0472916f51','ae5ca149-72e6-4f52-9631-b5d2806ddd44','5eb8e846-4538-4928-8b18-de73d655f0a5'];
        $questionnaire = Questionnaire::with('utm', 'hasLids.hasPayments')
            ->paginate($this->perPage, ['*'], '', $this->pageNr);


        foreach ($questionnaire as $row) {
            try {
                if (!empty($row->data)) {
                    unset($tr);

                    $this->reset();

                    // leads
                    $this->hasLids($row->hasLids);


                    $data = $row->data;


                    $tr['uuid'] = $row['uuid']; // uuid
                    $tr['created_at'] = Carbon::parse($row->created_at)->format('Y-m-d H:i:s'); // Дата

                    // payments

                    $tr['fb'] = $this->fb ?: '';
                    $tr['fa'] = $this->fa ?: '';
                    $tr['le'] = $this->le ?: '';
                    $tr['f_default'] = $this->f_default ?: '';
                    $tr['sum'] = $this->sum ?: '';
                    $tr['lids_cnt'] = $this->lids_cnt ?: '';
                    $tr['payments_cnt'] = $this->payments_cnt ?: '';


                    // anketa
                    $tr['amo_id'] = $row->amo_id; // AMO ID
                    $tr['coupon'] = $data['coupon'] ?? ''; // Купон

                    try {
                        if(array_key_exists('anketa',$data)) {
                            $question = $data['anketa']['question'] ?? null;

                            $tr['amount'] = $data['anketa']['amount'] ?? ''; // Сумма
                            $tr['email'] = $question ? $question[14]['answer'] ?? '' : ''; // Почта
                            $tr['phone'] = $question ? $question[15]['answer'] ?? '' : ''; // Тел
                            $tr['name'] = $question ? $question[0]['answer'] ?? '' : ''; //Имя
                            $tr['surname'] = $question ? $question[67]['answer'] ?? '' : ''; //Фамилия
                            $tr['city'] = $question ? $question[71]['option'][$question[71]['answer']]['text'] ?? '' : ''; //Город
                            $tr['socials'] = $question ? $question[68]['answer'] ?? '' : ''; //Социалка
                            $tr['source'] = $question ? $question[69]['option'][$question[69]['answer']]['text'] ?? '' : ''; //Откуда узнала
                        } else {
                            $question = $data;

                            $tr['amount'] = $data['amount'] ?? ''; // Сумма
                            $tr['email'] = $question ? $question[14] ?? '' : ''; // Почта
                            $tr['phone'] = $question ? $question[15] ?? '' : ''; // Тел
                            $tr['name'] = $question ? $question[0] ?? '' : ''; //Имя
                            $tr['surname'] = $question ? $question[67] ?? '' : ''; //Фамилия
                            $tr['city'] = $question ? $question[71] ?? '' : ''; //Город
                            $tr['socials'] = $question ? $question[68] ?? '' : ''; //Город
                            $tr['source'] = $question ? $question[69] ?? '' : ''; //Откуда узнала

                        }
                    } catch (\Exception $e) {
//                        Log::error($row->uuid,[$e]);
                    }



                    $tr['utm_source'] = $row->utm->utm_source ?? ''; // Utm метка
                    $tr['utm_medium'] = $row->utm->utm_medium ?? ''; // Utm метка
                    $tr['utm_campaign'] = $row->utm->utm_campaign ?? ''; // Utm метка
                    $tr['utm_content'] = $row->utm->utm_content ?? ''; // Utm метка
                    $tr['utm_term'] = $row->utm->utm_term ?? ''; // Utm метка
                    $this->table[] = $tr;
                }
            } catch (\Exception $e) {
//                Log::error($row->uuid,[$e]);
            }

        }


        $this->fill($this->table,'a+');

        return [
            'current_page' => $questionnaire->currentPage(),
            'last_page' => $questionnaire->lastPage(),
            'total' => $questionnaire->total(),
            'perPage' => $questionnaire->perPage(),
            //'data' => $this->table,
        ];

        // @TODO-cdnadom:  -> remove
//        dump($this->table);
    }

}
