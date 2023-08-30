<?php

namespace App\Http\Controllers\Admin;

use App\Http\Classes\Common;
use \App\Http\Controllers\Controller;
use App\Http\Classes\Client;
use App\Http\Models\AdminClient\Client as ClientModel;
use App\Http\Models\AdminClient\ClientStatus;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Clients\AddClientFormRequest;
use App\Http\Requests\Admin\Clients\UpdateClientFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Venturecraft\Revisionable\Revision;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Models\Common\Bonus;
use Illuminate\Support\Str;

/**
 * Управление клиентами
 * Class ClientsController
 * @package App\Http\Controllers\Admin
 */
class ClientsController extends Controller
{

    /**
     * @deprecated
     * @param Request $request
     * @return false|Factory|RedirectResponse|View|string
     */
    public function index(Request $request)
    {
        if(!auth()->guard('admin')->user()->can('manage-clients'))
        {
            return redirect()->route('admin.main.index');
        }

        if(request()->ajax())
        {
            $arr_columns = [
                1=>'uuid',
                2=>'referal_code',
                3=>'name',
                4=>'second_name',
                5=>'phone',
                6=>'email',
                7=>'comments',
                8=>'client_status_id',
                9=>'created_at',
                10=>'updated_at'];
            $request_params = $request->all();

            $order_column = $arr_columns[$request_params['order'][0]['column']];

            $order_dir = $request_params['order'][0]['dir'];

            $pagination_start = $request_params['start'];
            $pagination_length = $request_params['length'];

            $search_value = $request_params['search']['value'];

            session()->forget('clients_data');
            session()->push('clients_data', [
                'order_column' => $request_params['order'][0]['column'],
                'order_dir' => $order_dir,
                'search_value' => $search_value,
                'limit_menu' => $pagination_length,
                'paging_start' => $pagination_start
            ]);

            $recordsTotal = ClientModel::count();

            $clientModal = new ClientModel();

            $clients = $clientModal->select($arr_columns)->with('client_status:id,name');

            if($search_value != '')
            {
                 $clients = $clients
                     ->where('uuid',  'LIKE' , "%{$search_value}%")
                     ->orWhere('referal_code', 'LIKE' , "%{$search_value}%")
                     ->orWhere('name', 'LIKE' , "%{$search_value}%")
                     ->orWhere('second_name', 'LIKE' , "%{$search_value}%")
                     ->orWhere('phone', 'LIKE' , "%{$search_value}%")
                     ->orWhere('comments', 'LIKE' , "%{$search_value}%")
                     ->orWhere('email', 'LIKE' , "%{$search_value}%");

                 $recordsFiltered = $clients->count();
            }

            $recordsFiltered = $recordsFiltered ?? $recordsTotal;

            $clients = $clients
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)
                ->get()
                ->toArray();

            /*$clients
                ->orderBy($order_column, $order_dir)
                ->take($pagination_length)
                ->skip($pagination_start)->dump();*/

            $res = [
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=>$clients,
                'temp'=>session()->get('clients_data')];

            return json_encode($res);
        }

        if(session('status_success')) {
            toastr()->success(session('status_success'));
        }

        if(session('status_error')) {
            toastr()->error(session('status_error'));
        }

        $datatable_params[0]= [
            'order_column' => '1',
            'order_dir' => 'desc',
            'search_value' => '',
            'limit_menu' => '10',
            'paging_start' => 0
        ];

        if(session()->has('clients_data'))
        {
            $datatable_params = session()->get('clients_data', 'default');
        }

