<?php

namespace App\Http\Controllers\Common;

use App\Http\Classes\Message;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\LeadRef;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


/**
 *
 * Class LeadController
 * @package App\Http\Controllers\Common
 */
class TestController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function amoTriggers()
    {

        if (request()->post()) {

            $validator = Validator::make(request()->all(), [
                'lead_id' => 'required | string | max:36 ',
                'state_id' => 'required | integer ',
            ], Message::messages());

            if ($validator->fails()) {
                return response()->json([
                    'result' => false,
                    'message' => $validator->errors()
                ], 400);
            }

            try {
                if (!$lead = Lead::where('uuid', request()->input('lead_id'))->first()) {
                    toastr()->error('Сделка не найдена');
                    return redirect()->back();
                }
                $lead->state_id = request()->input('state_id');
                $lead->save();
            }

            catch (\Exception $e){
                toastr()->error('Что-то пошло не так!' . $e);
                return redirect()->back();
            }

            toastr()->success('Статус назначен');
            return redirect()->back();

        }

        return view('admin.test.amo-triggers',[
            'title' => 'Тестирование триггеров',
            'states' => LeadRef::whereNull('parent_id')->orderBy('id')->pluck('name','id'),
            'data' => $data ?? false,
        ]);
    }

    /**
     * @return bool
     */
    public function php(){

        return phpinfo();

    }



}
