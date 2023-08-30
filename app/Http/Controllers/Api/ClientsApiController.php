<?php

namespace App\Http\Controllers\Api;

use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Classes\Client;
use Illuminate\Validation\Rule;
use \App\Http\Models\AdminClient\Client as ModelClient;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Classes\AmoCrm;
use App\Traits\ClientsLeadsCommonApi;

class ClientsApiController extends Controller
{
    use ClientsLeadsCommonApi;

    /**
     * Добавление, редактирование, поиск клиента
     * @params Request
     * @throws ValidationException
     * return json
     */
    public function index(Request $request)
    {

        if (!$request->action) return response('', 400);
        if (!$request->params) return response('', 400);
        if (!is_array($request->params)) return response('', 400);

        $params = $request->params;

        switch ($request->action) {
            case 'add' :
                return response()->json(self::AddClient($params));

            case 'edit' :

                $validator = Validator::make($params, [
                    'uuid' => [
                        Rule::exists('clients', 'uuid')->whereNull('deleted_at'), 'required', 'uuid'
                     ],
                    'name' => 'nullable | string| max:255',
                    'second_name' => 'string | nullable | max:255 | nullable',
                    'patronymic' => 'string | nullable | max:255 | nullable',
                    'phone' => 'regex:/[0-9_]+/i | unique:clients | nullable',
                    'email' => 'string | max:255 | email | nullable | unique:clients',
                    'comments' => 'string | max:2000 | nullable',
                    'referal_code' => 'string | nullable | max:15 | unique:clients',
                    'status' => 'exists:client_statuses,id| nullable',
                    'socialmedia_links' => 'string | max:1000 | nullable',
                    'amo_client_id' => 'integer | nullable'
                ]);

                if ($validator->fails()) {
                    return response()->json(['result_client' => false, 'errors_client' => $validator->errors()]);
                }

                $client = ModelClient::find($params['uuid']);

                $amo = new AmoCrm();
                $res_amo = [];

                if(!empty($params['name'])) $client->name = $params['name'];
                if(!empty($params['second_name'])) $client->second_name = $params['second_name'];
                if(!empty($params['patronymic'])) $client->patronymic = $params['patronymic'];
                if(!empty($params['phone'])) $client->phone = $params['phone'];
                if(!empty($params['email'])) $client->email = $params['email'];
                if(!empty($params['comments'])) $client->comments = $params['comments'];
                if(!empty($params['referal_code'])) $client->referal_code = $params['referal_code'];
                if(!empty($params['status'])) $client->client_status_id = $params['status'];
                if(!empty($params['socialmedia_links'])) $client->socialmedia_links = $params['socialmedia_links'];
                if(!empty($params['amo_client_id'])) $client->amo_client_id = $params['amo_client_id'];

                if($client->amo_client_id && $client->second_name) {

                    $name = $client->name ?? '';
                    $full_name = $name . ' ' . $client->second_name;
                    $response_amo = $amo->update_contact( $client->amo_client_id, ['full_name' => $full_name], true );

                    if($response_amo['status']) {
                        $res_amo = ['result_amo_update_secondname'=>true];
                    } else {
                        $res_amo = ['result_amo_update_secondname'=>false, 'errors_amo_update_secondname' =>$response_amo ];
                    }
                }

                if($client->amo_client_id && $client->socialmedia_links) {

                    $response_amo = $amo->update_contact( $client->amo_client_id, ['socialmedia_links' => $client->socialmedia_links], true );

                    if($response_amo['status']) {
                        $res_amo = array_merge($res_amo, ['result_amo_update_socialmedia_links'=>true]);
                    } else {
                        $res_amo = array_merge($res_amo, ['result_amo_update_socialmedia_links'=>false, 'errors_amo_update_socialmedia_links' =>$response_amo ]);
                    }
                }

                $client->save();

                if($client) return response()->json(array_merge($res_amo, ['result' => true]));

                break;

            case 'drop' :
                if(!isset($params['id'])) return response('', 400);

                $client = new Client();
                $res = $client->drop($params['id']);
                break;

            case 'search' :
                $client = new Client($params);
                $res = $client->searchAdvanced($params);

                break;
            case 'default' :
                return response('метод не существует', 400);
        }

        return response()->json($res, 200);
    }

    public function statuses()
    {
        $arr_statuses = ClientStatus::all()->pluck('name', 'id')->toArray();
        return response()->json($arr_statuses, 200);
    }

    /**
     * Больше для тестирования ( без указания метода POST принудительно), но работает
     * Немного другой роут - /clients/add в файле admin_api.php
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {

        // TODO: перенсти валидацию?
        $validator = Validator::make(request()->all(), [
            'name' => 'required | string | max:36 ',
            'phone' => 'required | integer ',
            'email' => 'required | email ',
            'instagramm' => 'nullable | string ',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        if ($validator->fails()) return response()->json(['result' => false, 'errors' => $validator->errors()], 200);

        try {
            $amo_params = [
                'name' => $request->input('name') ?? false,
                'phone' => $request->input('phone') ?? false,
                'email' => $request->input('email') ?? false,
            ];

            $params = $amo_params;

            $amo = new AmoCrm();
            $res_amo = $amo->add_contact($amo_params, true);

            if (isset($res_amo['_embedded']['contacts'][0]['id'])) {
                $params['amo_client_id'] = $res_amo['_embedded']['contacts'][0]['id'];
            }

            $client = ModelClient::create($params);
            $res = [
                'result' => true,
                'new_client' => $client,
            ];
        } catch (QueryException $ex) {

            $res = [
                'result' => false,
                'errors' => $ex->errorInfo
            ];
        }

        return response()->json($res, 200);

    }

}
