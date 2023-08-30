<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Classes\Common;
use App\Http\Controllers\Classes\AmoCrm;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Catalog\Brand;
use App\Http\Models\Catalog\Category;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Catalog\Tags;
use App\Http\Models\Common\Lead;
use App\Http\Requests\Catalog\ProductFormRequest;
use Bnb\Laravel\Attachments\HasAttachment;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use Bnb\Laravel\Attachments\Attachment;

/**
 * Class ProductsController
 * @package App\Http\Controllers\Catalog
 */
class ProductsController extends Controller
{
    use Notifiable, HasAttachment;
    /**
     * Список категорий
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        $products = Product::with('brands')->where(function ($q){
            $q->whereNull('visible')->orWhere('visible',0);
        });

        switch (\request()->input('action')){

            case 'search':         // TODO:: Вынести в отдельную страницу Поиска результатов
                $products =  $products->where('name','like','%'.trim(\request()->input('shop-search')).'%');
                break;

            case 'filters':

                    $products->whereBetween('price',[
                        \request()->input('price-from'),
                        \request()->input('price-to')
                     ]
                    );

                if ($categories = \request()->input('categories'))
                    $products->whereIn('category_id',$categories);

                if ($brands = \request()->input('brands'))
                    $products->whereIn('brand_id',$brands);

                break;

            case 'reset':
            default:
                break;
        }

        $products = $products->orderBy('id','desc')->paginate(12)->appends(request()->except('page'));

        return view('admin.products.index', [
            'title' => 'Управление товарами',
            'products' => $products,
            'filters' => \request()->only('price-from','price-to','categories','brands')
        ]);
    }

    /**
     * Добавление товара + dropzone
     * @param ProductFormRequest $request
     * @return Factory|JsonResponse|View
     */
    public function create(ProductFormRequest $request)
    {

        if ($request->post()) {

            try {
                $data = $request->except(['_token','attachment1_id','attachment2_id','attribute_id','preset_id','preset','attribute']);
                $data['user_id'] = auth()->guard('admin')->user()->id;
                $item = Product::create($data);
                $item->attributes()->sync([
                    $request->input('attribute_id') =>
                        [
                            'preset_id' => (int) $request->input('preset_id'),
                            'value' => json_encode($request->input('value'))
                        ]
                ]);
//                dd($request->all());
                self::attachments($item);
                toastr()->success('Товар добавлен');
                return redirect()->route('admin.catalog.products.index');
            }
            catch (Exception $e){
                dd($e);
                toastr()->error('Что-то пошло не так:' . $e);
                return redirect()->back();
            }

        }

        return view('admin.products.add', [
            'title' => 'Добавление товара',
            'brands' => Brand::array(),
            'categories' => Category::array(),
        ]);
    }

    /**
     * Добавление товара + dropzone
     * @param ProductFormRequest $request
     * @param Product $item
     * @return Factory|JsonResponse|View
     */
    public function edit(ProductFormRequest $request, Product $item)
    {

        if ($request->post()) {
            try {

                $data = $request->except(['_token','attachment1_id','attachment2_id','tags']);
                $data['user_id'] = auth()->guard('admin')->user()->id;
                $item->update($data);

                if ($request->input('attribute_id')) {
                    $item->attributes()->sync([
                        $request->input('attribute_id') =>
                            [
                                'preset_id' => (int)$request->input('preset_id'),
                                'value' => json_encode($request->input('value'))
                            ]
                    ]);
                }
                // Добавить когда будет миграция по тегам на прод
                //$tags = Tags::checkTag(request()->input('tags'));
                //$item->tags()->sync($tags);

                self::attachments($item);

                toastr()->info('Товар сохранен');
                return redirect()->route('admin.catalog.products.index');
            }
            catch (Exception $e){

                toastr()->error('Что-то пошло не так:' . $e);
                return redirect()->back();
            }

        }

        return view('admin.products.edit', [
            'title' => 'Добавление товара',
            'brands' => Brand::array(),
            'data' => $item,
            'categories' => Category::array(),
            "files" => collect($item->attachments()->get()),
            "tags" => Tags::all()->pluck('name')
        ]);
    }

    /**
     * Просмотр карточки товара
     * @param Product $item
     * @return Factory|JsonResponse|View
     */
    public function show(Product $item)
    {

        return view('admin.products.show', [
            'title' => @$item->name,
            'brands' => Brand::array(),
            'data' => $item,
            'categories' => Category::array(),
            "files" => collect($item->attachments()->get()),
        ]);
    }

    /**
     * @param Product $item
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Product $item){

        if (!empty($item->attachments()->count())) {
            foreach ($item->attachments()->get() as $k => $v) {
                $attachmentByKey = $v->key;
                $item->attachment($attachmentByKey)->delete();
            }
        }
        $item->delete();
        toastr()->info('Запись удалена!');
        return redirect()->back();

    }

    /**
     * @param Category $category
     * @param Product $product
     * @return JsonResponse
     */
    public function getAttributes(Category $category ,Product $product){

        return response()->json([
            'result' => true,
            'data' => $category->attributes()->with('presets')->get()->toArray(),
            'product' => isset($product->attributes->first()->pivot->value) ? json_decode($product->attributes->first()->pivot->value)->preset : false,
        ]);

    }

