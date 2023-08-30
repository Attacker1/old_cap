<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// BackOffice Side
Route::domain(config('config.APP_SUBDOMAIN_BACKOFFICE') . '.' . config('app.url'))->group(function () {
    include "admin.php";
});

Route::domain(config('config.APP_SUBDOMAIN_CLIENTS') . '.' . config('app.url'))->group(function () {
    include "admin_clients.php";
});

Route::domain(config('config.APP_SUBDOMAIN_OTHER_CLIENTS') . '.' . config('app.url'))->group(function () {
    include "admin_clients.php";
});


// API
Route::domain(config('config.APP_SUBDOMAIN_ADMIN_API') . '.' . config('app.url'))->group(function () {
    include "admin_api.php";
});
// Anketa frontend
Route::domain(config('config.APP_SUBDOMAIN_ANKETA') . '.' . config('app.url'))->group(function () {
    Route::get('/success', 'AdminClients\AnketaController@success');

//    Route::get('/cookie/get', 'Vuex\Anketa\AnketaController@getCookie');

    Route::post("questions", ["as" => "admin.vuex.anketa", "uses" => 'Vuex\Anketa\AnketaController@vuex']);

//    Route::get('/set-slug/set', 'Vuex\Anketa\AnketaController@setCookie');
    Route::any('{any?}', ["as" => "anketa.frontend", "uses" => 'Vuex\Anketa\AnketaController@index'])
        ->where('any', '^(?!api).*$');
});

// Новый интерфейс
include "auth_free.php";

