<?php

namespace App\Http\Controllers\Api\Sber;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\AdminClient\Questionnaire;
use App\Http\Models\Sber\SberAnketa;
use App\Http\Requests\Api\SberAnketaRequest;
use App\Services\AnswersAdapter;
use App\Services\QuestionAdapter;
use App\Traits\ClientsLeadsCommonApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\This;

class AnketsController extends Controller
{


    protected $model;
    protected $description = '';

    /**
     * AnketsController constructor.
     * @param $model
     */
    public function __construct(SberAnketa $model)
    {
        $this->model = $model;
    }

    /**
     *
     * @return Response
     */
    public function index()
    {
    }

    /**
     *
     * @return Response
     */
    public function create()
    {
    }

    /**
     *
     * @param $user_id
     * @param Request $request
     * @return Response
     */
    public function store($user_id, Request $request)
    {
    }

    /**
     *
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Обновление анкеты пользователя
     *
     * @param $user_id
     * @param SberAnketaRequest $request
     * @param int $id
     * @return Response
     */
    public function update($user_id)
    {
        $client = Common::getUser($user_id);

        // Признак первичной анкеты
        $input = \request()->input();
        $primary = empty($input[0]["primary"]) ? false : true;

        if (empty($client->error)) {

            if ($anketa = $this->model->whereClientUuid($client->model->uuid)->first() && $primary) {
                $this->model->whereClientUuid($client->model->uuid)->update(["data" => $input]);
            } else {
                $anketa = new SberAnketa();
                $anketa->client_uuid = $client->model->uuid;
                $anketa->data = \request()->input();
                $anketa->primary = $primary;
                $anketa->save();
            }

            self::saveToAnketa($client->model->uuid, $input, $primary);
        } else
            $this->description = $client->error;

        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => $this->description ?? '',
        ])->setStatusCode(Response::HTTP_OK);
    }

    private function saveToAnketa($client_uuid, $request, $primary)
    {

        $type = (string)$primary;
        Log::channel('sber_clients')->info("ТИП АНКЕТЫ: $type ");

        try {

            $data = (new AnswersAdapter())->setSber($request);
            $save_data = json_decode(json_encode($data));

            if ($primary && $anketa = Questionnaire::where('client_uuid', $client_uuid)->orderByDesc("created_at")->first())
                $anketa->update(["data" => $save_data]);
            else {
                $anketa = new Questionnaire();
                $anketa->client_uuid = $client_uuid;
                $anketa->data = $save_data;
                $anketa->source = $primary ? 'sber' : 'sber_re';
                $anketa->save();
            }


        } catch (\Exception $e) {
            Log::channel('sber_clients')->info("АНКЕТА Сохранение: " . $e->getMessage());
        }

    }

    /**
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
