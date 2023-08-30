<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Common;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\Http\Models\Catalog\Attribute;
use App\Http\Requests\Admin\RoleFormRequest;
use App\Http\Requests\Catalog\BrandFormRequest;
use App\Http\Requests\Admin\UserFormRequest;
use App\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

/**
 * Управление ролями
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class RolesController extends Controller
{
    /**
     * Список ролей
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        if(request()->ajax()){

            $dt = DataTables::eloquent(Role::query());


            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .='<a href = "'. route('manage.roles.edit',$data->id) .'" title = "Редактирование '. $data->name .'" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('destroy-roles'))
                    $buttons .='<a href = "'. route('manage.roles.edit',$data->id) .'" title = "Удаление '. $data->name .'" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.roles.index', [
            'title' => 'Роли'
        ]);
    }

    /**
     * Создание новой роли
     * @param RoleFormRequest $request
     * @return View
     */
    public function create(RoleFormRequest $request)
    {

        if (\request()->post()){

            $role = new Role();
            $role->name = trim($request->input('name'));
            $role->slug = Common::transliterate(trim($request->input('name')));
            $role->save();
            $permission = array_keys($request->input('permission'));
            $role->permissions()->sync($permission);

            toastr()->success('Роль добавлена!');
            return redirect()->route('manage.roles.index');
        }

        return view('admin.roles.add',[
            'title' => 'Добавление Роли',
            'permissions' => Permission::array()
        ]);
    }

    /**
     * Редактирование роли
     * @param RoleFormRequest $request
     * @param int $id
     * @return View
     */
    public function edit(RoleFormRequest $request, Role $role)
    {

        if (\request()->post()){

            if (empty($request->input('permission'))){
                toastr()->error('Не назначены права для роли!');
                return redirect()->back();
            }

            $permission = array_keys($request->input('permission'));
            $role->permissions()->sync($permission);

            $role->name = trim(\request()->input('name'));
            $role->save();

            $permission = array_keys($request->input('permission'));
            $role->permissions()->sync($permission);

            self::updateUserPermissions($role);

            toastr()->success('Роль сохранена!');
            return redirect()->route('manage.roles.index');
        }

        return view('admin.roles.edit',[
            'title' => 'Редактирование: '. @$role->name,
            'data' => $role,
            'permissions' => Permission::array()
        ]);
    }

    /**
     * Удаление роли
     * @param Role $role
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Role $role)
    {

        $role->delete();
        toastr()->success('Роль удалена!');
        return redirect()->route('manage.roles.index');
    }

    /**
     * Обновление прав пользователей при изменении роли
     * @param Role $role
     * @param int $role_id
     * @return bool
     */
    protected function updateUserPermissions(Role $role){

        $items = AdminUser::with('roles')->whereHas('roles',function ($q) use ($role){
            $q->where('role_id',$role->id);
        })->get();

        foreach ($items as $user ){
            $user->permissions()->sync($role->permissions()->get()->pluck("id"));
            $user->save();
        }

        return true;

    }
}
