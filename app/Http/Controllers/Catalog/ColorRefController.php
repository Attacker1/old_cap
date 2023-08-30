<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Classes\Common;
use App\Http\Models\Catalog\ColorsRef;
use App\Http\Requests\Catalog\ColorFormRequest;
use Doctrine\DBAL\Query\QueryException;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class ColorRefController extends Controller
{
    /**
     * Список Цветов каталога
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        if (\request()->ajax()){
            $sql = ColorsRef::query();

            $dt = DataTables::eloquent($sql);

            $dt->addColumn('hex', function ($data) {
                if ($data->hex)
                    return '<div class="filloption" style="background-color:'.$data->hex.'"></div> '  .$data->hex;
            });

            $dt->addColumn('action', function ($data) {

                $hidden_buttons = false;

                $visible[] = '<a href = "'. route('admin.color.edit',$data->id) .'" title = "Редактирование цвета: '. $data->name .'" ><i class="ml-1 feather icon-edit"></i></a></a >';
                $hidden_buttons[] .='<a href = "'. route('admin.color.destroy',$data->id) .'" class="confirm-delete dropdown-item" title = "Удаление '. $data->name .'" ><i class="feather icon-trash-2"></i> Удаление</a>';

                return Common::actionMenu($visible,$hidden_buttons);

            });

            return $dt->make(true);
        }

        return view('admin.color.index', [
            'title' => 'Цвета вещей каталога'
        ]);
    }




    /**
     * Добавление Цвета
     * @return Factory|View
     */
    public function create()
    {
        return view('admin.color.add', [
            'title' => 'Добавление цвета',
        ]);
    }

    /**
     * @param ColorFormRequest $request
     * @return RedirectResponse
     */
    public function store(ColorFormRequest $request){

        try {
            $data = $request->except(['_token']);
            if ($res =  ColorsRef::insert($data))
                toastr()->success('Цвет добавлен!');

        }
        catch (QueryException $e){
            var_dump($e);
        }


        return redirect()->route('admin.color.index');

    }

    /**
     * Редактирование Цвета
     * @param int $id
     * @return Factory|View
     */
    public function edit(int $id)
    {
        $item = ColorsRef::findOrFail($id);

        return view('admin.color.edit', [
            'title' => 'Редактирование цвета',
            'data' => $item,
        ]);
    }

    /**
     * @param ColorFormRequest $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(ColorFormRequest $request, int $id){

        $item = ColorsRef::findOrFail($id);

        try {
            $data = $request->except(['_token']);
            if ($res =  $item->update($data))
                toastr()->success('Цвет изменен!');

        }
        catch (QueryException $e){
            var_dump($e);
        }

        toastr()->success('Цвет сохранен!');
        return redirect()->route('admin.color.index');
    }


    /**
     * Удаление цвета
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy(int $id)
    {

        ColorsRef::where('id', $id)->delete();
        toastr()->error('Цвет удален!');
        return redirect()->route('admin.color.index');
    }

}
