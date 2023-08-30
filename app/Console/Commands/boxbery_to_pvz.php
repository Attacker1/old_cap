<?php

namespace App\Console\Commands;

use App\Http\Controllers\Classes\BoxberryApi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Boxberry
 * Class common
 * @package App\Console\Commands
 */

class boxbery_to_pvz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:boxbery_to_pvz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Boxberry: Посылка дошла до ПВЗ (статус в сделке)';


    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $bb = new BoxberryApi();
        $bb->deliveredToPvz();
    }



}
