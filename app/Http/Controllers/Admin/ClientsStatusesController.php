<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Classes\Client;
use App\Http\Models\AdminClient\Client as ClientModel;
use App\Http\Models\AdminClient\ClientStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Clients\AddClientFormRequest;
use App\Http\Requests\Admin\Clients\UpdateClientFormRequest;
use App\Http\Requests\Admin\Clients\SearchClientFormRequest;
use App\Http\Requests\Admin\Clients\SearchAdvancedClientFormRequest;
use Illuminate\Support\Facades\Session;

class ClientsStatusesController extends Controller
{

    public function index()
    {
        if(!auth()->guard('admin')->user()->can('manage-clients'))
        {
            return redirect()->route('admin.main.index');
        }

        $data = ClientStatus::all();

        if(session('status_success')) {
            toastr()->success(session('status_success'));
        }

        if(session('status_error')) {
            toastr()->error(session('status_error'));
        }

        return view('admin.clients-statuses.list',[
            'title' => 'Управление статусами клиентов',
            'statusesData' => $data
        ]);
    }


    public function show($uuid)
    {
        if(!auth()->guard('admin')->user()->can('manage-clients'))
        {
            return redirect()->route('admin.main.index');
        }

        
        return view('admin.clients-statuses.show',[
            'clientData' => $clientData,
            'title' => 'Просмотр карточки клиента'
        ]);
    }

    public function destroy($id)
    {
        if(!auth()->guard('admin')->user()->can('manage-clients'))
        {
            return redirect()->route('admin.main.index');
        }

        $status = ClientStatus::find($id);
        $status->delete();

        toastr()->success('Запись успешно удалена');

        return redirect()->route('admin.clients-statuses.list');
    }

    public function create()
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        return view('admin.clients-statuses.create', [
            'title' => 'Создание нового статуса для клиента'
        ]);
    }

    public function store(Request $request)
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $status = new ClientStatus();
        $status->fill($request->all());
        $res = $status->save();

        if(session('status_success')) {
            toastr()->success(session('status_success'));
        }

        return redirect()->route('admin.clients-statuses.list')->with('status_success', 'Запись успешно создана');
    }

    public function edit($id)
    {
        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $statusData = ClientStatus::find($id);

        return view('admin.clients-statuses.edit', [
            'title' => 'Редактирование статуса клиента',
            'statusData' => $statusData
        ]);
    }

    public function update(Request $request, $id)
    {

        if (!auth()->guard('admin')->user()->can('manage-clients')) {
            return redirect()->route('admin.main.index');
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
        ]);

        $status = ClientStatus::find($id);
        $status->name = $request->name;
        $status->save();

        return redirect()->route('clients-statuses.edit', $id)->with('status_success', 'Запись успешно сохранена');
    }

}