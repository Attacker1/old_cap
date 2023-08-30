<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Models\Catalog\Attribute;
use App\Http\Models\Common\ClientSettings;
use App\Http\Requests\Common\ClientSettingsFromRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

/**
 * Настройки клиентские
 * Class ClientSettingsController
 * @package App\Http\Controllers\Common
 */
class ClientSettingsController extends Controller
{

    public function index()
    {
        if(request()->ajax()){

            $dt = DataTables::eloquent(ClientSettings::query());

            $dt->addColumn('params', function ($data) {
                if (!empty($data->params)){
                    $html = '';
                    foreach ($data->params as $k=>$v)
                        $html .= '<button class="btn btn-sm btn-outline-info ml-1 mb-1">' . $k . ": " . $v . '</button>';

                    return $html;

                }
                return '—';
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                if (auth()->guard('admin')->user()->hasPermission('catalog-manage'))
                    $buttons .= '<a href = "' . route('client.settings.edit',$data->id) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a>';

                if (auth()->guard('admin')->user()->hasPermission('catalog-cat-destroy'))
                    $buttons .= '<a href = "'.route('client.settings.destroy',$data->id).'" class="ml-5 modal-delete" title = "Удалить запись '. $data->name . '"  onclick="return confirm(\'Удалить параметры?\')" ><i class="fa far fa-trash-alt text-danger"></i></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.client-settings.index', [
            'title' => 'Настройки для клиетской части'
        ]);


    }

    /**
     * Настройки клиентские
     *
     * @return Response
     */
    public function create()
    {

        return view('admin.client-settings.add',[
            'title' => 'Добавление параметров',
        ]);
    }

    /**
     * Сохранение параметров
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(ClientSettingsFromRequest $request)
    {

        if (!empty(\request()->input('new'))){
            foreach (\request()->input('new')['title'] as $k=>$v){
                if (isset(\request()->input('new')['value']) && isset(\request()->input('new')['value'][$k]))
                    $params[$v] = \request()->input('new')['value'][$k];
            }
        }

        $setting = new ClientSettings();
        $setting->name = \request()->input('name');
        $setting->params = $params ?? null;
        $setting->save();

        toastr()->success('Параметры добавлены!');
        return redirect()->route('client.settings.index');
    }

    /**
     * Редактирование параметров
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id)
    {
        $settings = ClientSettings::find($id);
        return view('admin.client-settings.edit',[
            'title' => 'Добавление параметров',
            'data' =>  $settings,
            'params' =>  $settings->params
        ]);
    }

    /**
     *
     *
     * @param ClientSettingsFromRequest $request
     * @param int $id
     * @return void
     */
    public function update(ClientSettingsFromRequest $request, int $id)
    {

        $params = \request()->input('params') ?? [];
        if (!empty(\request()->input('new'))){
            foreach (\request()->input('new')['title'] as $k=>$v){
                if (isset(\request()->input('new')['value']) && isset(\request()->input('new')['value'][$k]))
                $params[$v] = \request()->input('new')['value'][$k];
            }
        }

        $setting = ClientSettings::find($id);
        $setting->name = \request()->input('name');
        $setting->params = $params ?? null;
        $setting->save();

        toastr()->info('Параметр сохранен!');
        return redirect()->route('client.settings.index');
    }

    /**
     *
     * @param int $id
     * @param ClientSettings $clientSettings
     * @return Response
     */
    public function destroy(int $id)
    {
        ClientSettings::where('id',$id)->delete();
        toastr()->error('Параметр удален!');
        return redirect()->route('client.settings.index');
    }
}
