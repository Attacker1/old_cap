<?php

// ДОЛИ: Вебхуки и страницы
Route::group(['prefix' => '/doli'], function () {

    Route::any('/webhook/{feedback_uuid?}', [
        "as" => "doli.webhook",
        "uses" => 'Classes\DoliApi@webhook']);

    Route::any('/fail/{feedback_uuid?}', [
        "as" => "doli.page.fail",
        "uses" => 'Classes\DoliApi@fail']);

    Route::any('/success/{feedback_uuid?}', [
        "as" => "doli.page.success",
        "uses" => 'Classes\DoliApi@success']);
});

// Мой Склад: Вебхуки
Route::group(['prefix' => '/moy-sklad'], function () {

    Route::any('/webhook/{uuid?}', [
        "as" => "moy_sklad.webhook",
        "uses" => '\App\Services\Stock\StockWebhooks@get']);
});