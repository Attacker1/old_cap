<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\Admin\AdminUser;
use App\Http\Requests\Admin\TagFormRequest;
use Illuminate\View\View;
use Exception;
use App\Http\Models\Catalog\Tags;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Управление тегами
 * Class TagController
 * @package App\Http\Controllers\Admin
 */
class TagController extends Controller
{
    /**
     * Список тегов
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {
        if(request()->ajax()){

            $dt = DataTables::eloquent(Tags::query());

            $dt->addColumn('role', function ($data) {
                return '<span style="position:absolute;top:0px;left:0px;width: 100%;height: 100%;background-color: ' . $data->color . '"></span>';
            });


            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .='<a href = "'. route('manage.tags.edit',$data->id) .'" title = "Редактирование '. $data->name .'" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('manage-users-destroy'))
                    $buttons .='<a href = "'. route('manage.tags.destroy', $data->id) .'" title = "Удаление '. $data->name .'"  class="ml-5" onclick="return confirm(\'Удалить тег?\')"><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.tags.index', [
            'title' => 'Теги'
        ]);
    }

    /**
     * Создание новых тегов
     * @return View
     */
    public function create()
    {

        if (\request()->post()){
            $tag = new Tags();
            $tag->name = trim(\request()->input('name'));
            $tag->color = trim(\request()->input('color'));
            $tag->type = trim(\request()->input('type'));
            $tag->created_at = now();
            $tag->updated_at = now();
            $tag->save();

            toastr()->success('Тег добавлен!');
            return redirect()->route('manage.tags.index');
        }

        return view('admin.tags.add',[
            'title' => 'Добавление Тега'
        ]);
    }

    /**
     * Редактирование тегов
     * @param Tags $tag
     * @return View
     */
    public function edit(int $tagId)
    {
        $tag = Tags::with('leads')->find($tagId);

        if (\request()->post()){

            $tag->name = trim(\request()->input('name'));
            $tag->color = trim(\request()->input('color'));
            $tag->type = trim(\request()->input('type'));
            $tag->created_at = now();
            $tag->updated_at = now();
            $tag->save();

            toastr()->success('Тег сохранен!');
            return redirect()->route('manage.tags.index');
        }

        return view('admin.tags.edit',[
            'title' => 'Редактирование: '. $tag->name,
            'data' => $tag,
        ]);

    }

    public function destroy( int $id)
    {
//        if(count($tag->leads()->get()) == 0) {
//
//        } else {
//            //
//        }
        $tag = Tags::with('leads')->find($id);
        $tag->leads()->sync([]);
        $tag->delete();
        toastr()->error('Тег удален!');
        return redirect()->route('manage.tags.index');
    }
}
