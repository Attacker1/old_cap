<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Models\Catalog\CategoriesTranslator;
use App\Http\Requests\Catalog\CategoriesTranslatorRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CategoriesTranslatorController extends Controller
{
    protected $pageModel;

    /**
     * CategoriesTranslatorController constructor.
     * @param CategoriesTranslator $model
     */
    public function __construct(CategoriesTranslator $model)
    {
        $this->pageModel = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        if(request()->ajax()) {
            $dt = DataTables::eloquent(CategoriesTranslator::query());
            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .= '<a href = "' . route('category.translator.edit', $data->id) . '" title = " Редактировать ' . $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                $buttons .= '<a href="#" data-url = "'.route('category.translator.destroy', $data->id).'" class="delete-item ml-5 " title = "Удалить запись ' . $data->ms_name . '" ><i class="fa far fa-trash-alt text-danger"></i></a></a >';

                return $buttons;
            });
            return $dt->make(true);
        }

        return view("admin.category-translator.index", [
            'title' => 'Управление Сопоставлениями'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.category-translator.add", [
            'title' => 'Новое сопоставление '
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except(['_token']);
        CategoriesTranslator::create($data);
        toastr()->success('Добавлено новое сопоставление');
        return redirect()->route('category.translator.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = CategoriesTranslator::find($id);
        return view("admin.category-translator.edit", [
            'title' => 'Редактировать сопоставление ' . $item->ms_name,
            'data' => $item
        ]);
    }

    public function update(CategoriesTranslatorRequest $request, $id)
    {
        $page = $this->pageModel->findOrFail($id);
        $page->update($request->validated());
        toastr()->success('Сохранено сопоставление');
        return redirect()->route('category.translator.index');
    }


    public function destroy($id)
    {

        CategoriesTranslator::find($id)->delete();
        toastr()->error('Удалено сопоставление');
        return redirect()->route('category.translator.index');
    }
}
