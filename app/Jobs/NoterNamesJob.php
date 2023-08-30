<?php

namespace App\Jobs;


use App\Http\Classes\Common;
use App\Http\Models\Catalog\Product;
use App\Http\Models\Common\Lead;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;

class NoterNamesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $item;


    /**
     * NoterNamesJob constructor.
     * @param Lead $item
     */
    public function __construct(Lead $item)
    {
        $this->item = $item;
    }


    public function handle()
    {

        if (!$api = Common::importByOrderId($this->item->amo_lead_id))
            return false;

        if (!empty($api->items)) {
            foreach ($api->items as $item) {

                if ($product = Product::where('external_id', $item->id)->first()) {

                    $product->amo_name = @$item->name ?? '-';
                    $product->save();

                }
            }

        }

    }


}
