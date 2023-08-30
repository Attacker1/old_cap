<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Common;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\Http\Requests\Admin\PermissionFormRequest;
use App\Http\Requests\Admin\RoleFormRequest;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Управление правами
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class PermissionController extends Controller
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

            $dt = DataTables::eloquent(Permission::query());

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .='<a href = "'. route('manage.permissions.edit',$data->id) .'" title = "Редактирование '. $data->name .'" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('destroy-roles'))
                    $buttons .='<a href = "'. route('manage.permissions.edit',$data->id) .'" title = "Удаление '. $data->name .'" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.permissions.index', [
            'title' => 'Права в системе'
        ]);
    }

    /**
     * Создание новых прав
     * @param Permission $request
     * @return View
     */
    public function create(PermissionFormRequest $request)
    {

        if (\request()->post()){

            $permission = new Permission();
            $permission->name = trim($request->input('name'));
            $permission->slug = trim($request->input('slug'));
            $permission->save();

            toastr()->success('Права добавлены!');
            return redirect()->route('manage.roles.index');
        }

        return view('admin.permissions.add',[
            'title' => 'Добавление Прав',
        ]);
    }

    /**
     * Редактирование прав
     * @param PermissionFormRequest $request
     * @param Permission $permission
     * @return View
     */
    public function edit(PermissionFormRequest $request, Permission $permission)
    {

        if (\request()->post()){

            $permission->name = trim(\request()->input('name'));
            $permission->slug = trim(\request()->input('slug'));
            $permission->updated_at = now();
            $permission->save();

            toastr()->success('Права сохранены!');
            return redirect()->route('manage.permissions.index');
        }

        return view('admin.permissions.edit',[
            'title' => 'Редактирование: '. @$permission->name,
            'data' => $permission,
        ]);
    }

    public function destroy( Permission $permission)
    {

        $permission->delete();
        toastr()->success('Права удалены!');
        return redirect()->route('manage.permissions.index');
    }
}
