<?php

// Возможно описание по редиректу?
Route::get('/api/docs', [
"as" => "admin.api.login",
"uses" => 'Api\AdminApiAuthController@index']);

Route::any('/api/auth', [
"as" => "admin.api.auth",
"uses" => 'Api\AdminApiAuthController@authenticate']);


// Protected with APIToken Middleware
Route::group(['prefix' => '/api', 'middleware' => ['admin.api']], function() {

    // СМС.ру
    Route::any("/sms/send", [
        "as" => "admin.api.sms.send",
        "uses" => "\App\Http\Classes\Common@smsSend"]);

    // Управление Сделкой
    Route::group(['prefix' => '/lead'], function() {

        Route::any("/create", [
            "as" => "admin.api.lead.create",
            "uses" => "Api\ClientsLeadsCommonApiController@make"])->middleware('throttle:200,1');

        Route::any("/link", [
            "as" => "admin.api.lead.link",
            "uses" => "Api\LeadApiController@link"])->middleware('throttle:200,1');

        Route::any("/read", [
            "as" => "admin.api.lead.read",
            "uses" => "Api\LeadApiController@read"])->middleware('throttle:200,1');

        Route::post("/update", [
            "as" => "admin.api.lead.update",
            "uses" => "Api\LeadApiController@update"])->middleware('throttle:200,1');

        Route::any("/destroy", [
            "as" => "admin.api.lead.destroy",
            "uses" => "Api\LeadApiController@destroy"]);
    });

    // Управление Клиентами
    Route::group(['prefix' => '/clients'], function() {

        Route::any("/add", [
            "as" => "admin.api.client.add",
            "uses" => "Api\ClientsApiController@add"])->middleware('throttle:200,1');
    });


    // Оплата по сделке
    Route::group(['prefix' => '/payment'], function() {

        Route::any("/create", [
            "as" => "admin.api.payments.create",
            "uses" => "Api\PaymentsApiController@create"])->middleware('throttle:200,1');

        Route::any("/get", [
            "as" => "admin.api.payments.read",
            "uses" => "Api\PaymentsApiController@read"])->middleware('throttle:200,1');

    });

    // Почта
    Route::group(['prefix' => '/mail'], function() {
        Route::group(['prefix' => '/template'], function() {

            Route::any("/list", [
                "as" => "api.template.list",
                "uses" => "Api\MailTemplateApiController@list"]);

            Route::any("/add", [
                "as" => "api.template.add",
                "uses" => "Api\MailTemplateApiController@create"]);

            Route::any("/delete", [
                "as" => "api.template.delete",
                "uses" => "Api\MailTemplateApiController@destroy"]);
        });
    });

    // Рефферальная программа
    Route::group(['prefix' => '/refferal'], function() {

        Route::any("/register", [
            "as" => "api.refferal.register",
            "uses" => "Api\RefferalApiController@register"]);

        Route::any("/feedback/{uuid}", [
            "as" => "api.refferal.feedback",
            "uses" => "Api\RefferalApiController@feedback"]);

        Route::any("/activate/{code}", [
            "as" => "api.refferal.activate",
            "uses" => "Api\RefferalApiController@activate"]);

        Route::get("/check/{code}", [
            "as" => "api.refferal.check",
            "uses" => "Api\RefferalApiController@check"]);

    });


    // SBER Пользователи
    Route::resource('user', 'Api\Sber\UserController')->only(['show','store','update',]);
    // SBER Fake Анкета
    Route::put('user/{uuid}/questionnaire', 'Api\Sber\AnketsController@update');
    // SBER Заказы/Сделки
    Route::patch('user/{uuid}/order/{id}', 'Api\Sber\OrdersController@edit');
    Route::resource('user/{uuid}/order', 'Api\Sber\OrdersController')->only([
        'index', 'show', 'store', 'update','destroy'
    ]);

    Route::resource('order/{uuid}/state', 'Api\Sber\OrdersStateController')->only([
        'index', 'update'
    ]);
    Route::resource('promos', 'Api\Sber\PromosController')->only(['show']);



});
