<?php

namespace App\Http\Controllers\Catalog;

use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Catalog\Quantity;
use App\Http\Models\Catalog\Tags;
use App\Http\Requests\Catalog\QuantityFormRequest;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class QuantityController extends Controller
{
    /**
     * Список по количеству
     *
     * @return View
     * @throws Exception
     */
    public function index()
    {

        if(request()->ajax()){

            $query = Quantity::with(['users','products'])
                ->select("quantities.*",'products.sku','products.name')
                ->leftJoin('products','products.id','quantities.product_id');

            //$dt = DataTables::eloquent(Quantity::with(['users','products']));
            $dt = DataTables::eloquent($query);

            $dt->addColumn('product', function ($data) {
                return $data->products->name;
            });

            $dt->addColumn('sku', function ($data) {
                return @$data->products->sku;
            });

            $dt->addColumn('category', function ($data) {
                return @$data->products->cats->name;
            });

            $dt->addColumn('brand', function ($data) {
                return @$data->products->brands->name;
            });

            $dt->addColumn('user', function ($data) {
                return @$data->users->name;
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                if (auth()->guard('admin')->user()->hasPermission('catalog-manage'))
                    $buttons .= '<a href = "' . route('quantity.edit',$data->id) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a>';

                if (auth()->guard('admin')->user()->hasPermission('quantity-destroy'))
                    $buttons .= '<a href = "#" class="ml-5 modal-delete" title = "Удалить запись '. $data->name . '" data-id="'.route('quantity.edit.destroy',$data->id).'" data-route-destroy="'.route('clients.delete',$data->uuid).'" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.quantity.index', [
            'title' => 'Количество/склад товаров'
        ]);
    }

    /**
     * Прием товаров
     * @param QuantityFormRequest $request
     * @return View
     */
    public function create(QuantityFormRequest $request)
    {

        if (\request()->post()){

            $item = new Quantity();
            $item->amount = $request->input('amount');
            $item->product_id = $request->input('product_id');
            $item->user_id = auth()->guard('admin')->user()->id;
            $item->save();

            toastr()->success('Количество добавлено!');
            return redirect()->route('quantity.index');
        }

        return view('admin.quantity.add',[
            'title' => 'Добавление количества товаров',
        ]);
    }

    /**
     * Редактирование Количества
     * @param QuantityFormRequest $request
     * @param int $id
     * @return View
     */
    public function edit(QuantityFormRequest $request, $id)
    {

        $item = Quantity::with('products')->find($id);

        if(!$item)
            return redirect()->back();

        if(request()->post()){

            $item->amount = $request->input('amount');
            $item->product_id = $request->input('product_id');
            $item->user_id = auth()->guard('admin')->user()->id;
            $item->save();

            toastr()->success('Количество сохранено!');
            return redirect()->route('quantity.index');
        }

        return view('admin.quantity.edit', [
            'title' => 'Ред.: ' . @$item->products->name,
            'data' => $item,
        ]);
    }

    /**
     * Удаление Кол-ва товаров
     * @param  int  $id
     *
     * @return RedirectResponse|Redirector
     */
    public function destroy($id)
    {
        $item = Quantity::where('id',$id)->first();
        $item->delete();
        toastr()->error('Количество удалено!');
        return redirect()->route('quantity.index');

    }

    /**
     * Поиск по существубщим товарам
     * @return JsonResponse
     * @throws ValidationException
     */
    public function searchProduct(){
    $this->validate(request(), [
        'search' => 'required | string | min:2',
    ]);

    $search = \request()->input('search');
    $results = Product::where('name','like',"%$search%")->orWhere('sku','like',"%$search%")->get();
    return response()->json([
        'result' => true,
        'data' => $results
    ],200
    );

}

}
