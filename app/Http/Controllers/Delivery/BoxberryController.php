<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Delivery;
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
class BoxberryController extends Controller
{

    /**
     * @return bool
     */
    public function index(){

        return view('admin.boxberry.index',[
            'title' => 'Тестирование BoxBerry',
        ]);

    }

    /**
     * Детальная информация по заказу
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function info(){

        if(!$order_id = request()->input('order_id')) {
            toastr()->error('Не указан ID заказа');
            return redirect()->back();
        }

        $lead = Lead::where('amo_lead_id',$order_id)->first();
        $api = BoxberryApi::statusOrder(request()->input('order_id'));

        return view('admin.boxberry.info',[
            'title' => 'Информация по заказу/сделке: ' . request()->input('order_id'),
            'data' => $lead,
            'api' => $api,
        ]);

    }

    /**
     * Удаление заказа
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function destroy(){

        if(!$order_id = request()->input('order_id')) {
            toastr()->error('Не указан ID заказа');
            return redirect()->back();
        }

        $api = new BoxberryApi();
        if ($result = $api->deleteOrder(request()->input('order_id'))){
            toastr()->success('Заказ удален из BOXBERRY:' . request()->input('order_id'));
            return redirect()->back();
        }

        toastr()->error('Не удалось далить заказ BOXBERRY:' . request()->input('order_id'));
        return redirect()->back();

    }

    /**
     * Список заказов в работе
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function deliveringOrders(){

        $api = new BoxberryApi;

        return view('admin.boxberry.delivering',[
            'title' => "Список доставляющихся заказов",
            'api' => $api->deliveringOrders(),
        ]);
    }

    /**
     * Список заказов в работе
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function amo(){

        $api = new BoxberryApi;
        // $lead = Lead::whereAmoLeadId(29862031)->first();
        //dd($api->getPvzByAddress("625033, Тюмень г, Михаила Сперанского ул, д.17"));
        //dd($api->createOrder($lead));

        if (request()->post()){

            if ($api->amoToBoxberry()){
                toastr()->success('Заказы обработаны');
                return redirect()->back();
            }

        }

        return view('admin.boxberry.amo',[
            'title' => "Список Заказов АМО для ББ",
            'api' =>  $api->getAmoLeads(31798657),
        ]);
    }
}
