<?php

namespace App\Http\Controllers\Common;

use App\Http\Classes\Message;
use App\Mail\Test;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

/**
 * Class MailController
 * @package App\Http\Controllers\Common
 */
class MailController extends Controller
{

    /** Отправка тестового сообщения
     * @return Factory|RedirectResponse|View
     */
    public function test(){

        if (request()->post()) {

            $validator = Validator::make(request()->all(), [
                'to' => 'required | email '
            ], Message::messages());

            if ($validator->fails()) {
                toastr()->error('Ошибка валидации!');
                return redirect()->back();
            }

            // Предпросмотр шаблона без отправки
            //return (new App\Mail\Test())->render();

            try {
                Mail::to(request()->input('to'))->send(new Test());
                toastr()->success('Отправлено!');
            } catch (\Exception $e) {
                toastr()->error('Ошибка отправки');
            }

            return redirect()->back();

        }

        return view('admin.mail.test', [
            'title' => 'Тест почтовой отправки!'
        ]);

    }
}