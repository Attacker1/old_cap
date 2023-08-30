<?php

//Управление клиентами

Route::any('clients/list', [
    "as" => "clients.list",
    "uses" => 'Admin\ClientsController@list'])->middleware('role:,manage-clients');

Route::any('clients/edit/{uuid}', [
    "as" => "clients.edit",
    "uses" => 'Admin\ClientsController@edit'])->middleware('role:,manage-clients');

Route::any('clients/delete/{uuid?}', [
    "as" => "clients.delete",
    "uses" => 'Admin\ClientsController@destroy'])->middleware('role:,manage-clients');

Route::any('clients/password/update/{uuid?}', [
    "as" => "clients.password.update",
    "uses" => 'Admin\ClientsController@updatePassword'])->middleware('role:,manage-clients');

Route::any('clients/show/{uuid}', [
    "as" => "clients.show",
    "uses" => 'Admin\ClientsController@show'])->middleware('role:,view-clients');

Route::any('clients/bonus/update/{uuid}', [
    "as" => "clients.bonus.update",
    "uses" => 'Admin\ClientsController@updateBonus'])->middleware('role:,manage-clients');

//Route::post('clients/list', [
//    "as" => "admin.clients.list.fill",
//    "uses" => 'Admin\ClientsController@index'])

// Под метроник эксперимент
//Route::any('clients/index', [
//    "as" => "admin.clients.index",
//    "uses" => 'Admin\ClientsController@list'])->middleware('role:,manage-clients');

Route::get('clients/list-reset-settings', [
    "as" => "admin.clients.list.reset-datatable-settings",
    "uses" => 'Admin\ClientsController@reset_datatable_settings']);

Route::resource('clients', 'Admin\ClientsController');

//Управление статусами клиентов

Route::get('clients-statuses/list', [
    "as" => "admin.clients-statuses.list",
    "uses" => 'Admin\ClientsStatusesController@index']);

Route::resource('clients-statuses', 'Admin\ClientsStatusesController');


//Обратная связь
Route::group(['prefix' => 'feedback'], function () {
    Route::any('list', [
        "as" => "admin.feedback.list.fill",
        "uses" => 'Admin\FeedbackController@list']);
    Route::get('list-reset-settings', [
        "as" => "admin.feedback.list.reset-datatable-settings",
        "uses" => 'Admin\FeedbackController@reset_datatable_settings']);
});
Route::resource('feedback', 'Admin\FeedbackController');


//Анкеты
Route::group(['prefix' => '/anketa'], function () {

    Route::get('test', function (){
        $collection = new \App\Http\Classes\DataConversion\AnketaXlsShort(10, 1);
        $t = $collection->make();
        dump($t);
    });



    Route::any('xls-list-whole', [
        "as" => "admin.anketa.xsl-list.whole",
        "uses" => 'Admin\AnketaController@XSLListingWhole']);

    Route::any('xls-list-short', [
        "as" => "admin.anketa.xsl-list.short",
        "uses" => 'Admin\AnketaController@XSLListingShort']);

    Route::get('ttt', [
        "as" => "admin.anketa.ttt",
        "uses" => 'Admin\AnketaController@listing']);

    Route::any('list', [
        "as" => "admin.anketa.list.fill",
        "uses" => 'Admin\AnketaController@list']);


    Route::get('list-reset-settings', [
        "as" => "admin.anketa.list.reset-datatable-settings",
        "uses" => 'Admin\AnketaController@reset_datatable_settings']);

    Route::any('/listing/{ajax?}', [
        "as" => "admin.anketa.listing",
        "uses" => 'Admin\AnketaController@listing'])->middleware('role:,anketa-support');


    Route::any('/payments/{client_uuid}', [
        "as" => "admin.anketa.payments",
        "uses" => 'Admin\AnketaController@payments']);

    Route::any('/ajax/{client_uuid}', [
        "as" => "admin.anketa.ajax",
        "uses" => 'Admin\AnketaController@ajax']);

    Route::any('/feedback/{client_uuid}', [
        "as" => "admin.anketa.feedback",
        "uses" => 'Admin\AnketaController@feedback']);

        Route::group(['prefix' => 'tabs'], function () {
            Route::resource('support-photo', 'Admin\Anketa\TabPhotoSupportController', [
                'as' => 'admin.anketa.tabs'
            ]);
            Route::resource('reference','Admin\Anketa\TabReferenceController', [
                'as' => 'admin.anketa.tabs'
            ]);
        });

});

Route::resource('anketa', 'Admin\AnketaController');

//Повторные анкеты
Route::group(['prefix' => '/reanketa'], function () {
    Route::any('list', [
        "as" => "admin.reanketa.list.fill",
        "uses" => 'Admin\ReanketaController@list']);

    Route::get('test/{anketa_uuid}', [
        "as" => "admin.reanketa.show.test",
        "uses" => 'Admin\ReanketaController@testShow']);

    //admin.reanketa.show.testQuestions

    Route::get('test-q', [
        "as" => "admin.reanketa.show.testQuestions",
        "uses" => 'Admin\ReanketaController@testQuestions']);
});

//старые анкеты
Route::get('old-anketa/{amo_id}', 'Admin\AnketaController@show_old');

Route::resource('reanketa', 'Admin\ReanketaController');

//переносы БД
Route::group(['prefix' => '/transitions-temp', 'middleware' => 'role:developer'], function () {
    Route::get('clients/format-transition-data-delete-duplicates', 'AdminClients\Transitions\Clients\DeleteDuplicatesController@main');
    Route::get('clients/format-data', 'AdminClients\Transitions\Clients\FormatDataController@main');
    Route::get('clients/format-transition-data', 'AdminClients\Transitions\Clients\TransitionDataController@main');
    Route::get('clients/set-promocode', 'AdminClients\Transitions\Clients\TransitionDataController@setPromocode');
    Route::get('feedback/feedback_main', 'AdminClients\Transitions\Feedback\TransitionDataController@feedback_main');
    Route::get('feedback/feedback', 'AdminClients\Transitions\Feedback\TransitionDataController@feedback');
    Route::get('feedback/relation', 'AdminClients\Transitions\Feedback\TransitionDataController@set_relation');
    //Фидбек клиента обработка
    Route::get('handleFb-hgyty7yhjkjnj', 'AdminClients\Transitions\Feedback\HandleClientController@make');
});

