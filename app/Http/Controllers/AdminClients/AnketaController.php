<?php

namespace App\Http\Controllers\AdminClients;

use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Common\Lead;
use App\Services\AnswersAdapter;
use http\Env\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Ixudra\Curl\Facades\Curl;


class AnketaController extends Controller
{
    private function redirect_router($status, $message = false) {
        $route = 'admin-clients.orders.list';
        switch ($status) {
            case 'message': return redirect()->route($route)->with('message', $message);
            case 'error' : return redirect()->route($route)->with('error', 'Произошла ошибка, обратитесь к администратору');
        }
        return false;
    }

    public function success(Request $request) {

        if(isset($request->OrderId)) {
            $OrderPrefix = substr($request->OrderId, 0, 2);
        }

        if($request->isMethod('post')) {

            $response = Curl::to('https://redtrack.thecapsula.ru/postback')
                ->withData( [
                    'clickid' => $request->clickid,
                    'type' => 'UserPays',
                    'sum' => $request->sum
                    ] )
                ->get();
            Log::channel('redtracker')->info([date('d.m.Y H:i:s') . ': Redtracker  [' . json_encode($response) . ']']);
            die('ok');
        }

        return view('admin-clients.anketa.success.page-success', [
            'Success' => $request->Success ?? '',
            'Amount' => $request->Amount ?? 0,
            'OrderId' => $request->OrderId ?? '',
            'OrderPrefix' => $OrderPrefix ?? ''
        ]);
    }

    public function show(){

        $client = Auth::guard('admin-clients')->user();

        if(!$lead = Lead::where('client_id', $client->uuid)->latest()->first()) {
            return self::redirect_router('error');
        }

        if (!$item = Questionnaire::where('uuid', $lead->anketa_uuid)->latest()->first()) {
            return self::redirect_router('error');
        }

        $url = config('config.ANKETA_URL');

        $code = $item->code;

        if(isset($item->data['anketa'])) $array_questions = $item->data['anketa'];
        else {

            $adapter = new AnswersAdapter();
            $array_questions = [
                'question' => $adapter->setAnketaShow($item->data),
                'disclaimer' => [
                    0 => '<p class="font-size-lg">С 20 августа мы начнем доставлять во все города-миллионники.</p><p class="font-size-lg">Заполняя анкету, вы даете согласие на обработку персональных данных: <a href="https://thecapsula.ru/privacy">https://thecapsula.ru/privacy</a> и принимаете оферту <a href="https://thecapsula.ru/publicoffer">https://thecapsula.ru/publicoffer</a></p>',
                    1 => 'Посмотрите еще образы',
                    2 => '',
                    3 => 'Расскажите нам больше о себе, чтобы стилист сделал подборку персонально под вас',
                    4 => 'А теперь расскажите, какую одежду вы предпочитаете носить',
                    5 => '',
                    6 => 'Вам не нужно вносить предоплату за все вещи, вы заплатите только за то, что вам подойдет',
                    7 => '',
                    8 => 'Для получения капсулы в пункте выдачи вам понадобится паспорт, не забудьте взять его с собой.',
                    9 => '<p class="font-size-lg">Мы собираем капсулу только для размерной сетки от 40 до 48.<br> Если ваш размер меньше 40 или больше 48, вы также можете заполнить анкету и мы напишем вам первой, когда начнем работать с новой линейкой.</p>',
                ]
            ];
        }

        if(@fopen($url . '/uploads/' . $code . '_1','r')) {
            $res['image_1'] = $url . '/uploads/' . $code . '_1';
        } else {
            $res['image_1'] = '';
        }

        if(@fopen($url . '/uploads/' . $code . '_2','r')) {
            $res['image_2'] = $url . '/uploads/' . $code . '_2';
        } else {
            $res['image_2'] = '';
        }
        if(@fopen($url . '/uploads/' . $code . '_3','r')) {
            $res['image_3'] = $url . '/uploads/' . $code . '_3';
        } else {
            $res['image_3'] = '';
        }

        return view('admin-clients.anketa.index', [
            'uuid_view'=> $item->uuid,
            'anketa' => $array_questions
        ]);
    }

};