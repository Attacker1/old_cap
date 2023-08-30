<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\Http\Models\Catalog\Attribute;
use App\Http\Requests\Catalog\BrandFormRequest;
use App\Http\Requests\Admin\UserFormRequest;
use App\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    /**
     * Список пользователей
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        if(request()->ajax()){

            $dt = DataTables::eloquent(AdminUser::query());

            $dt->addColumn('role', function ($data) {
                $roles = $data->roles()->get()->pluck('name');
                $html = '';
                foreach ($roles as $k=>$v){
                    $html .= '<button class="btn btn-outline-info btn-sm px-2 ml-1">' . $v . '</button >';
                }
                return $html;
            });

            $dt->addColumn('disabled', function ($data) {
                if ($data->disabled == 1)
                    return '<i class="fa fa-ban text-danger" aria-hidden="true"></i>';

                return '<i class="fa fa-check text-success" aria-hidden="true"></i>';


            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .='<a href = "'. route('admin.manage.users.edit',$data->id) .'" title = "Редактирование '. $data->name .'" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

                if (auth()->guard('admin')->user()->hasPermission('manage-users-destroy'))
                    $buttons .='<a href = "'. route('admin.manage.users.destroy',$data->id) .'" title = "Удаление '. $data->name .'"  class="ml-5" onclick="return confirm(\'Удалить пользователя?\')"><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.users.index', [
            'title' => 'Пользователи'
        ]);
    }

    /**
     * Создание нового пользователя
     * @param UserFormRequest $request
     * @return View
     */
    public function create(UserFormRequest $request)
    {

        if (\request()->post()){

            $user = new AdminUser();
            $user->name = trim(\request()->input('name'));
            $user->amo_name = trim(\request()->input('amo_name')) ?? null;
            $user->email = trim(\request()->input('email'));
            $user->password = bcrypt(\request()->input('password'));
            $user->disabled = trim(\request()->input('disabled'));
            $user->created_at = now();
            $user->updated_at = now();
            $role = Role::where('id',\request()->input('role_id'))->first();
            $user->save();
            $user->roles()->sync($role);
            $user->permissions()->sync($role->permissions()->get()->pluck("id"));
            $user->save();

            toastr()->success('Пользователь добавлен!');
            return redirect()->route('admin.manage.users.index');
        }

        return view('admin.users.add',[
            'title' => 'Добавление пользователя',
            'roles' => Role::array(),
        ]);
    }

    /**
     * Редактирование пользователя
     * @param UserFormRequest $request
     * @param AdminUser $user
     * @return View
     */
    public function edit(UserFormRequest $request, $user_id)
    {

        // TODO: UserFormRequest $request не отрабатывает уникальный email

        $user = AdminUser::find($user_id);

        if (\request()->post()){

            $user->name = trim(\request()->input('name'));
            $user->amo_name = trim(\request()->input('amo_name')) ?? null;
            $user->email = trim(\request()->input('email'));
            $user->disabled = trim(\request()->input('disabled'));
            if (!empty(\request()->input('password')))
                $user->password = bcrypt(\request()->input('password'));
            $user->created_at = now();
            $user->updated_at = now();
            $role = Role::where('id',(int) \request()->input('role_id'))->first();
            $user->roles()->sync($role);

            $user->permissions()->sync($role->permissions()->get()->pluck("id"));
            $user->save();

            toastr()->success('Пользователь сохранен!');
            return redirect()->route('admin.manage.users.index');
        }

        return view('admin.users.edit',[
            'title' => 'Редактирование: '. $user->name,
            'data' => $user,
            'roles' => Role::array(),
        ]);
    }

    public function destroy( AdminUser $user )
    {

    }
}
