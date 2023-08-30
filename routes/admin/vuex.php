<?php

// ресурсы для vuex (api)

Route::group(['prefix' => 'vuex'], function () {

    Route::post("settings", ["as" => "admin.vuex.settings", "uses" => 'Vuex\SettingsController@vuex']);

    /** \App\Http\Controllers\Vuex\SandBox::class */
    if (config("app.env") != "production")
        Route::any("sandbox", ["as" => "admin.vuex.sandbox", "uses" => 'Vuex\SandBox@vuex']);
    // sandbox для тестов -> http://stage-0.thecapsula.loc/admin/vuex/sandbox?func=index&anketaSlug=b

// Anketa
    Route::group(['prefix' => 'anketa'], function () {
        /** \App\Http\Controllers\Vuex\Anketa\AnketaBuilderController::class */
        Route::post("builder", ["as" => "admin.vuex.anketa.builder", "uses" => 'Vuex\Anketa\AnketaBuilderController@vuex']);

        /** \App\Http\Controllers\Vuex\Anketa\AnketaController::class  */
        Route::post("questions", ["as" => "admin.vuex.anketa", "uses" => 'Vuex\Anketa\AnketaController@vuex']);

        /** \App\Http\Controllers\Vuex\Anketa\AnketaQuestionsController::class  */
//        Route::post("manager", ["as" => "admin.vuex.anketa.questions", "uses" => 'Vuex\Anketa\AnketaController@vuex']);
        Route::post("question-manager", ["as" => "admin.vuex.anketa.questions", "uses" => 'Vuex\Anketa\AnketaQuestionsController@vuex']);

        Route::post("improve-quests", ["as" => "admin.vuex.anketa.builder", "uses" => 'Vuex\Anketa\DBConvert@vuex']);

        Route::post("questions-converter", ["as" => "admin.vuex.anketa.converter", "uses" => 'Vuex\Anketa\AnketaConverter@vuex']);
    });

// Stock
    Route::group(['prefix' => 'stock'], function () {
        /** Товары
         * \App\Http\Controllers\Vuex\Stock\StockProductController::class */
        Route::post("product", ["as" => "stock.product", "uses" => 'Vuex\Stock\StockProductController@vuex']);

        /** Корзина
         * \App\Http\Controllers\Vuex\Stock\StockCartController::class */
        Route::post("cart", ["as" => "stock.cart", "uses" => 'Vuex\Stock\StockCartController@vuex']);

        /** Атрибуты
         * \App\Http\Controllers\Vuex\Stock\StockAttributeController::class */
        Route::post("attribute", ["as" => "stock.attribute", "uses" => 'Vuex\Stock\StockAttributeController@vuex']);

        /** Лайки
         * \App\Http\Controllers\Vuex\Stock\StockLikeController::class */
        Route::post("like", ["as" => "stock.like", "uses" => 'Vuex\Stock\StockLikeController@vuex']);

        // Фото
        Route::any("image/{uuid}", ["as" => "stock.image", "uses" => 'Vuex\Stock\StockProductController@image']);

    });
// Leads
    Route::group(['prefix' => 'lead'], function () {
        Route::any("", ["as" => "admin.vuex.lead", "uses" => 'Vuex\Lead\LeadController@vuex']);
    });

});

// Корневые Views
Route::group(['prefix' => 'v'], function () {
    Route::any('index', ["as" => "admin.v.index", "uses" => 'Vuex\IndexController@index']);
//
    Route::any('lead{any}', ["as" => "admin.v.lead", "uses" => 'Vuex\Lead\LeadController@index'])->where('any', '^(?!api).*$');


    Route::any('stock{any}', ["as" => "admin.v.index", "uses" => 'Vuex\Stock\StockProductController@index'])->where('any', '^(?!api).*$');

    Route::group(['prefix' => 'settings'], function () {
        Route::any('anketas{any}', ["as" => "admin.v.anketas", function () {
            return view('vuex.anketa.builder');
        }])->where('any', '^(?!api).*$');
    });
});
/** Заглушка пока метроникс работает */
//Route::any('v-index',["as" => "admin.v-index", function() {return view('vuex.index');}]);
Route::any('anketas', ["as" => "admin.anketas", function () {
    return view('vuex.anketa.builder');
}]);



//stock
if (config("app.env") != "production") {
    Route::any('stock{any}', ["as" => "admin.stock.index", function () {
        return view('vuex.stock.frontend');
    }])->where('any', '^(?!api).*$');
}

// Перевод роутинга на vue
//Route::any("{any}", ["as" => "admin.api.vuex.anketas", function() {
//    return view('vuex.builder');
//}])->where('any', '^(?!api).*$');
