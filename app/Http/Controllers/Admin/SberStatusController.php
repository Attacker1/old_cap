<?php

namespace App\Http\Controllers\Admin;

use \App\Http\Controllers\Controller;
use App\Http\Models\AdminClient\FeedbackgeneralQuize;
use App\Http\Models\Common\LeadRef;
use App\Http\Models\Common\SberStatus;
use App\Http\Requests\Admin\ClientStatusFormRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

class SberStatusController extends Controller
{
    /**
     * Список статусов
     *
     * @return Response
     * @throws \Exception
     */
    public function index()
    {

        return view('admin.sber-statuses.index', [
            'title' => 'Статусы Сбербанка',
        ]);
    }

    public function dt()
    {
        $dt = DataTables::eloquent(SberStatus::with('leadref'));

        $dt->addColumn('statuses', function ($data) {

            $html = '';
            foreach ($data->leadref()->get() as $item)
                $html .= '<span class="ml-3 mb-3 btn btn-sm btn-outline-info">' . @$item->name . '</span>' ;

            return $html;
        });

        $dt->addColumn('action', function ($data) {
            $buttons = '';

            $buttons .= '<a href = "' . route('sber.statuses.edit',$data->id) . '" title = "Редактирование статуса" class="ml-5"><i class="fa far fa-eye text-primary"></i></a >';

            return $buttons;
        });

        return $dt->make(true);
    }

    /**
     * Создание нового статуса
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.sber-statuses.create', [
            'title' => 'Создать статус',
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

        $state = new SberStatus();
        $state->name = trim($request->input('name'));
        $state->save();

        foreach (\request()->input('state') as $k=>$v)
            $state->leadref()->syncWithoutDetaching([$state->id => $v]);

        toastr()->success('Статус добавлен');
        return redirect()->route('sber.statuses.index');
    }

    /**
     * Display the specified resource.
     *
     * @param SberStatus $clientStatus
     * @return Response
     */
    public function show(SberStatus $clientStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param SberStatus $clientStatus
     * @return Response
     */
    public function edit($client_state_id)
    {
        $state = SberStatus::find($client_state_id);

        return view('admin.sber-statuses.edit', [
            'title' => 'Редактировать статус: ' . @$state->name,
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

        $state = SberStatus::find($clientStatus_id);
        $state->name = trim($request->input('name'));
        $state->save();

        $state->leadref()->detach();
        foreach (\request()->input('state') as $k=>$v)
            $state->leadref()->syncWithoutDetaching([$state->id => $v]);

        toastr()->success('Статус добавлен');
        return redirect()->route('sber.statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param SberStatus $clientStatus
     * @return Response
     */
    public function destroy(SberStatus $clientStatus)
    {
        //
    }
}
