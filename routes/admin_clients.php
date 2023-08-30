<?php

use App\Http\Controllers\Common\SandboxController;

Route::get('/login', [
    "as" => "admin-clients.auth.showlogin",
    "uses" => 'AdminClients\Auth\LoginController@showLoginForm'])->middleware('throttle:5,3');

Route::get('/impersonate/token/{token}', [
    "as" => "admin-clients.auth.impersonate",
    "uses" => 'AdminClients\Auth\LoginController@impersonate'])->where('token', '[a-z0-9\-]*');

Route::post('/login-sms', [
    "as" => "admin-clients.auth.login-sms",
    "uses" => 'AdminClients\Auth\LoginController@loginSms']);

Route::post('/get-sms-code', [
    "as" => "admin-clients.auth.get-sms-code",
    "uses" => 'AdminClients\Auth\LoginController@getSms']);

Route::group(['middleware' => ['auth.admin-clients']], function() {

    Route::post('/logout', [
        "as" => "admin-clients.auth.logout",
        "uses" => 'AdminClients\Auth\LoginController@logout']);

    Route::get('/', [
        "as" =>'admin-clients.main.index',
        "uses" => 'AdminClients\MainController@index']);

    Route::get('/feedback/new/{lead_uuid?}', [
        "as" => "admin-clients.feedback.create",
        "uses" => 'AdminClients\FeedBackController@create']);

    Route::get('/feedback/edit/{feedback_uuid?}', [
        "as" => "admin-clients.feedback.edit",
        "uses" => 'AdminClients\FeedBackController@edit']);

    Route::post('/feedback/store', [
        "as" => "admin-clients.feedback.store",
        "uses" => 'AdminClients\FeedBackController@store']);

    Route::get('/feedback-pay/{lead_id}', [
        "as" => "admin-clients.payment.show",
        "uses" => 'AdminClients\PaymentController@show']);

    Route::post('/feedback-pay-bonuses/store', [
        "as" => "admin-clients.payment.bonuses-store",
        "uses" => 'AdminClients\PaymentController@bonuses_store']);


    Route::any('/feedback-payment-method/{lead_uuid}', [
        "as" => "admin-clients.payment.show-method",
        "uses" => 'AdminClients\PaymentController@show_method']);

    Route::get('/feedback-payment-doli/{fb_uuid}', [
        "as" => "admin-clients.payment.method-doli",
        "uses" => 'AdminClients\PaymentController@method_doli']);

    Route::get('/feedback-send-payment/{order_uuid}', [
        "as" => "admin-clients.payment.send",
        "uses" => 'AdminClients\PaymentController@send']);

    Route::get('/feedback', [
        "as" => "admin-clients.feedback.index",
        "uses" => 'AdminClients\FeedBackController@index']);

    Route::get('/orders', [
        "as" => "admin-clients.orders.list",
        "uses" => 'AdminClients\OrdersController@index']);

    Route::get('/repeat-order', [
        "as" => "admin-clients.repeat-order",
        "uses" => 'AdminClients\OrdersController@repeat_order']);

    Route::get('/bonuses', [
        "as" => "admin-clients.bonuses.index",
        "uses" => 'AdminClients\BonusesRefController@index']);

    Route::group(['prefix' => '/repeate-anketa'], function() {

        Route::any('/new', [
            "as" => "admin-clients.reanketa.new",
            "uses" => 'AdminClients\ReanketaController@new']);

        Route::post('/save', [
            "as" => "admin-clients.reanketa.save",
            "uses" => 'AdminClients\ReanketaController@save']);

        Route::post('/get-other-cities', [
            "as" => "admin-clients.reanketa.get-other-cities",
            "uses" => 'AdminClients\ReanketaController@getOtherCities']);

        Route::post('/create-lead', [
            "as" => "admin-clients.reanketa.create-lead",
            "uses" => 'Vuex\Anketa\AnketaController@temp_reanketa_lead']);

        Route::post('/client-update', [
            "as" => "admin-clients.reanketa.client-update",
            "uses" => 'Vuex\Anketa\AnketaController@temp_reanketa_client']);

        Route::post('/check-coupon', [
            "as" => "admin-clients.reanketa.check-coupon",
            "uses" => 'AdminClients\ReanketaController@checkCoupon']);

    });


});

Route::get('/pay/{id}', function ($id) {
    return redirect("https://custbase.thecapsula.ru/pay/$id");
});

Route::get('/oplata{paid}', function ($paid) {
    return redirect("https://custbase.thecapsula.ru/oplata".$paid);
});

// Анкета
Route::group(['prefix' => '/anketa'], function() {

    Route::get('/view', [
        "as" => "admin-clients.anketa.show",
        "uses" => 'AdminClients\AnketaController@show']);
    
});


Route::any('register/applePay', [
    "as" => "sandbox",
    "uses" => 'Common\SandboxController@index']);


//Route::get('/register/applePay', function () {
//    dd(SandboxController::registerApplePay());
//});
