<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Http\Models\Common\Bonus;
use App\Http\Models\Common\BonusTransactions;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BonusTransactionsController extends Controller
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

            $dt = DataTables::eloquent(BonusTransactions::with('clients'));

            $dt->addColumn('client', function ($data) {
                return @$data->clients->name ?? '';
            });

            $dt->addColumn('points', function ($data) {
                return @$data->points ?? '';
            });

            $dt->addColumn('promocode', function ($data) {
                return @$data->promocode ?? '';
            });

            $dt->addColumn('type', function ($data) {
                return @$data->type ?? '';
            });

            $dt->addColumn('pay_to', function ($data) {
                return @$data->pay_to ?? '';
            });

            $dt->addColumn('action', function ($data) {
                $buttons = '';
                //$buttons .='<a href = "'. route('bonuses.edit',$data->id) .'" title = "Редактирование бонусов '. @$data->clients->name .'" ><button class="btn btn-dark btn-sm px-1 ml-1" ><i class="fa fa-pencil-square-o" aria-hidden= "true" ></i ></button ></a >';

                return $buttons;
            });

            return $dt->make(true);
        }

        return view('admin.transactions.index', [
            'title' => 'Бонусы клиентов'
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
     * @param  \App\Http\Models\Common\BonusTransactions  $bonusTransactions
     * @return \Illuminate\Http\Response
     */
    public function show(BonusTransactions $bonusTransactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Http\Models\Common\BonusTransactions  $bonusTransactions
     * @return \Illuminate\Http\Response
     */
    public function edit(BonusTransactions $bonusTransactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Http\Models\Common\BonusTransactions  $bonusTransactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BonusTransactions $bonusTransactions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Models\Common\BonusTransactions  $bonusTransactions
     * @return \Illuminate\Http\Response
     */
    public function destroy(BonusTransactions $bonusTransactions)
    {
        //
    }
}
