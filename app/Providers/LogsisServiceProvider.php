<?php

namespace App\Providers;

use App\Services\Deliveries\Logsis;
use Illuminate\Support\ServiceProvider;

class LogsisServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Logsis', function() {
            return new Logsis();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}