<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Catalog\Brand;
use App\Http\Models\Catalog\Category;
use App\Http\Requests\Catalog\BrandFormRequest;
use App\Http\Requests\Catalog\CategoryFormRequest;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BrandsController extends Controller
{
    /**
     * Список брендов
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        $menu = Brand::all()->pluck('name','id');

        if(request()->ajax()){

            $dt = DataTables::eloquent(Brand::with('users'));

            $dt->addColumn('user', function ($data) {
                return @$data->users->name ?? '—';
            });

            $dt->addColumn('visible', function ($data) {
                if ($data->visible == 1)
                    return '<button class="btn btn-outline-danger btn-sm px-2" title="Выключено"><i class="fa fa-eye-slash" aria-hidden="true"></i></button >';

                return '<button class="btn btn-outline-success btn-sm px-2" title="Включено"><i class="fa fa-eye" aria-hidden="true"></i></button >';

            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .= '<a href = "' . route('admin.catalog.brands.edit',$data->id) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                $buttons .= '<a href = "#" class=" ml-5 modal-delete" title = "Удалить запись '. $data->name . '" data-id="'.route('admin.catalog.brands.destroy',$data->id).'" data-route-destroy="'.route('clients.delete',$data->uuid).'"><i class="fa far fa-trash-alt text-danger"></i></a></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.brands.index', [
            'title' => 'Управление Брендами'
        ]);
    }

    /**
     * Создание нового бренда
     * @param BrandFormRequest $request
     * @return View
     */
    public function create(BrandFormRequest $request)
    {

        if (\request()->post()){

            $brand = new Brand();
            $brand->name = trim(\request()->input('name'));
            $brand->created_at = now();
            $brand->updated_at = now();
            $brand->save();

            toastr()->success('Бренд добавлен!');
            return redirect()->route('admin.catalog.brands.index');
        }

        return view('admin.brands.add',[
            'title' => 'Добавление бренда',
        ]);
    }

    /**
     * Редактирование бренда
     * @param BrandFormRequest $request
     * @param Brand $brand
     * @return View
     */
    public function edit(BrandFormRequest $request, Brand $brand)
    {

        if (\request()->post()){

            $brand->name = trim(\request()->input('name'));
            $brand->created_at = now();
            $brand->updated_at = now();
            $brand->save();

            toastr()->success('Бренд сохранен!');
            return redirect()->route('admin.catalog.brands.index');
        }

        return view('admin.brands.edit',[
            'title' => 'Редактирование: '. $brand->name,
            'data' => $brand
        ]);
    }

    public function destroy( Brand $brand)
    {

        try {
            $brand->delete();
        } catch (Exception $e) {
        }

        toastr()->success('Бренд удален!');
        return redirect()->route('admin.catalog.brands.index');
    }
}
