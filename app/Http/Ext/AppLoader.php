<?php

namespace App\Http\Ext;
use Illuminate\Http\Request;

chdir('../');
require 'vendor/autoload.php';

/**
 * Загрузчик классов
 * Class AppLoader
 * @package App\Http\Ext
 */
class AppLoader
{

    /**
     * AppLoader constructor.
     */
    public function __construct()
    {
        //загружаем контейнер приложения
        $app = require 'bootstrap/app.php';
        $request = Request::capture();
        $app->instance('request', $request);
        $app->instance('validator ', Illuminate\Support\Facades\Validator::class );

        $app->bootstrapWith([
            \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
            \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
            \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
            \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
            \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
            \Illuminate\Foundation\Bootstrap\BootProviders::class,
        ]);
    }



}
