<?php

namespace App\Http\Controllers\Api\Sber;

use App\Http\Classes\Common;
use App\Http\Classes\Message;
use App\Http\Controllers\Controller;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\Payments;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrdersStateController extends Controller
{
    private $description = '';

    private $states = [
        "new" => 1,
        "cancelled" => 20,
        "processing" => 3,
        "sent" => 8,
        "completed" => 14,

    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($uuid)
    {

        if (!empty($lead = Lead::whereUuid($uuid)->first())) {



            $states = array_flip($this->states);

            if (!empty($states[$lead->state_id]))
                $order_state = $states[$lead->state_id];
            else
                $order_state = 'new';
        } else
            $this->description = 'Заказ не найден';

        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'order_state' => $order_state ?? '',
        ])
            ->setStatusCode(Response::HTTP_OK);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid, $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid, $state)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($uuid, $state)
    {
        if (!empty($lead = Lead::find($uuid))) {
            $lead->state_id = $this->states[$state];
            if (empty($lead->amo_lead_id))
                $lead->amo_lead_id = 30221469; // TODO For tests SBER
            $lead->save();

        } else
            $this->description = 'Заказ не найден';


        return response()->json([
            'status' => !empty($this->description) ? 'error' : 'ok',
            'description' => @$this->description ?? '',
        ])
            ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
