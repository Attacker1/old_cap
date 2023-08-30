<?php


namespace App\Services\Doli;


use App\Abstracts\Doli;
use App\Http\Models\Common\DoliTransactions;
use Illuminate\Support\Facades\Log;

class StoreTransactions
{
    protected Doli $doli;

    public function __construct(){}

    public function created(Doli $doli, $lead_uuid){

        try {
            $transaction = new DoliTransactions();
            $transaction->fill([
                "lead_uuid" => $lead_uuid,
                "doli_id" => $doli->order["id"],
                "amount" => $doli->order["amount"],
                "items" => $doli->items,
            ]);
            $transaction->save();
        }
        catch (\Exception $e){
            @Log::channel('err_doli')->alert(class_basename($this) . ' CREATE ' . $e->getMessage());
        }
    }

    public function update($doli_id, $status)
    {
        try {
            return DoliTransactions::where("doli_id", $doli_id)->update(["state" => $status]);
        }
        catch (\Exception $e){
            @Log::channel('err_doli')->alert(class_basename($this) . ' UPDATE ' . $e->getMessage());
        }
    }

}