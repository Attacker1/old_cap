<?php

namespace App\Http\Controllers\AdminClients;

use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;


class MainController extends Controller
{

    /**
     * Главная страница ЛК клиента
     *
     * @return View
     */
    public function index(Request $request)
    {
        //Пиксель Фейсбук
        /*if(url()->previous() == 'https://securepay.tinkoff.ru/' && $request->all() != []) {

            $validator = Validator::make(request()->all(), [
                'Success' => 'required | string | max:10',
                'Amount' => 'required | integer| max:1000000',
                'OrderId' => 'required | string | max:38'
            ], Message::messages());
            if (!$validator->fails()) {
                if ($request->Success == 'true' && substr($_GET['OrderId'], 0, 2) == 're') {
                    $fb_amount = substr($request->Amount, 0, -2) . '.' . substr($request->Amount, -2, 2);
                    $request->session()->flash('pay_status', true);
                    $request->session()->flash('pay_amount', $fb_amount);
                }
            }
        }*/

        return redirect()->route('admin-clients.orders.list');
    }
}