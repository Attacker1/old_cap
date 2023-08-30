<?php

namespace App\Abstracts;

use Illuminate\Http\Request;

abstract class Payments
{
    protected $request;

    protected $lead_id;
    protected $amount;
    protected $paid_at;
    protected $pay_for;
    protected $payload;
    protected $payment_id;
    protected $source;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->lead_id = $request->get('leadUuid');
        $this->pay_for = $request->get('pay_for') ?: 'stylist';
    }

    abstract public function prepareToCreate($model);

    public function create(){
        // общий метод в базу
       return \App\Http\Models\Common\Payments::create([
           'lead_id' => $this->lead_id,
           'amount' => $this->amount,
           'paid_at' => $this->paid_at,
           'pay_for' => $this->pay_for,
           'payload' => $this->payload,
           'payment_id' => $this->payment_id,
           'source' => $this->source,
       ]);

    }

}
