<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Common;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Common\LeadRef;
use App\Http\Requests\Admin\LeadRefRoleFormRequest;
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
 * Управление статусами сделок (по ролям
 * Class RolesController
 * @package App\Http\Controllers\Admin
 */
class LeadRefRolesController extends Controller
{
    /**
     * Список
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        if(request()->ajax()){

            $dt = DataTables::eloquent(Role::query());

            $dt->addColumn('action', function ($data) {

                return '<a href = "'. route('manage.leadref.edit',$data->id) .'" title = "Редактирование '. $data->name .'" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

            });

            return $dt->make(true);
        }

        return view('admin.leadref.index', [
            'title' => 'Управление доступностью Статус-сделок'
        ]);
    }

    /**
     * Редактирование
     * @param LeadRefRoleFormRequest $request
     * @param int $id
     * @return View
     */
    public function edit(LeadRefRoleFormRequest $request, $id)
    {

        $role = Role::with('leadref')->find($id);

        if (\request()->post()){

            if (empty($request->input('state'))){
                toastr()->error('Не назначены статусы для роли!');
                return redirect()->back();
            }

            $states = array_keys($request->input('state'));
            $role->leadref()->sync($states);

            $role->save();

            $states = array_keys($request->input('state'));
            $role->leadref()->sync($states);

            toastr()->success('Сохранено!');
            return redirect()->route('manage.leadref.index');
        }

        return view('admin.leadref.edit',[
            'title' => 'Редактирование статусов для : '. @$role->name,
            'data' => $role,
            'states' => LeadRef::orderBy('id')->pluck('name','id')
        ]);
    }

}
