<?php

namespace App\Http\Controllers\AdminClients\Transitions\Feedback;

use App\Http\Classes\Common;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Payments;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\AdminClient\FeedbackQuize;
use App\Http\Models\Catalog\Note;
use Illuminate\Support\Str;


class HandleClientController extends Controller
{
    //A%*q`VSb9Zg https://office.thecapsula.ru/admin/transitions-temp/handleFb-hgyty7yhjkjnj
    public function make()
    {
        $client = Client::find('20c22389-8c22-417f-b340-72c7595c2bab');
        if(!$client) die('клиент не найден');
        $lead = $client->leads->where('uuid','5675655e-abd3-42fb-805c-01d575daf8a7')->first();

        echo '<pre>'. $client->uuid. ' клиент </pre>';
        echo '<pre>'. $client->phone. ' телефон </pre>';
        if(!$lead) die('сделка не найдена');
        echo '<pre>'. $lead->uuid. ' сделка </pre>';
        echo '<pre> '. $lead->state_id ?? 'null'. ' сделка статус </pre>';

        if($lead->total == '') $total = 0; else $total = $lead->total;
        echo '<pre> '. $total . ' сделка total </pre>';
        if($lead->discount == '') $discount = 0; else $discount = $lead->discount;
        echo '<pre> '. $discount . ' сделка discount </pre>';

        $fb_main = FeedbackgeneralQuize::where('lead_id', $lead->uuid)->orderBy('id', 'desc')->first();
        if(!$fb_main) die('фидбек не найден');
        echo '<pre>'. $fb_main->id. ' Фидбек id </pre>';

        $stylist_pay = Payments::where('lead_id', $lead->uuid)->first();
        if(!$stylist_pay) {
            $stylist_pay_amount = 390;
            //die('Оплата не найдена');
        } else $stylist_pay_amount = $stylist_pay->amount;

        if(!$fbs = $fb_main->feedbackgQuize->where('action_result','buy')) {
            die('нет продуктов на оплату');
        }
        $i = 1;
        $sum = 0;
        foreach ($fbs as $fb) {
            echo '<pre>' . str_replace('.', ',', $fb->price) . '    продукт ' . $i . '</pre>';
            $i++;
            $sum+= $fb->price;
        }
        echo '<pre>'. $sum . ' всего</pre>';
        echo '<pre>'. $stylist_pay_amount. ' подбор</pre>';
        $total = $minus_stylist = bcdiv(($sum - $stylist_pay_amount), 1, 2);
        echo '<pre>'. $minus_stylist . ' минус подбор</pre>';
        $discount = $stylist_pay_amount;
        $products = Note::with(['products'])->where('order_id', $lead->amo_lead_id)->orderBy('id', 'desc')->first()->products;
        /*foreach($products as $product) {
            dump($product->id);
            dump($product->price);
        }*/
        if(count($fbs) == count($products)) {
            $total = $per_75 = bcdiv(( $minus_stylist * 0.75), 1, 2);
            echo '<pre>'. $per_75 . ' total итого -25%</pre>';
            $discount = $sum - $per_75;
            echo '<pre>'. $discount . ' discount -25%</pre>';
        }
        $lead->state_id = 12;
        $lead->total = $total;
        $lead->discount = $discount;
        //$lead->save();
    }

}
