<?php

namespace App\Console;

use App\Console\Commands\bb;
use App\Http\Controllers\Classes\BoxberryApi;
use App\Http\Controllers\Classes\LogSysApi;
use App\Http\Controllers\Vuex\Stock\StockAttributeController;
use App\Http\Controllers\Vuex\Stock\StockCartController;
use App\Http\Models\Common\Lead;
use App\Http\Models\Common\LeadControl;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // AMO: из 'ГОТОВО к отправке' в 'Отгружено'
        $schedule->command('command:transit')
            ->dailyAt('21:00');

        // Stock: остатки
        $schedule->command('command:stock_product_quantity')
            ->hourlyAt(25);

        // Stock: импорт товаров и хар-к
        $schedule->command('command:stock_product_import')
            ->twiceDaily(9,19)
            ->withoutOverlapping();

        // Отслеживание заказа. Посылка дошла до ПВЗ (статус в сделке)
        $schedule->command('command:boxbery_to_pvz')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // Автоапдейт статуса "Одежда у клиента"
        $schedule->call(function () {
            $logsys = new LogSysApi();
            $logsys->run();
            Log::channel('cron')->info('Автоапдейт статуса "Одежда у клиента"');
        })->everyThirtyMinutes();

        $schedule->call(function () {
           Lead::hourlyFromState();
        })->everyMinute();


//        $schedule->call(function () {
//            $bb = new BoxberryApi();
//            $bb->leadStateToDelivering();
//            Log::channel('cron')->info('BOXBERRY: Смена статуса на "ОТГРУЖЕНО"');
//        })->everyThirtyMinutes();

        // Проверка на 2 статуса
        $schedule->call(function () {

            $bb = new BoxberryApi();
            $leads = $bb->getAmoLeadsTest(24838114); //
            $upd = $bb->deliveredTest($leads);
            Log::channel('cron')->info('Boxberry: Получение из Отгружена статусов -> Обработано: ' .  count($upd));

            $bb = new BoxberryApi();
            $leads = $bb->getAmoLeadsTest(45677761); // Из статуса ПВЗ,
            $upd = $bb->deliveredTest($leads);
            Log::channel('cron')->info('Boxberry: Получение из ПВЗ статусов -> Обработано: ' .  count($upd));

        })->everyThirtyMinutes();

        // Создание заказов в Боксбери - тестирование отслеживание
        $schedule->call(function () {
            $bb = new BoxberryApi();
            $bb->amoToBoxberry();
        })->everyFiveMinutes();

        // TODO: Вынести по аналогии в команды см выше - "command:transit"
        // Отслеживание сделок в статусе "Проблема с подбором"
        $schedule->call(function () {
            LeadControl::checkStylist();
        })->everyThirtyMinutes();

        // Обновление уникальных аттрибутов для товаров стока ДЛЯ ТЕСТА
        $schedule->call(function () {
            StockAttributeController::updateUniqueAttributes();
        })->cron('0 */6 * * *');

        // Проверка на истечение резервации корзины
        $schedule->call(function () {
            StockCartController::checkExpireReservation();
        })->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
