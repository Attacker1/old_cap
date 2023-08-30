<?php

namespace App\Http\Controllers\Api;

use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\BonusTransactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


/**
 * Апи контроллер по реффералкам
 * Class RefferalApiController
 * @package App\Http\Controllers\Api
 */
class RefferalApiController extends Controller
{

    /**
     * Добавление по новой рефералке
     * @params Request
     * return json
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register()
    {

        $validator = Validator::make(request()->all(), [
            'code' => 'required | string | max:36',
            'registered_id' => 'nullable | required | integer', // Активация нового клиента уже с ID ?
            'type' => 'nullable | required | string', // Тип бонуса ?
            'points' => 'nullable | integer ', // Количество бонусов ?
            'paid_by' => 'nullable | integer ', // Количество бонусов ?
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if (!$refferal = Bonus::where('promocode', \request()->input('code'))->first())
            return response()->json([
                'result' => false,
                'message' => 'Данный промокод не найден'
            ]);

        $transaction = new BonusTransactions();
        $transaction->promocode = $refferal->promocode;
        $transaction->client_id = $refferal->client_id;
        $transaction->registered_id = \request()->input('client_id') ?? null; // Кто зарегистрировался
        $transaction->type = 'add';
        $transaction->paid_by = 'stylist';
        $transaction->save();

        return response()->json([
            'result' => true,
            'message' => 'Промокод активирован!',
            'data' => $transaction
        ]);
    }

    /**
     * Начисление за баллов за отзыв
     * @params Request
     * return json
     * @return JsonResponse
     * @throws ValidationException
     */
    public function feedback()
    {

        $validator = Validator::make(request()->all(), [
            'code' => 'required | string | max:36',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if (!$refferal = Bonus::where('promocode',\request()->input('code'))->first())
            return response()->json([
                'result' => false,
                'message' => 'Данный промокод не найден'
            ]);

        return response()->json([
            'result' => true,
            'data' => $refferal
        ]);
    }

    /**
     * Проверка промокода
     * @params Request
     * return json
     * @return JsonResponse
     * @throws ValidationException
     */
    public function check($code)
    {
        $validator = Validator::make(['code'=>$code], [
            'code' => 'required | string | max:36',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if (!$refferal = Bonus::where('promocode', $code)->first())
            return response()->json([
                'result' => false,
                'message' => 'promocode nof found'
            ]);

        return response()->json([
            'result' => true,
            'data' => $refferal
        ]);
    }


}