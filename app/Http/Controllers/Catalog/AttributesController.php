<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Catalog\Preset;
use App\Http\Models\Common\ClientSettings;
use App\Http\Requests\Catalog\AttributeFormRequest;
use Auth;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class AttributesController extends Controller
{
    /**
     * Список Характеристик
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        $presets = Preset::array();

        if(request()->ajax()){

            $dt = DataTables::eloquent(Attribute::with('presets'));

            $dt->addColumn('params', function ($data) use ($presets) {
                if (!empty($data->params)){
                    $html = '';
                    foreach ($data->params['value'] as $k=>$v)
                        $html .= '<button class="btn btn-sm btn-outline-info ml-1 mb-1">' . $v . '</button>';

                    return $html;

                }
                return '—';
            });

            $dt->addColumn('preset', function ($data) {

                return '<button class="btn btn-outline-primary btn-sm px-2">' . ($data->presets->name ?? '—') . '</button >';
            });

            $dt->addColumn('visible', function ($data) {
                if ($data->visible == 1)
                    return '<button class="btn btn-outline-danger btn-sm px-2" title="Выключено"><i class="fa fa-eye-slash" aria-hidden="true"></i></button >';

                return '<button class="btn btn-outline-success btn-sm px-2" title="Включено"><i class="fa fa-eye" aria-hidden="true"></i></button >';
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                if (auth()->guard('admin')->user()->hasPermission('catalog-manage'))
                    $buttons .= '<a href = "' . route('admin.catalog.attributes.edit',$data->id) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a>';

                if (auth()->guard('admin')->user()->hasPermission('catalog-cat-destroy'))
                    $buttons .= '<a href = "#" class="ml-5 modal-delete" title = "Удалить запись '. $data->name . '" data-id="'.route('admin.catalog.attributes.destroy',$data->id).'" data-route-destroy="'.route('clients.delete',$data->uuid).'" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.attributes.index', [
            'title' => 'Характеристики товаров'
        ]);
    }

    /**
     * Создание новой категории
     * @param AttributeFormRequest $request
     * @return View
     */
    public function create(AttributeFormRequest $request)
    {

        if (\request()->post()){

            $item = new Attribute();
            $item->name = \request()->input('name');
            $item->params = $request->input('params');
            $item->preset_id = $request->input('preset_id');
            $item->save();
            toastr()->success('Характеристика добавлена!');
            return redirect()->route('admin.catalog.attributes.index');
        }

        return view('admin.attributes.add',[
            'title' => 'Добавление характеристики',
            'presets' => Preset::whereNotIn('id',[1])->array(),
        ]);
    }

    /**
     * Редактирование Характеристик
     * @param AttributeFormRequest $request
     * @param Attribute $item
     * @return View
     */
    public function edit(AttributeFormRequest $request, Attribute $item)
    {

        if(request()->post()){

            $item->name = $request->input('name');
            $item->params = $request->input('params');
            $item->preset_id = $request->input('preset_id');
            $item->save();

            toastr()->success('Характеристика сохранена!');
            return redirect()->route('admin.catalog.attributes.index');
        }

        return view('admin.attributes.edit', [
            'title' => 'Ред.: ' . @$item->name,
            'data' => $item,
            'params' => $item->params ?? false,
            'presets' => Preset::array(),
        ]);
    }

    /**
     * Удаление Характеристики
     * @param Attribute $item
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Attribute $item)
    {

        $item->categories()->detach();
        $item->delete();
        toastr()->error('Характеристика удалена!');
        return redirect()->route('admin.catalog.attributes.index');

    }

}