    /**
     * Работа с вложениями
     * @param Product $item
     * @return bool
     * @throws Exception
     */
    protected function attachments(Product $item){

        if (!empty(\request()->input('attachment1_id')) ) {
            self::dropAttachments($item);
            if ($attach = Attachment::attach(\request()->input('attachment1_id'), $item, ['main' => true]))
                self::thumb($attach);
        }


        if (!empty(\request()->input('attachment2_id')))
            $attach = Attachment::attach(\request()->input('attachment2_id'), $item,['group' => 'other']);

        return true;
    }

    /**
     * Создание миниатюры
     * @param Attachment $attach
     * @return bool
     */
    protected function thumb(Attachment $attach){

        if (isset($attach)) {
            // TODO: RESIZE and update в сохранение продукта перенести
            try {
                ini_set('memory_limit', '256M');
                $path = 'catalog/thumbs/'.uuid_v4_short() . '.jpg';
                Image::make(Storage::disk('local')->get($attach->filepath))->resize((int) config('config.PRODUCT_THUMB_SIZE'), null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);
            } catch (Exception $e) {
                dd($e);
            }
            $attach->preview_url = $path;
            $attach->save();
        }

        return true;
    }

    /**
     * Поиск по существубщим тегам
     * @return JsonResponse
     * @throws ValidationException
     */
    public function searchTag(){

        $this->validate(request(), [
            'search' => 'required | string | min:2',
        ]);

        $search = \request()->input('search');
        $results = Tags::where('name','like',"%$search%")->get();
        return response()->json([
                'data' => $results
            ],200
        );

    }

    private function dropAttachments($item){
        if (!empty($item->attachments()->count())) {
            foreach ($item->attachments()->get() as $k => $v) {
                $attachmentByKey = $v->key;
                $item->attachment($attachmentByKey)->delete();
            }
        }
        return true;
    }


//        if(request()->ajax()){
//
//            $dt = DataTables::eloquent(Product::with(['users','cats']));
//
//            $dt->addColumn('user', function ($data) {
//                return @$data->users->name ?? '—';
//            });
//
//            $dt->editColumn('created_at', function ($data) {
//                return Carbon::parse($data->created_at)->format("d/m/Y");
//            });
//
//            $dt->addColumn('img', function ($data) {
//                $img = !empty($data->attachments()->where('main',1)->first()->preview_url) ? '/'. @$data->attachments()->where('main',1)->first()->preview_url :  @$data->attachments()->where('main',1)->first()->url;
//                return '<div class=""><img src="'. $img .'" class="img-thumbnail" width="64" ></div>' ?? '—';
//            });
//
//            $dt->addColumn('category', function ($data){
//                return  $data->cats->name ?? '—' ;
//            });
//
//            $dt->addColumn('preset', function ($data){
//                return '<button class="btn btn-outline-info btn-sm px-1" title="Выключено">Набор свойств 1</button >';
//            });
//
//            $dt->addColumn('visible', function ($data) {
//                if ($data->visible == 1)
//                    return '<button class="btn btn-outline-danger btn-sm px-2" title="Выключено"><i class="fa fa-eye-slash" aria-hidden="true"></i></button >';
//
//                return '<button class="btn btn-outline-success btn-sm px-2" title="Включено"><i class="fa fa-eye" aria-hidden="true"></i></button >';
//
//            });
//
//            $dt->addColumn('action', function ($data) {
//
//                $visible[] = '<a href = "'. route('admin.catalog.products.show',$data->id) .'" title="Просмотр товара"><i class="feather icon-eye"></i></a>';
//                $visible[] = '<a href = "#"  class="ml-1" title="Добавить в корзину"><i class="feather icon-shopping-cart"></i></a>';
//                $visible[] = '<a href = "#" data-product-id="' . $data->id . '" class="ml-1 product_to_note__" title="Добавить в записку стилиста"><i class="feather icon-book-open"></i></a>';
//
//                $hidden_buttons[] ='<a href = "'. route('admin.catalog.products.edit',$data->id) .'" title = "Редактирование '. $data->name .'" class="dropdown-item"><i class="feather icon-edit"></i> Редактирование</a>';
//                if (auth()->guard('admin')->user()->hasPermission('destroy-products'))
//                    $hidden_buttons[] .='<a href = "'. route('admin.catalog.products.destroy',$data->id) .'" class="confirm-delete dropdown-item" title = "Удаление '. $data->name .'" ><i class="feather icon-trash-2"></i> Удаление</a>';
//
//                return Common::actionMenu($visible,$hidden_buttons);
//                return $buttons;
//            });
//
//            return $dt->make(true);
//        }

}
