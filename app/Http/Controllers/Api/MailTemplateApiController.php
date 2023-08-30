<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\Common\MailTemplate;
use App\Http\Requests\Api\MailTemplateApiRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class SmsTemplateApiController
 * @package App\Http\Controllers\Api
 */
class MailTemplateApiController extends Controller
{

    /**
     * Список всех шаблонов
     * @return JsonResponse
     */
    public static function list(){

        $items = MailTemplate::all()->pluck('name','id');

        return response()->json([
            'result' => true,
            'templates' => $items
        ]);
    }


    /** Создание почтового шаблона
     * @param MailTemplateApiRequest $request
     * @return JsonResponse
     */
    public function create(MailTemplateApiRequest $request)
    {

        if ($item = MailTemplate::where('name', $request->input('name'))->first())
            return response()->json([
                'result' => false,
                'message' => 'Шаблон с таким именем уже существует!',
                'data' => $item,
            ], 200);

        $item = new MailTemplate();
        $item->name = $request->input('name');
        $item->text = $request->input('text');
        $item->html = $request->input('html');
        $item->save();

        return response()->json([
            'result' => true,
            'message' => 'Шаблон успешно добавлен!',
            'template_id' => $item->id
        ]);
    }


    /**
     * @param MailTemplateApiRequest $request
     * @return JsonResponse
     */
    public function destroy(MailTemplateApiRequest $request){


        if (!$item = MailTemplate::where('name', $request->input('name'))->first())
            return response()->json([
                'result' => false,
                'message' => 'Шаблон не найден!'
            ], 200);

        $item->delete();

        return response()->json([
            'result' => true,
            'message' => 'Шаблон успешно удален',
            'data' => $item
        ], 200);
    }


}