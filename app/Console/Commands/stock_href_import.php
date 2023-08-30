<?php

namespace App\Console\Commands;

use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Common\Lead;
use App\Http\Models\Stock\StockProducts;
use App\Services\Stock\StockProduct;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use WildTuna\BoxberrySdk\Exception\BoxBerryException;

/**
 * Stock Product Import
 * Class common
 * @package App\Console\Commands
 */

class stock_href_import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:stock_href_import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Link urls Import';

    private $start;

    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->start = microtime(true);
    }


    public function handle()
    {

        $product = new StockProduct();
        $product->importImagesUri();
        dd(self::execution());

    }


    private function execution(){
        return round(microtime(true) - $this->start) .' секунд';
    }



}
