<?php

namespace App\Console\Commands;

use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Models\Admin\ServiceToken;
use App\Http\Models\Common\Lead;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use WildTuna\BoxberrySdk\Exception\BoxBerryException;

/**
 * Boxberry
 * Class common
 * @package App\Console\Commands
 */

class bb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:bb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Boxberry';


    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {



    }



}
