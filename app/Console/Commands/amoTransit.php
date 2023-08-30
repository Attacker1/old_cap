<?php

namespace App\Console\Commands;

use App\Http\Controllers\Classes\AmoCrm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;


/**
 * Amo Пернос сделок
 * Class common
 * @package App\Console\Commands
 */

class amoTransit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:transit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transit AMO deliveries';


    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {

        $amo = new AmoCrm();
        $count = $amo->transitDelivery();
        Log::channel('amo')->info("Перенесено сделок из 'ГОТОВО к отправке' в 'Отгружено' :" . $count);

    }



}
