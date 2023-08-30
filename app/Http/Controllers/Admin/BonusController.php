<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Models\Admin\AdminUser;
use App\Http\Models\Common\Bonus;
use App\Http\Requests\AdminClient\BonusFormRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BonusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {

        if(request()->ajax()){

            $dt = DataTables::eloquent(Bonus::with('clients'));

            $dt->addColumn('client', function ($data) {
                return @$data->clients->name ?? '';
            });

            $dt->addColumn('points', function ($data) {
                return @$data->points ?? '';
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                $buttons .='<a href = "'. route('bonuses.edit',$data->id) .'" title = "Редактирование бонусов '. @$data->clients->name .'" ><button class="btn btn-dark btn-sm px-1 ml-1" ><i class="fa fa-pencil-square-o" aria-hidden= "true" ></i ></button ></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin-clients.bonuses.index', [
            'title' => 'Бонусы клиентов'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        return view('admin-clients.bonuses.create', [
            'title' => 'Новые бонусы'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BonusFormRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Models\Common\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function show(Bonus $bonus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\Common\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function edit(Bonus $bonus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Models\Common\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bonus $bonus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Models\Common\Bonus  $bonus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bonus $bonus)
    {
        //
    }
}
