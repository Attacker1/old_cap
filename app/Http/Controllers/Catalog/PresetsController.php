<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Catalog\Preset;
use App\Http\Requests\Catalog\PresetFormRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class PresetsController extends Controller
{
    /**
     * Список Пресетов
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        return view('admin.presets.index', [
            'title' => 'Пресеты'
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function dt(){

        $menu = Preset::all()->pluck('name', 'id');

        $dt = DataTables::eloquent(Preset::query());

        $dt->addColumn('params', function ($data) {
            if (!empty($data->params)){
                $html = '';
                foreach ($data->params['name'] as $k=>$v)
                    $html .= '<button class="btn btn-sm btn-outline-primary ml-1">' . $v . '</button>';

                return $html;

            }
            return '—';
        });

        $dt->addColumn('action', function ($data) {
            $buttons = '';
            if (auth()->guard('admin')->user()->hasPermission('catalog-manage'))
                $buttons .= '<a href = "' . route('admin.catalog.presets.edit', $data->id) . '" title = "Редактирование ' . $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a >';

            if (auth()->guard('admin')->user()->hasPermission('destroy-presets'))
                $buttons .= '<a href = "' . route('admin.catalog.presets.destroy', ['item' => $data->id]) . '" class = "ml-5 confirm-delete" title = "Удаление ' . $data->name . '" ><i class="fa far fa-trash-alt text-danger"></i></a >';

            return $buttons;
        });

        return $dt->make(true);
    }


    /**
     * Добавление Пресета
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.presets.add', [
            'title' => 'Добавление пресета',
        ]);
    }

    /**
     * @param PresetFormRequest $request
     * @return RedirectResponse
     */
    public function store(PresetFormRequest $request){

        $cat = new Preset();
        $cat->name = trim($request->input('name'));
        $cat->params = $request->input('params');
        $cat->save();
        toastr()->success('Пресет добавлен!');
        return redirect()->route('admin.catalog.presets.index');

    }

    /**
     * Редактирование пресета
     * @param Preset $item
     * @return Factory|View
     */
    public function edit(Preset $item)
    {

        return view('admin.presets.edit', [
            'title' => 'Редактирование пресета',
            'data' => $item,
        ]);
    }

    /**
     * @param PresetFormRequest $request
     * @param Preset $item
     * @return RedirectResponse
     */
    public function update(PresetFormRequest $request, Preset $item){

        $item->name = trim($request->input('name'));
        $item->params = $request->input('params');
        $item->save();

        toastr()->success('Пресет сохранен!');
        return redirect()->route('admin.catalog.presets.index');
    }


    /**
     * @param Preset $item
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Preset $item)
    {
        $item->delete();
        toastr()->error('Пресет удален!');
        return redirect()->back();
    }

}
