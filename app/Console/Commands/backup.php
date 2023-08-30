<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class common
 * @package App\Console\Commands
 */
class backup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание бэкапа';


    /**
     * backup constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // TODO: Перенес в CRON DAILY
        // Этот файл оставим для создания папки на серваках

    }
}
