<?php

namespace App\Http\Controllers\Common;


use App\Exports\CouponsExport;
use App\Exports\StylistsExport;
use App\Http\Models\Common\Coupon;
use App\Http\Requests\Common\CouponFromRequest;
use App\Imports\CouponImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Validator;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Управление купонами
 *
 * @package App\Http\Controllers\Admin
 */
class CouponController extends Controller
{
    /**
     * CouponController constructor.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function index()
    {


        if(request()->ajax()){

            $dt = DataTables::eloquent(Coupon::query());

            $dt->addColumn('params', function ($data) {
                return "<span class='btn btn-small btn-outline-info'>" . @$data->type . "</span>";
            });

            $dt->addColumn('custom', function ($data) {
                return "<span class='btn btn-small btn-outline-success'>" . $data->price . "</span>";
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                if (auth()->guard('admin')->user()->can('destroy-coupon'))
                    $buttons .= '<a href = "'.route('coupon.destroy',$data->id).'" class=" ml-5 modal-delete" title = "Удалить купон '. $data->name . '" data-id="'.route('coupon.destroy',$data->id).'" data-route-destroy="'.route('clients.delete',$data->uuid).'"  onclick="return confirm(\'Удалить купон?\')"><i class="fa far fa-trash-alt text-danger"></i></a></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.coupon.index', [
            'title' => 'Управление Купонами'
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function import(){


        if (request()->post()) {

            if(!request()->file('xlsFile') ) {
                toastr()->error('Нет вложенного файла');
                return \redirect()->back();
            }

            Excel::import(new CouponImport(), request()->file('xlsFile'));
            toastr()->info('Данные загружены');
            return \redirect()->back();
        }

        return view('admin.coupon.import', [
            'title' => 'Загрузка купонов из XlS'
        ]);

    }

    /**
     * Создание одиночного купона
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(){

        return view('admin.coupon.add', [
            'title' => 'Добавление нового купона'
        ]);

    }

    /**
     * Сохранение одиночного купона
     * @param CouponFromRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CouponFromRequest $request){

        $coupon = new Coupon();
        $coupon->name = $request->input('name');
        $coupon->type = $request->input('type');
        $coupon->price = $request->input('price');
        $coupon->save();

        toastr()->success('Купон добавлен, ID: ' . @$coupon->id);
        return \redirect()->route('coupon.index');

    }

    /**
     * Удаление купона
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(int $id){

        Coupon::where('id',$id)->delete();
        toastr()->info('Купон удален!');
        return \redirect()->back();

    }


    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export() {

        return Excel::download(new CouponsExport(), 'coupons.xlsx');

    }
}