<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/users', function (Request $request) {
    return $request->user();
});

// Protected with APIToken Middleware
Route::group(['middleware' => ['admin.api']], function() {

    // Клиентский раздел
    Route::group(['prefix' => '/client'], function() {

        Route::post("/", [
            "as" => "admin.api.clients",
            "uses" => 'Api\ClientsApiController@index']);

        Route::get("/statuses", [
            "as" => "admin.api.clients.statuses",
            "uses" => 'Api\ClientsApiController@statuses']);
    });

    //анкеты клиентов
    Route::group(['prefix' => '/anketa'], function() {

        Route::get("{code}", [
            "uses" => 'Api\AnketaApiController@getItem']);
        Route::post("/", [
            "uses" => 'Api\AnketaApiController@index']);
    });

    // купоны
    Route::group(['prefix' => '/coupon'], function() {
        Route::post("/", [
            "uses" => 'Api\CouponsApiController@index']);
    });

    // Единый функционал для Clients и Leads
    Route::group(['prefix' => '/client-lead'], function() {

        Route::post("/", [
            "as" => "admin.api.clients",
            "uses" => 'Api\ClientsLeadsCommonApiController@make']);
    });

});
