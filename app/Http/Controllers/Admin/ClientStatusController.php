<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Common\ClientStatus;
use App\Http\Models\Common\LeadRef;
use App\Http\Requests\Admin\ClientStatusFormRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class ClientStatusController extends Controller
{
    /**
     * Список статусов
     *
     * @return Response
     * @throws \Exception
     */
    public function index()
    {

        if (request()->ajax()) {

            $dt = DataTables::eloquent(ClientStatus::with('leadref'));

            $dt->addColumn('statuses', function ($data) {

                $html = '';
                    foreach ($data->leadref()->get() as $item)
                        $html .= '<span class="ml-3 mb-3 btn btn-sm btn-outline-info">' . @$item->name . '</span>' ;

                return $html;
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';

                $buttons .= '<a href = "' . route('client.statuses.edit',$data->id) . '" title = "Редактирование статуса" class="ml-5"><i class="fa far fa-eye text-primary"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.clients-statuses.manage.index', [
            'title' => 'Клиентские статусы',
        ]);
    }

    /**
     * Создание нового статуса
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.clients-statuses.manage.create', [
            'title' => 'Создать клиентский статус',
            'states' => LeadRef::orderBy('id')->pluck('name','id')
        ]);
    }

    /**
     * Сохранение нового статуса
     *
     * @param ClientStatusFormRequest $request
     * @return void
     */
    public function store(ClientStatusFormRequest $request)
    {

        $state = new ClientStatus();
        $state->name = trim($request->input('name'));
        $state->save();

        foreach (\request()->input('state') as $k=>$v)
            $state->leadref()->syncWithoutDetaching([$state->id => $v]);

        toastr()->success('Статус добавлен');
        return redirect()->route('client.statuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param ClientStatus $clientStatus
     * @return Response
     */
    public function show(ClientStatus $clientStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param ClientStatus $clientStatus
     * @return Response
     */
    public function edit($client_state_id)
    {
        $state = ClientStatus::find($client_state_id);

        return view('admin.clients-statuses.manage.edit', [
            'title' => 'Редактировать клиентский статус: ' . @$state->name,
            'data' => $state,
            'states' => LeadRef::orderBy('id')->pluck('name','id')
        ]);
    }

    /**
     * Обновить данные по статусу
     *
     * @param ClientStatusFormRequest $request
     * @param $clientStatus_id
     * @return Response
     */
    public function update(ClientStatusFormRequest $request, $clientStatus_id)
    {

        $state = ClientStatus::find($clientStatus_id);
        $state->name = trim($request->input('name'));
        $state->save();

        $state->leadref()->detach();
        foreach (\request()->input('state') as $k=>$v)
            $state->leadref()->syncWithoutDetaching([$state->id => $v]);

        toastr()->success('Статус добавлен');
        return redirect()->route('client.statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ClientStatus $clientStatus
     * @return Response
     */
    public function destroy(ClientStatus $clientStatus)
    {
        //
    }
}