        return view('admin.clients.list',[
            'title' => 'Управление клиентами',
            'datatable_params' => $datatable_params[0]
        ]);
    }

    /**
     * @deprecated
     * @return RedirectResponse
     */
    public function reset_datatable_settings()
    {
        session()->forget('clients_data');
        return redirect()->route('clients.list');
    }

    /**
     * Просмотр карточки
     * @param $uuid
     * @return Factory|RedirectResponse|View
     */
    public function show($uuid)
    {
        if(!auth()->guard('admin')->user()->can('view-clients'))
        {
            return redirect()->route('admin.main.index');
        }

        $clientData = ClientModel::with(['bonuses','leads'])->find($uuid);

        if(!$clientData) return redirect()->route('clients.list');
        $clientData->status = $clientData->client_status->name ?? 'отсутствует';

        $clientData->comments = $clientData->comments ?? 'отсутствуют';

        return view('admin.clients.show',[
            'clientData' => $clientData,
            'title' => 'Просмотр карточки клиента',
            'history' => $clientData->revisionHistory,
            'manage' => !auth()->guard('admin')->user()->hasRole('stylist')
        ]);
    }

    public function destroy($uuid)
    {
        if(!auth()->guard('admin')->user()->can('manage-clients'))
        {
            return redirect()->route('admin.main.index');
        }

        $client = new Client();
        $arr_res = $client->drop($uuid);

        if($arr_res['result'] === true) toastr()->success('Запись успешно удалена');
        else toastr()->error('Ошибка удаления');

        return redirect()->route('clients.list');
    }

    public function create()
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $code = Client::createReferalCode();

        if(session('status_error')) {
            toastr()->error(session('status_error'));
        }

        $client_statuses = ClientStatus::all();

        return view('admin.clients.create', [
            'title' => 'Создание нового клиента',
            'referal_code' => $code,
            'client_statuses' => $client_statuses
        ]);
    }

    public function store(AddClientFormRequest $request)
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $client = new Client($request->all());
        $res = $client->add(false);

        $promocode = '';

        $promocode_name = Str::slug($client->name);
        $promocode_phone = substr($client->phone, -4);

        $promocode = $promocode_name.$promocode_phone;

        $i = 0;

        do {
            $check_promo = Bonus::where('promocode', $promocode)->first();

            if($check_promo) {
                $promocode .= (string) $i;
                $i++;
            }

        } while($check_promo && $i<100);


        if($check_promo)
        {
            session()->flashInput($request->input());
            return redirect()->route('clients.create')->with('status_error', 'Ошибка создания promocode в bonuses')->with('errors');
        }

        $bonuses = new Bonus();
        $bonuses->client_id = $res['new_client']['uuid'];
        $bonuses->promocode = $promocode;
        $bonuses->save();

        if(!$res['result'])
        {
            session()->flashInput($request->input());
            return redirect()->route('clients.create')->with('status_error', 'Ошибка записи в БД')->with('errors');
        }

        return redirect()->route('clients.list')->with('status_success', 'Запись успешно создана');
    }

    /**
     * Редактирование клиента
     * @param $uuid
     * @return Factory|RedirectResponse|View
     */
    public function edit($uuid)
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        if(session('status_error')) {
            toastr()->error(session('status_error'));
        }

        if(session('status_success')) {
            toastr()->success(session('status_success'));
        }

        $clientData = ClientModel::find($uuid);
        $client_statuses = ClientStatus::all();

        return view('admin.clients.edit', [
            'title' => 'Редактирование клиента',
            'clientData' => $clientData,
            'client_statuses' => $client_statuses,
            'history' => ClientModel::bonusHistory($uuid)
        ]);
    }

    public function update(UpdateClientFormRequest $request, $uuid)
    {

        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $client = new Client($request->all());

        $res = $client->edit( $uuid, false);

        if(!$res['result'])
        {
            session()->flashInput($request->input());
            return redirect()->route('clients.edit', $uuid)->with('status_error', 'Ошибка записи в БД')->with('errors');
        }

        return redirect()->route('clients.list')->with('status_success', 'Запись успешно сохранена');
    }

    /**
     * Обновление пароля
     * @param $uuid
     * @return RedirectResponse
     */
    public function updatePassword($uuid){
        $newPassword = \request()->input('new_password');

        if (\request()->post() && $newPassword) {

            $validator = Validator::make(\request()->all(), [
                'new_password' => ['string',
                    'min:7',             // must be at least 10 characters in length
                    'regex:/[a-z]/',      // must contain at least one lowercase letter
                    'regex:/[A-Z]/',      // must contain at least one uppercase letter
                    'regex:/[0-9]/', ]
            ],
            [
                'new_password.min' => 'Минимальная длина пароля 7 символов',
                'new_password.regex' => 'Пароль должен содержать как минимум 1 заглавную латинскую букву и 1 цифру'
            ])->validate();

            $client = Client::where('uuid', $uuid)->get()->first();
            $client->password = bcrypt(\request()->input('new_password'));
            $client->save();

        }
        return redirect()->route('clients.list')->with('status_success', 'Пароль обновлен');
    }

    /**
     * Обновление бонусов
     * @param $uuid
     * @return RedirectResponse
     */
    public function updateBonus($uuid){

        if (\request()->post()) {

            if (!$item = Bonus::where('client_id', $uuid)->first()){
                $item = new Bonus();
                $item->client_id = $uuid;
                $item->save();
            }

            $item->points = \request()->input('points') ?? null ;
            $item->promocode = \request()->input('promocode') ?? null ;
            $item->save();


            if ($revision = $item->revisionHistory->last()) {
                Revision::where('id', $revision->id)->update(['user_id' => Auth::guard('admin')->user()->id]);
            }

        }
        return redirect()->route('clients.list')->with('status_success', 'Бонусы обновлены');
    }

    /**
     * Под Метроник
     *
     * @param Request $request
     * @return false|Factory|RedirectResponse|View|string
     * @throws \Exception
     */
    public function list(Request $request)
    {

        if (\request()->ajax()) {

            $sql = ClientModel::with('bonuses');

            $dt = DataTables::eloquent($sql);

            $dt->addColumn('points', function ($data) {
                return '<button class="btn btn-sm btn-outline-warning">'. ($data->bonuses->points ?? 0) .'</button>';
            });

            $dt->addColumn('name', function ($data) {
                return $data->second_name . ' ' . $data->name . ' ' . $data->patronymic;
            });

            $dt->addColumn('tel', function ($data) {
                $str = strval($data->phone);
                $str = substr($str, 0, 1).'('.substr($str, 1, 3).')'.substr($str, 4, 3).'-'.substr($str, 7, 2).'-'.substr($str, 9, 2);
                return '<a class="font-weight-bold text-success " href="'. route('clients.show',$data->uuid) .'" style="white-space: nowrap;">' . @$str . '</a>';
            });

            $dt->addColumn('email', function ($data) {
                return '<a class="text-muted text-hover-primary" href="'. route('clients.show',$data->uuid) .'">' . @$data->email . '</a>';
            });

            $dt->addColumn('code', function ($data) {
                return @$data->bonuses->promocode ?? '—';
            });

            $dt->addColumn('action', function ($data) {

                $buttons  = '<a href = "' . route('clients.show',$data->uuid) . '" title = " Просмотр '. $data->name . '" class=""><i class="fa far fa-eye text-primary"></i></a></a >';
                $buttons .= '<a href = "' . route('clients.edit',$data->uuid) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                $buttons .= '<a class="lead-create"  data-phone="' . $data->phone . '" href = "#" title = " Создать сделку '. $data->name . '" ><i class="ml-5 fas fa-tag text-warning"></i></a></a >';
                //$buttons .= '<a href = "#" class=" ml-5 modal-delete" title = "Удалить запись '. $data->name . '" data-id="'.route('clients.delete',$data->uuid).'" data-route-destroy="'.route('clients.delete',$data->uuid).'"><i class="fa far fa-trash-alt text-danger"></i></a></a >';
                return $buttons;

            });

            return $dt->make(true);
        }

        return view('admin.clients.index',[
            'title' => 'Управление клиентами',

        ]);
    }

}
