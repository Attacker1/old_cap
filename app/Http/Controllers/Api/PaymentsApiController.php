<?php

namespace App\Http\Controllers\Api;

use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class PaymentsApiController extends Controller
{

    /**
     * Добавление новой опдаты
     * @params Request
     * return json
     * @return JsonResponse
     */
    public function create()
    {

        // TODO: перенсти валидацию?
        $validator = Validator::make(request()->all(), [
            'lead_id' => 'required | string | max:36 ',
            'amount'  => 'required | integer ',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if (!$item = Lead::where('uuid', \request()->input('lead_id'))->first())
            return response()->json([
                'result' => false,
                'message' => 'Сделка не найдена!'
            ], 200);

        $payment = new Payments();
        $payment->lead_id = \request()->input('lead_id');
        $payment->amount = \request()->input('amount');
        $payment->save();

        return response()->json([
            'result' => true,
            'message' => 'Оплата успешно добавлена!',
            'payment_id' => $payment->id
        ]);
    }

    /**
     * Информауия по оплате
     * @params Request
     * return json
     */
    public function read()
    {
        $validator = Validator::make(request()->all(), [
            'lead_id' => 'required | string | max:36'
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {
            if (!$item = Lead::where('uuid', \request()->input('lead_id'))->first())
                return response()->json([
                    'result' => false,
                    'message' => 'Сделка не найдена!'
                ], 200);

            return response()->json([
                'result' => true,
                'message' => false,
                'data' => $item
            ], 200);
        }
        catch (Exception $e){
            return 'Ошибка при выполнении запроса, см. Lara logs';
        }
    }

    /**
     * Редактирование оплаты
     * @params Request
     * return json
     */
    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'lead_id' => 'required | string | max:36',
            'client_id' => 'nullable | string | max:36',
            'amo_lead_id' => 'nullable | integer',
            'stylist_id' => 'nullable | integer',
            'state_id' => 'nullable | integer | between:0,14',
            'state_logistic' => 'nullable | integer',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {
            if (!$item = Lead::where('uuid', \request()->input('lead_id'))->first())
                return response()->json([
                    'result' => false,
                    'message' => 'Сделка не найдена!'
                ], 200);


            $item->client_id = !empty(\request()->input('client_id')) ? \request()->input('client_id') : $item->client_id;
            $item->stylist_id = !empty(\request()->input('stylist_id')) ? (int) \request()->input('stylist_id') : $item->stylist_id;
            $item->amo_lead_id = !empty(\request()->input('amo_lead_id')) ? (int) \request()->input('amo_lead_id') : $item->amo_lead_id;
            $item->state_id = !empty(\request()->input('state_id')) ? \request()->input('state_id') : $item->state_id;
            $item->state_logistic = !empty(\request()->input('state_logistic')) ? \request()->input('state_logistic') : $item->state_logistic;
            $item->save();

            return response()->json([
                'result' => true,
                'message' => 'Сделка сохранена!',
                'data' => $item
            ], 200);
        }
        catch (QueryException $e){
            return 'Ошибка при выполнении запроса, см. Lara logs';
        }
    }

    public function destroy(){

        $validator = Validator::make(request()->all(), [
            'lead_id' => 'required | string | max:36',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if (!$item = Lead::where('uuid', \request()->input('lead_id'))->first())
            return response()->json([
                'result' => false,
                'message' => 'Сделка не найдена!'
            ], 200);

        $item->delete();
        return response()->json([
            'result' => true,
            'message' => 'Сделка удалена',
            'data' => $item
        ], 200);
    }


}