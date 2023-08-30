<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Common\MailTemplate;
use App\Http\Requests\Admin\MailFormRequest;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Управление шаблонами почты
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class MailController extends Controller
{
    /**
     * Список прав
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        if(request()->ajax()){

            $dt = DataTables::eloquent(MailTemplate::query());

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .='<a href = "'. route('mail.templates.update',$data->id) .'" title = "Редактирование '. $data->name .'" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('destroy-mail'))
                    $buttons .='<a href = "'. route('mail.templates.destroy',$data->id) .'" title = "Удаление '. $data->name .'" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.mail.index', [
            'title' => 'Управление шаблонами почты'
        ]);
    }

    /**
     * Добавление шаблона
     * @param MailFormRequest $request
     * @return View
     */
    public function create(MailFormRequest $request)
    {

        if (\request()->post()){

            $template = new MailFormRequest();
            $template->name = trim($request->input('name'));
            $template->description = trim($request->input('description'));
            $template->params = trim($request->input('params'));
            $template->text = trim($request->input('text')) ?? null;
            $template->html = $request->input('html') ?? null ;
            $template->save();

            toastr()->success('Шаблон добавлен!');
            return redirect()->route('mail.templates.index');
        }

        return view('admin.mail.create',[
            'title' => 'Новый почтовый шаблон',
        ]);
    }

    /**
     * Редактирование шаблона
     * @param MailFormRequest $request
     * @param int $id
     * @return View
     */
    public function update(MailFormRequest $request, $id)
    {

        $template = MailTemplate::find($id);

        if (\request()->post()){

            $template->name = trim($request->input('name'));
            $template->description = trim($request->input('description'));
            $template->params = json_encode($request->input('params')) ?? null;
            $template->text = trim($request->input('text')) ?? null;
            $template->html = $request->input('html') ?? null ;
            $template->updated_at = now();
            $template->save();

            toastr()->success('Шаблон сохранен!');
            return redirect()->route('mail.templates.index');
        }

        return view('admin.mail.update',[
            'title' => 'Редактирование: ' . @$template->name,
            'data' => $template,
        ]);
    }

    public function destroy( $id)
    {

        MailFormRequest::where('id',$id)->firstOrFail()->delete();

        toastr()->success('Шаблон почты удалены!');
        return redirect()->route('mail.templates.index');
    }
}
