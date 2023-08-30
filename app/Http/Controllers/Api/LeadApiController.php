<?php

namespace App\Http\Controllers\Api;

use App\Http\Classes\Message;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Lead;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use App\Traits\ClientsLeadsCommonApi;


class LeadApiController extends Controller
{
    use ClientsLeadsCommonApi;

    /**
     * Добавление новой сделки
     * @params Request
     * return json
     * @return JsonResponse
     * @throws ValidationException
     */
    public function create()
    {
        $res = self::AddLead(request()->all());
        return response()->json($res, 200);
    }

    /**
     * Добавление новой сделки
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
        } catch (Exception $e) {
            return 'Ошибка при выполнении запроса, см. Lara logs';
        }
    }

    /**
     * Редактирование Сделки
     * @params Request
     * return json
     */
    public function update(Request $request)
    {
        $params = $request->all();
        if (!is_array($params)) return response('', 400);
        $amo = new AmoCrm();

        $validator_lead = Validator::make($params, [
            'uuid' => 'required | string | max:36'
        ]);

        if($validator_lead->fails()) {
            $lead_response = ['result_lead' => false, 'errors_lead' => $validator_lead->errors()];
            return response()->json($lead_response);
        }

        $lead = Lead::find($params['uuid']);

        $params['amo_lead_id'] = ($lead) ? $lead->amo_lead_id : '';

        $validator_amo = $validator_amo = Validator::make($params, [
            'amo_lead_id' => 'required | integer',
            'name' => 'nullable | string| max:255',
            'paid_price' => 'nullable | integer ',
            'coupon' => 'nullable | string | max:36',
        ]);

        if ($validator_amo->fails()) {
            $amo_response = ['result_amo' => false, 'errors_amo' => $validator_amo->errors()];
            return response()->json($amo_response);
        }

        $pars = ['id'=> $params['amo_lead_id'] ];
        if(isset($params['name'])) $pars['name'] = $params['name'];
        if(isset($params['paid_price'])) $pars['paid_price'] = $params['paid_price'];
        if(isset($params['coupon'])) $pars['coupon'] = $params['coupon'];
        if(isset($params['paid_price'])) {
            if($params['paid_price'] == 0 ) $pars['state'] = 2;
            $lead->state_id = 2;
            $lead->client_num = (int) Lead::where('client_id',$lead->client_id)->max('client_num') + 1;
            $lead->save();
        }

        $amo = new AmoCrm();
        $res_amo = $amo->update_lead($pars,true);
        if($res_amo["status"] == "200") $return = ['result' => true];
        else $return = ['result_amo' => false, 'errors_amo' => $res_amo];
        return response()->json($return);
    }

    public function destroy()
    {

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

    /**
     * Отдельный метод связки контакта АМО и сделки АМО для API
     * @return JsonResponse
     */
    public function link()
    {
        $validator = Validator::make(request()->all(), [
            'lead_id' => 'required | string | max:36',
            'contact_id' => 'required ',
        ], Message::messages());

        if ($validator->fails()) {
            return response()->json([
                'result' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            if (!$lead = Lead::where('uuid', \request()->input('lead_id'))->first())
                return response()->json([
                    'result' => false,
                    'message' => 'Сделка не найдена'
                ], 400);

            $amo = new AmoCrm();

            if (!$resp = $amo->link_lead_contact( (int) $lead->amo_lead_id, (int)\request()->input('contact_id')))
                return response()->json([
                    'result' => false,
                    'message' => 'Ошибка обработки запроса от Амо',
                    'error' => $resp
                ], 400);

            $lead->amo_link_contact_id = (int)\request()->input('contact_id');
            $lead->save();

        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'message' => 'Ошибка обработки запроса',
                'error' => $e
            ], 400);
        }

        return response()->json([
            'result' => true,
            'message' => 'Контакт связан',
        ], 200);

    }


}
