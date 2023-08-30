<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Models\AdminClient\Questionnaire;


class AnketaApiController extends Controller
{

    /**
     * Добавление нового клиента
     * @params Request
     * return json
     */
    public function getItem($code)
    {
        
        $validator = Validator::make(['code'=>$code], [
            'code' => 'required|string|max:36|exists:questionnaires,code'
        ]);

        if($validator->fails()) return response()->json(['result' => false, 'errors' => 'wrong parameter']);
        
        $item = Questionnaire::where('code', $code)->first();
        
        if(!$item) return response()->json(['result' => false, 'errors' => 'anketa not found']);

        return response()->json(['result' => true, 'anketa' => $item]);
    }

    public function index(Request $request)
    {

        if(!$request->action) return response('', 400);
        if(!$request->params) return response('', 400);

        $request_params = $request->params;

        switch($request->action) {
            case 'add' :
                $params['data'] = json_encode($request_params['data']);
                $validator = Validator::make($params, [
                    'data' => 'required|json'
                ]);
                if($validator->fails()) {
                    Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Анкета добавление. Ошибки валидации [' . $validator->errors()->first() . ']']);
                    return response()->json(['result' => false, 'errors' => $validator->errors()]);
                }

                $code = Questionnaire::createCode();

                $questionnaire = new Questionnaire();
                $questionnaire->fill([
                    'code' => $code,
                    'data' => $request_params['data']
                ]);

                try {
                    $questionnaire->save();
                } catch (QueryException $ex) {
                    Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Анкета add ошибка сохранения ' . $ex->errorInfo[2] ]);
                    return response()->json(['result' => false, 'errors' => 'ошибка сохранения']);
                }

                return response()->json(['result' => true, 'new_anketa' => $questionnaire]);
                break;

            case 'update' :
                $params = $request_params;
                if(isset($request_params['data'])) $params['data'] = json_encode($request_params['data']);
                $validator = Validator::make($params, [
                    'data' => 'nullable|json',
                    'uuid' => 'required|string|max:36| uuid',
                    'client_uuid' => 'nullable|string|max:36| uuid',
                    'amo_id' => 'nullable| integer'
                ]);

                if($validator->fails()) {
                    Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Анкета update. Ошибки валидации [' . $validator->errors()->first() . ']']);
                    return response()->json(['result' => false, 'errors' => $validator->errors()]);
                }
                $questionnaire = Questionnaire::find($request_params['uuid']);
                if(!$questionnaire) return response()->json(['result' => false, 'errors' => 'anketa not found']);

                if(isset($request_params['data'])) $questionnaire->data = $request_params['data'];
                if(isset($request_params['client_uuid'])) $questionnaire->client_uuid = $request_params['client_uuid'];
                if(isset($request_params['amo_id'])) $questionnaire->amo_id = $request_params['amo_id'];

                try {
                    $questionnaire->save();
                } catch (QueryException $ex) {
                    Log::channel('customlog')->info([date('d.m.Y H:i:s') . ': Api Анкета add ошибка сохранения ' . $ex->errorInfo[2] ]);
                    return response()->json(['result' => false, 'errors' => 'ошибка сохранения']);
                }

                return response()->json(['result' => true]);

            case 'search_by_uuid' :
                
                $validator = Validator::make($request_params, [
                    'uuid' => 'required|string|max:36| uuid'
                ]);
                if($validator->fails()) return response()->json(['result' => false, 'errors' => $validator->errors()]);

                $questionnaire = Questionnaire::find($request_params['uuid']);
                if(!$questionnaire) return response()->json(['result' => false, 'errors' => 'anketa not found']);

                return response()->json(['result' => true, 'anketa'=>$questionnaire]);

            default: return response('method not found', 400);    
        }
        

    }

}