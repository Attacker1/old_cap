<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Admin\Permission;
use App\Http\Models\Admin\Role;
use App\Http\Models\AdminClient\Client;
use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Catalog\CategoriesTranslator;
use App\Http\Models\Catalog\Category;
use App\Http\Models\Catalog\Note;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Lead;
use App\Http\Requests\Catalog\CategoryFormRequest;
use App\User;
use Auth;
use Bnb\Laravel\Attachments\Attachment;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CategoriesController
 * @package App\Http\Controllers\Catalog
 */
class CategoriesController extends Controller
{
    /**
     * Список категорий
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        $menu = Category::all()->pluck('name','id');

        if(request()->ajax()){

            $dt = DataTables::eloquent(Category::with(['users','attributes'])->whereNotIn('id',[1]));

            $dt->addColumn('user', function ($data) {
                return @$data->users->name ?? '—';
            });

            $dt->addColumn('parent', function ($data) use ($menu){
                return !empty($data->parent_id) ? $menu[$data->parent_id] : '—' ;
            });

            $dt->addColumn('attributes', function ($data){
                if (!empty($data->attributes)){
                    $html ='';
                    foreach ($data->attributes as $k=>$v)
                        $html .= '<button class="btn btn-outline-info btn-sm px-1 ml-1" >'.$v->name.'</button >';
                }
                return $html ?? '—';
            });

            $dt->addColumn('visible', function ($data) {
                if ($data->visible == 1)
                    return '<button class="btn btn-outline-danger btn-sm px-2" title="Выключено"><i class="fa fa-eye-slash" aria-hidden="true"></i></button >';

                return '<button class="btn btn-outline-success btn-sm px-2" title="Включено"><i class="fa fa-eye" aria-hidden="true"></i></button >';

            });

            $dt->addColumn('action', function ($data) {

                $buttons = '';
                $buttons .= '<a href = "' . route('admin.catalog.categories.edit',$data->id) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                $buttons .= '<a href = "'.route('admin.catalog.categories.destroy',$data->id).'" class=" ml-5 modal-delete" title = "Удалить запись '. $data->name . '" data-id="'.route('admin.catalog.categories.destroy',$data->id).'" data-route-destroy="'.route('clients.delete',$data->uuid).'"><i class="fa far fa-trash-alt text-danger"></i></a></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.categories.index', [
            'title' => 'Управление категориями'
        ]);
    }

    /**
     * Создание новой категории
     * @param CategoryFormRequest $request
     * @return View
     */
    public function create(CategoryFormRequest $request)
    {

        if (\request()->post()){

            $cat = new Category();
            $cat->name = \request()->input('name');
            $cat->parent_id = !empty(\request()->input('parent_id')) ? \request()->input('parent_id') : null;
            $cat->visible = !empty(\request()->input('visible')) ? \request()->input('visible') : null;
            $cat->slug = str_replace(' ', '', \request()->input('slug'));
            $cat->user_id = auth()->guard('admin')->user()->id;
            $cat->created_at = now();
            $cat->updated_at = now();
            $cat->save();
            self::attributes($request,$cat);

            toastr()->success('Категория добавлена!');
            return redirect()->route('admin.catalog.categories.index');
        }

        return view('admin.categories.add',[
            'title' => 'Добавление категории',
            'menu' => Category::orderBy('name','asc')->get()->pluck('name','id'),
            'attributes' => Attribute::all()->pluck('name','id')
        ]);
    }

    /**
     * Редактирование страницы
     * @param CategoryFormRequest $request
     * @param Category $category
     * @return View
     */
    public function edit(CategoryFormRequest $request, Category $category)
    {

        if(request()->post()){

            if ($request->input('parent_id') == $category->id){
                toastr()->error('Не может быть родительским пунктом');
                return redirect()->back();
            }

            $category->name = $request->input('name');
            if (!empty($request->input('parent_id')))
                $category->parent_id = $request->input('parent_id');
            $category->slug = str_replace(' ', '', $request->input('slug'));
            $category->visible = !empty(\request()->input('visible')) ? $request->input('visible') : null;
            $category->user_id = auth()->guard('admin')->user()->id;
            $category->created_at = now();
            $category->updated_at = now();
            $category->save();
            self::attributes($request,$category);

            toastr()->success('Категория отредактирована!');
            return redirect()->route('admin.catalog.categories.index');
        }

        return view('admin.categories.edit', [
            'title' => 'Редактирование категории',
            'menu' => Category::orderBy('name','asc')->get()->pluck('name','id'),
            'data' => $category,
            'attributes' => Attribute::all()->pluck('name','id'),
            'assign_attributes' => $category->attributes()->get()->pluck('id')->toArray()
        ]);
    }

    /**
     * Удаление Категории
     * @param Category $category
     * @return RedirectResponse|Redirector
     * @throws Exception
     */
    public function destroy(Category $category)
    {
        $category->delete();
        toastr()->error('Категория удалена!');
        return redirect()->route('admin.catalog.categories.index');

    }

    // Добавление атрибутов

    /**
     * @param $request
     * @param $cat
     */
    protected function attributes($request, $category){

        if ($request->input('attribute_id')) {
            $category->attributes()->sync($request->input('attribute_id'));
        }
        else{
            $category->attributes()->detach();
        }
    }

    /**
     * Импорт Сопоставлений категорий из МС склада в TheCap
     * @return Factory|RedirectResponse|View
     * @throws ValidationException
     */
    public function import()
    {

        if (request()->post()) {

            if (request()->hasFile('xlsFile')) {

                $this->validate(request(), [
                    'xlsFile' => 'required | max:5000000',
                ]);

                $file = request()->file('xlsFile');

                if (!($file->isValid())) {

                    toastr()->error('Не все поля заполнены корректно');
                    return redirect()->back()->withErrors(['xlsFile' => 'Ошибка загруженного файла'])->withInput();
                }

                $path = request()->file('xlsFile')->getRealPath();
                $data = Excel::load($path)->get();
                dd($data);
                if ($data->count()) {

                    foreach ($data as $key => $value) {
                        if ($value->ms)
                        $data = [
                            'ms_name' => $value->capsula ?? null ,
                            'cap_name' => $value->capsula ?? '',
                        ];


                        CategoriesTranslator::updateOrCreate([
                            'ms_name' => $value->capsula
                        ],$data);

                    }
                    toastr()->success('Импорт успешно завершен!');
                    return redirect()->back();
                }
            }
            toastr()->error('Не все поля заполнены корректно');
            return redirect()->back();
        }
        return view('admin.categories.import', [
            'title' => 'Импорт сопоставлений категорий'
        ]);
    }
}
