<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Classes\DoliApi;
use App\Http\Controllers\Controller;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\DoliTransactions;
use App\Http\Models\Common\Lead;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Venturecraft\Revisionable\Revision;
use Yajra\DataTables\Facades\DataTables;

class DoliController extends Controller
{
    protected DoliTransactions $model;
    protected $products;

    /**
     * CategoriesTranslatorController constructor.
     * @param DoliTransactions $model
     */
    public function __construct(DoliTransactions $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     * @throws \Exception
     */
    public function index()
    {
        if(request()->ajax()) {

            $dt = DataTables::eloquent($this->model::query());
            $dt->addColumn('user_name', function ($data) {
                return @$data->users->name;
            });
            $dt->editColumn('created_at', function ($data) {
                return Carbon::parse($data->created_at)->format("d.m.Y H:i");
            });
            $dt->addColumn('amo_id', function ($data) {
                return @$data->leads->amo_lead_id;
            });
            $dt->addColumn('action', function ($data) {
                if (empty($data->x_correlation_id))
                    $buttons = '<a href = "' . route('admin.doli.edit', $data->id) . '" title = "Редактировать" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                else
                    $buttons = '<a  title = "Процедура возврата завершена" class="disabled"><i class=" ml-5 fa far fa-edit text-muted"></i></a>';

                $buttons .= '<a href = "' . route('leads.edit', $data->lead_uuid) . '" title = "Ссылка на Сделку" ><i class=" ml-5 fa far fa-link text-primary"></i></a></a >';
                return $buttons;
            });
            return $dt->make(true);
        }

        return view("admin.doli.index", [
            'title' => 'Транзакции по Долями'
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        $item = $this->model::with(["leads"])->findOrFail($id);
        if (!empty($item->x_correlation_id))
            return redirect()->back();

        self::getProducts($item);

        return view("admin.doli.edit", [
            'title' => 'Детали транзакции ' . @$item->doli_id,
            'data' => $item,
            "products" => (object) $this->products ?? false
        ]);
    }

    private function getProducts($item, $with_product = true){

        foreach ($item->items as $index => $value)
            if (!empty($value["id"]))
                $this->products[] = (object)[
                    "id" => $value["id"],
                    "price" => $value["price"],
                    "quantity" => $value["quantity"],
                    "product" => ($with_product) ? Product::getProductById($value["id"]) : null,
                ];

    }

    private function getCheckedProducts($items)
    {
        if ($this->products) {
            foreach ($this->products as $i => $product) {
                if (in_array($product->id, array_keys($items))) {
                    unset($product->product);
                    $products[] = $product;
                }
            }
            return $products;
        }
        return false;
    }

    public function update($id)
    {
        try {

            $item = $this->model::with(["leads"])->findOrFail($id);
            self::getProducts($item, false);
            $refund_type = (string)\request()->input("refund_type");

            switch ($refund_type) {

                case "all":
                    $item->returned_items = $this->products;
                    $item->refund_amount = $item->amount;
                    break;

                case "partials":
                    $checked_products = collect(self::getCheckedProducts(\request()->input("item")));

                    $item->returned_items = $checked_products->toArray();
                    $item->refund_amount = $checked_products->sum("price") ?? null;
                    break;

                default:
                    toastr()->error("Не указан тип");
                    return redirect()->back();
            }

            $item->x_correlation_id = uuid_v4();
            $item->refund_at = now();
            $item->refund_type = $refund_type;
            $item->save();

            $api = new DoliApi();
            $response = $api->refundOrder($item);

            if (!empty($response) && !empty($response->refund_id)) {
                Log::channel('doli')->alert("Возврат успешный: " . json_encode($response));
                $item->refund_id = $response->refund_id;
                $item->save();
                self::updateRevision($item,$response->amount);
                toastr()->success('Успешный вовзрат! \n' . $response->refund_id);
            }
            else
                toastr()->error('Что то пошло не так!');

            return redirect()->route('admin.doli.index');
        }
        catch (\Exception $e){
            Log::channel('doli')->alert("Ошибка проведения возврата: " . $e->getMessage());
            return redirect()->route('admin.doli.index');
        }

    }


    public function destroy($id){}

    private function updateRevision($item, $new_amount){

        $type = $item->refund_type == 'all' ? 'полный ' : ' частичный';

        $revision = new Revision();
        $revision->revisionable_type = 'App\Http\Models\Common\Lead';
        $revision->revisionable_id = $item->lead_uuid;
        $revision->user_id = @auth()->guard('admin')->user()->id;
        $revision->key = 'Возврат ДОЛИ';
        $revision->old_value = '-';
        $revision->new_value = "| Refund_id: #" . $item->refund_id . '| Тип возврата: ' . $type . '| Новая сумма: ' . $new_amount;
        $revision->created_at = now();
        $revision->save();
        return true;

    }

}
