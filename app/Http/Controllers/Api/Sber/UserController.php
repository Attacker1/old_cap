<?php

namespace App\Http\Controllers\Api\Sber;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Bonus;
use App\Services\ClientService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $description = false;

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param $user_id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $user_id)
    {

        $client = Common::getUser($user_id);

        if(empty($client->error)) {

            $bonuses = Bonus::where('client_id',$client->model->uuid)->first();
            $ref_code = !empty($bonuses->promocode) ? $bonuses->promocode : $client->model->promo_code;

            $data = [
                'id' => @$client->model->uuid,
                'name' => @!empty($client->model->name) ? $client->model->name : '',
                'address' => !empty($client->model->address) ? @$client->model->address : '',
                'refferal_code' => !empty($ref_code) ? $ref_code : "",
                'bonuses' => !empty($bonuses->points) ? $bonuses->points : 0
            ];
        }
        else
            $this->description = $client->error;

        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'user' => $data ?? '',
            'description' => $this->description ?? '',
        ],Response::HTTP_OK);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), ['phone' => 'required | string | regex:/[0-9_]+/i '], Message::messages());

        if ($validator->fails())
            return \response()->json(['status' => 'error','description' => $validator->errors()],Response::HTTP_BAD_REQUEST);

        $client = (new ClientService())
            ->createOrUpdate($request->input('phone'),['name' => $request->input('name')])
            ->amoFirstOrNew()
            ->get();

        return response()
            ->json([
                'status' => 'ok',
                'description' => '',
                'user_id' => $client->uuid
            ],Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $user_id
     * @return Response
     */
    public function update(Request $request, $user_id)
    {

        Log::channel('sber_clients')->info(json_encode($request));
        Log::channel('sber_clients')->info('CLIENT UUID :' . $user_id);
        $client = Common::getUser($user_id);
        if(empty($client->error)) {

            $params = [
                'name' => @$request->input('fullname'),
                'address' => @$request->input('address'),
            ];
            $service = new ClientService();
            $service->createOrUpdate($client->model->phone,$params)
                ->amoUpdateClient()
                ->get();
        }
        else
            $this->description = $client->error;

        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => $this->description ?? '',
        ],Response::HTTP_OK);

    }

}
