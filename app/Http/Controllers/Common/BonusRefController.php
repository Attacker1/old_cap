<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Http\Models\Common\BonusRef;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Справочник баллов по рефферальным программам
 * Class BonusRefController
 * @package App\Http\Controllers\Common
 */
class BonusRefController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        if (\request()->ajax()){
            $sql = BonusRef::query();

            $dt = DataTables::eloquent($sql);

            $dt->addColumn('action', function ($data) {

                $buttons = '<a href = "' . route('bonus.ref.edit',$data->id) . '" title = " Редактировать '. $data->name . '" ><i class=" ml-5 fa far fa-edit text-primary"></i></a></a >';
                $buttons .= '<a href = "#" class=" ml-5 modal-delete" title = "Удалить запись '. $data->name . '" data-id="'.route('bonus.ref.destroy',$data->id).'" data-route-destroy="'.route('bonus.ref.destroy',$data->id).'"><i class="fa far fa-trash-alt text-danger"></i></a></a >';
                return $buttons;

            });

            return $dt->make(true);
        }

        return view('admin.bonus-ref.index',[
            'title' => 'Справочник бонусов',

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Models\Common\BonusRef  $bonusRef
     * @return \Illuminate\Http\Response
     */
    public function show(BonusRef $bonusRef)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\Common\BonusRef  $bonusRef
     * @return \Illuminate\Http\Response
     */
    public function edit(BonusRef $bonusRef)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Models\Common\BonusRef  $bonusRef
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BonusRef $bonusRef)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Models\Common\BonusRef  $bonusRef
     * @return \Illuminate\Http\Response
     */
    public function destroy(BonusRef $bonusRef)
    {
        //
    }
}
