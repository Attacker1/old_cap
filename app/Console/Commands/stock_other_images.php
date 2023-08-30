<?php

namespace App\Console\Commands;

use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Common\Lead;
use App\Http\Models\Stock\StockProducts;
use App\Services\Stock\StockImages;
use App\Services\Stock\StockProduct;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use WildTuna\BoxberrySdk\Exception\BoxBerryException;

/**
 * Stock Other Images Import
 * Class common
 * @package App\Console\Commands
 */

class stock_other_images extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:stock_other_images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stock Other Images Import';

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

        $handle = new StockImages();
        $processed_items = $handle->backgroundDownloadOtherImages();
        dd($processed_items,self::execution());

    }

    private function cleanTemp(){
        $temp_dir = storage_path() . "/app/public/stock_temp_images";
        $file = new Filesystem();
        $file->cleanDirectory($temp_dir);
    }

    private function execution(){
        return round(microtime(true) - $this->start) .' секунд';
    }



}
