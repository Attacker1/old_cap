<?php

Route::get('/', [
    "as" => "admin.login",
    "uses" => 'Admin\AdminAuthController@index'])->middleware('admin.login.check');

Route::get('/admin', function () {
    return redirect()->route('admin.login');
});

Route::any('/admin/auth', [
    "as" => "admin.login.auth",
    "uses" => 'Admin\AdminAuthController@authenticate']);

Route::any('/admin/amocrm', [
    "as" => "admin.amocrm",
    "uses" => 'Classes\AmoCrm@get_token']);

Route::any('/admin/payments/webhook', [
    "as" => "payments.webhook",
    "uses" => 'Common\PaymentsController@webhook']);

// вебхук для тестовых платежей
Route::any('/pay/gate-tinkoff', [
    "as" => "payments.webhook-test",
    "uses" => 'Common\PaymentsController@webhookTest']);

// Ссылка на оплату
Route::any('/pay/order/{uuid}', [
    "as" => "leads.pay.link",
    "uses" => 'Common\LeadController@payLink']);

Route::any('/pay/order/{uuid}', [
    "as" => "leads.pay.link",
    "uses" => 'Common\LeadController@payLink']);

Route::group(['prefix' => '/admin', 'middleware' => ['auth.admin']], function () {

    // Sanbox Lara
    if (config("app.env") != "production")
        Route::any('/sandbox', [
            "as" => "sandbox",
            "uses" => 'Common\SandboxController@index']);

    Route::get('/logout', [
        "as" => "admin.logout",
        "uses" => 'Admin\AdminAuthController@logout']);

    Route::get('/dashboard', [
        "as" => "admin.main.index",
        "uses" => 'Admin\MainController@index']);

    Route::get('/dashboard', [
        "as" => "admin.main.index",
        "uses" => 'Admin\MainController@index']);

    // Управление Пользователями
    Route::group(['prefix' => '/manage/users', 'middleware' => 'role:developer,manage-users'], function () {

        Route::any('/index', [
            "as" => "admin.manage.users.index",
            "uses" => 'Admin\UsersController@index']);

        Route::any('/create', [
            "as" => "admin.manage.users.create",
            "uses" => 'Admin\UsersController@create']);

        Route::any('/edit/{user}', [
            "as" => "admin.manage.users.edit",
            "uses" => 'Admin\UsersController@edit']);

        Route::any('/destroy/{user}', [
            "as" => "admin.manage.users.destroy",
            "uses" => 'Admin\UsersController@destroy']);

    });

    // Управление категориями
    Route::group(['prefix' => '/catalog/categories', 'middleware' => 'role:developer,catalog-manage'], function () {

        Route::any('/index', [
            "as" => "admin.catalog.categories.index",
            "uses" => 'Catalog\CategoriesController@index']);

        Route::any('/create', [
            "as" => "admin.catalog.categories.create",
            "uses" => 'Catalog\CategoriesController@create']);

        Route::any('/import', [
            "as" => "admin.catalog.categories.import",
            "uses" => 'Catalog\CategoriesController@import']);

        Route::any('/edit/{category}', [
            "as" => "admin.catalog.categories.edit",
            "uses" => 'Catalog\CategoriesController@edit']);

        Route::any('/destroy/{category}', [
            "as" => "admin.catalog.categories.destroy",
            "uses" => 'Catalog\CategoriesController@destroy']);

    });

    // Управление Товарами
    Route::group(['prefix' => '/catalog/products', 'middleware' => 'role:developer,catalog-manage'], function () {

        Route::any('/index', [
            "as" => "admin.catalog.products.index",
            "uses" => 'Catalog\ProductsController@index']);

        Route::any('/create', [
            "as" => "admin.catalog.products.create",
            "uses" => 'Catalog\ProductsController@create']);

        Route::any('/test', [
            "as" => "admin.catalog.products.test",
            "uses" => 'Catalog\ProductsController@test']);

        Route::any('/show/{item}', [
            "as" => "admin.catalog.products.show",
            "uses" => 'Catalog\ProductsController@show']);

        Route::any('/edit/{item}', [
            "as" => "admin.catalog.products.edit",
            "uses" => 'Catalog\ProductsController@edit']);

        Route::any('/attributes/{category}/{product}', [
            "as" => "admin.catalog.products.attributes",
            "uses" => 'Catalog\ProductsController@getAttributes'])->where('cat_id', '[0-9]+');

        Route::any('/destroy/{item}', [
            "as" => "admin.catalog.products.destroy",
            "uses" => 'Catalog\ProductsController@destroy']);

        Route::any('/search-tag', [
            "as" => "admin.catalog.products.tag.search",
            "uses" => 'Catalog\ProductsController@searchTag']);

    });

    // Управление Характеристиками
    Route::group(['prefix' => '/catalog/attributes', 'middleware' => 'role:developer'], function () {

        Route::any('/index', [
            "as" => "admin.catalog.attributes.index",
            "uses" => 'Catalog\AttributesController@index']);

        Route::any('/create', [
            "as" => "admin.catalog.attributes.create",
            "uses" => 'Catalog\AttributesController@create']);

        Route::any('/edit/{item}', [
            "as" => "admin.catalog.attributes.edit",
            "uses" => 'Catalog\AttributesController@edit']);

        Route::any('/destroy/{item}', [
            "as" => "admin.catalog.attributes.destroy",
            "uses" => 'Catalog\AttributesController@destroy']);

    });

    // Управление Пресетами
    Route::group(['prefix' => '/catalog', 'middleware' => 'role:developer,manage-presets'], function () {

        Route::resource('presets', Catalog\PresetsController::class, [
            'as' => 'admin.catalog',
            'except' => ['update', 'show', 'destroy', 'edit']
        ]);

        Route::any('/presets/dt', [
            "as" => "admin.catalog.presets.dt",
            "uses" => 'Catalog\PresetsController@dt']);

        Route::post('/presets/update/{item}', [
            "as" => "admin.catalog.presets.update",
            "uses" => 'Catalog\PresetsController@update']);

        Route::any('/presets/edit/{item}', [
            "as" => "admin.catalog.presets.edit",
            "uses" => 'Catalog\PresetsController@edit']);

        Route::get('/presets/destroy/{item}', [
            "as" => "admin.catalog.presets.destroy",
            "uses" => 'Catalog\PresetsController@destroy']);


    });

    // Управление Брендами
    Route::group(['prefix' => '/catalog/brands', 'middleware' => 'role:developer'], function () {

        Route::any('/index', [
            "as" => "admin.catalog.brands.index",
            "uses" => 'Catalog\BrandsController@index']);

        Route::any('/create', [
            "as" => "admin.catalog.brands.create",
            "uses" => 'Catalog\BrandsController@create']);

        Route::any('/edit/{brand}', [
            "as" => "admin.catalog.brands.edit",
            "uses" => 'Catalog\BrandsController@edit']);

        Route::any('/destroy/{brand}', [
            "as" => "admin.catalog.brands.destroy",
            "uses" => 'Catalog\BrandsController@destroy']);

    });

    // Управление доп. фотками
    Route::group(['prefix' => '/manage/stock', 'middleware' => 'role:developer'], function () {
        Route::any('/import/other-images', [
            "as" => "stock.other_images.import",
            "uses" => '\App\Services\Stock\StockImages@importOtherImagesXls']);
    });
        // Управление Ролями
    Route::group(['prefix' => '/manage/roles', 'middleware' => 'role:developer'], function () {

        Route::any('/index', [
            "as" => "manage.roles.index",
            "uses" => 'Admin\RolesController@index']);

        Route::any('/create', [
            "as" => "manage.roles.create",
            "uses" => 'Admin\RolesController@create']);

        Route::any('/edit/{role}', [
            "as" => "manage.roles.edit",
            "uses" => 'Admin\RolesController@edit']);

        Route::any('/destroy/{role}', [
            "as" => "manage.roles.destroy",
            "uses" => 'Admin\RolesController@destroy']);

    });

    // Управление Правами
    Route::group(['prefix' => '/manage/permissions', 'middleware' => 'role:developer'], function () {

        Route::any('/index', [
            "as" => "manage.permissions.index",
            "uses" => 'Admin\PermissionController@index']);

        Route::any('/create', [
            "as" => "manage.permissions.create",
            "uses" => 'Admin\PermissionController@create']);

        Route::any('/edit/{permission}', [
            "as" => "manage.permissions.edit",
            "uses" => 'Admin\PermissionController@edit']);

        Route::any('/destroy/{permission}', [
            "as" => "manage.permissions.destroy",
            "uses" => 'Admin\PermissionController@destroy']);
    });


    // Записка стилиста
    Route::group(['prefix' => '/note', 'middleware' => 'role:developer,view-notes'], function () {

        Route::any('/list', [
            "as" => "notes.list",
            "uses" => 'Catalog\NotesController@list']);

        Route::any('/print/{item}', [
            "as" => "notes.product.print",
            "uses" => 'Catalog\NotesController@print'])->middleware('role:false,print-notes');

        Route::any('/list/create', [
            "as" => "notes.list.create",
            "uses" => 'Catalog\NotesController@create'])->middleware('role:,edit-notes');

        Route::any('/index/{note_id?}', [
            "as" => "notes.index",
            "uses" => 'Catalog\NotesController@index'])->where('product_id', '[0-9]+')->middleware('role:,edit-notes');

        Route::any('/add/{product_id?}', [
            "as" => "notes.add",
            "uses" => 'Catalog\NotesController@add'])->where('product_id', '[0-9]+')->middleware('role:,manage-notes');

        Route::any('/remove/{item}/{product_id?}', [
            "as" => "notes.remove",
            "uses" => 'Catalog\NotesController@remove'])->where('product_id', '[0-9]+')->middleware('role:,manage-notes');

        Route::any('/save/{item}', [
            "as" => "notes.save",
            "uses" => 'Catalog\NotesController@save'])->middleware('role:,edit-notes');

        Route::any('/sort/{item}', [
            "as" => "notes.sort",
            "uses" => 'Catalog\NotesController@sort'])->middleware('role:,edit-notes');

        Route::any('/attach/{item}', [
            "as" => "notes.attach",
            "uses" => 'Catalog\NotesController@attach'])->middleware('role:,edit-notes');

        Route::any('/detach/{img_key}', [
            "as" => "notes.detach",
            "uses" => 'Catalog\NotesController@detach'])->middleware('role:,edit-notes');

        Route::any('/advice/{item}', [
            "as" => "notes.advice",
            "uses" => 'Catalog\NotesController@advice'])->middleware('role:,edit-notes');

        Route::any('/unset/{note}', [
            "as" => "notes.unset",
            "uses" => 'Catalog\NotesController@unset'])->middleware('role:,edit-notes');

        Route::any('/import/{note_id}', [
            "as" => "notes.import",
            "uses" => 'Catalog\NotesController@import'])->where('note_id', '[0-9]+')->middleware('role:,edit-notes');

        Route::any('/product/advice/{item}', [
            "as" => "notes.product.advice",
            "uses" => 'Catalog\NotesController@productAdvice'])->middleware('role:,edit-notes');

        Route::any('/edit/{item}', [
            "as" => "notes.edit",
            "uses" => 'Catalog\NotesController@edit'])->middleware('role:,edit-notes');

        Route::any('/delete/{item}', [
            "as" => "notes.delete",
            "uses" => 'Catalog\NotesController@delete'])->middleware('role:,destroy-notes');

        Route::any('/close/{item}', [
            "as" => "notes.close",
            "uses" => 'Catalog\NotesController@close'])->middleware('role:,manage-notes');

        Route::any('/custom-advice/{item}', [
            "as" => "notes.custom.advice",
            "uses" => 'Catalog\NotesController@customAdvice'])->middleware('role:,edit-notes');

        Route::any('/custom-unset/{note_id}', [
            "as" => "notes.custom.unset",
            "uses" => 'Catalog\NotesController@customUnset'])->where('note_id', '[0-9]+')->middleware('role:,edit-notes');

        Route::any('/replace-image', [
            "as" => "notes.replace.image",
            "uses" => 'Catalog\NotesController@replaceImage']);

    });

    // Обратная связь
    Route::group(['prefix' => '/feedback', 'middleware' => 'role:developer,viewing-feedback-quizes'], function () {
        Route::get('/list', [
            "as" => "feedback.list",
            "uses" => 'Catalog\FeedbackController@list']);
        Route::get('/show/{item}', [
            "as" => "feedback.show",
            "uses" => 'Catalog\FeedbackController@show']);
        Route::post('/delete/{item}', [
            "as" => "feedback.delete",
            "uses" => 'Catalog\FeedbackController@delete']);
    });

    // Раздел Оплаты
    Route::group(['prefix' => '/payments', 'middleware' => 'role:developer,view-payments'], function () {

        Route::any('/list/sberbank', [
            "as" => "payments.list.sberbank",
            "uses" => 'Common\PaymentsController@paymentSberbank']);
        Route::any('/list', [
            "as" => "payments.list",
            "uses" => 'Common\PaymentsController@list']);

        Route::get('/show/{id}', [
            "as" => "payments.edit",
            "uses" => 'Common\PaymentsController@show'])->where('id', '[0-9]+')->middleware('role:,edit-payments');
        Route::any('/delete/{id}', [
            "as" => "payments.destroy",
            "uses" => 'Common\PaymentsController@destroy'])->where('id', '[0-9]+')->middleware('role:,destroy-payments');

    });

    // Раздел Сделки
    Route::group(['prefix' => '/leads', 'middleware' => 'role:developer,view-leads'], function () {

        Route::post('/list/xsl-list', [
            "as" => "leads.list.xsl-list",
            "uses" => 'Common\LeadController@XSLListing']);

        Route::post('/list/change-tag', [
            "as" => "leads.list.change-tag",
            "uses" => 'Common\LeadController@changeTag']);

        Route::any('/list', [
            "as" => "leads.list",
            "uses" => 'Common\LeadController@list']);

        Route::post('/list/bulk', [
            "as" => "leads.list.bulk",
            "uses" => 'Common\LeadController@bulkAction']);

        Route::any('/create', [
            "as" => "leads.create",
            "uses" => 'Common\LeadController@create'])->middleware('role:,create-leads');

        Route::any('/store', [
            "as" => "leads.store",
            "uses" => 'Common\LeadController@store'])->middleware('role:,edit-leads');

        Route::any('/edit/{uuid?}', [
            "as" => "leads.edit",
            "uses" => 'Common\LeadController@edit'])->middleware('role:,edit-leads');

        Route::any('/delete/{uuid}', [
            "as" => "leads.destroy",
            "uses" => 'Common\LeadController@destroy'])->middleware('role:,destroy-leads');

        Route::any('/payments/{uuid?}', [
            "as" => "leads.payments",
            "uses" => 'Common\LeadController@payment'])->where('id', '[0-9]+');

        Route::any('/get/substates/{id?}', [
            "as" => "leads.states",
            "uses" => 'Common\LeadController@subStates'])->where('id', '[0-9]+');

        Route::post('/lead-description', [
            "as" => "leads.description",
            "uses" => 'Common\LeadController@description']);

        Route::any('/clientSearch', [
            "as" => "leads.search.client",
            "uses" => 'Common\LeadController@clientSearch']);

        Route::group(['prefix' => '/export', 'middleware' => 'role:developer,edit-leads'], function () {

            Route::get('/barcode', [
                "as" => "export.leads.barcode",
                "uses" => '\App\Services\Export\LeadExport@barcode']);
        });


    });

    // Работа с Цветами каталога
    Route::group(['prefix' => '/catalog/colors', 'middleware' => 'role:developer,catalog-manage'], function () {

        Route::any('/index', [
            "as" => "admin.color.index",
            "uses" => 'Catalog\ColorRefController@index']);

        Route::get('/add', [
            "as" => "admin.color.add",
            "uses" => 'Catalog\ColorRefController@create']);

        Route::post('/store', [
            "as" => "admin.color.store",
            "uses" => 'Catalog\ColorRefController@store']);

        Route::get('/edit/{id}', [
            "as" => "admin.color.edit",
            "uses" => 'Catalog\ColorRefController@edit'])->where('id', '[0-9]+');

        Route::post('/update/{id}', [
            "as" => "admin.color.update",
            "uses" => 'Catalog\ColorRefController@update'])->where('id', '[0-9]+');

        Route::any('/destroy/{id}', [
            "as" => "admin.color.destroy",
            "uses" => 'Catalog\ColorRefController@destroy'])->where('id', '[0-9]+');
    });

    // Мой Склад -------
    Route::group(['prefix' => '/sklad', 'middleware' => 'role:developer,view-leads'], function () {

        Route::any('/brands', [
            "as" => "sklad.list",
            "uses" => 'Common\MoySkladController@getBrands']);

        // Работа с Товарами
        Route::group(['prefix' => '/product', 'middleware' => 'role:developer,view-leads'], function () {

            Route::get('/search', [
                "as" => "sklad.products.search",
                "uses" => 'Common\MoySkladController@searchProduct']);

            Route::get('/create', [
                "as" => "sklad.products.create",
                "uses" => 'Common\MoySkladController@createProduct']);

            Route::get('/cats', [
                "as" => "sklad.products.cats",
                "uses" => 'Common\MoySkladController@getGroups']);
        });

        // Работа с Заказами
        Route::group(['prefix' => '/orders', 'middleware' => 'role:developer,view-leads'], function () {

            Route::get('/get', [
                "as" => "sklad.orders.get",
                "uses" => 'Common\MoySkladController@getOrder']);
        });

    });

    // Управление Бонусами
    Route::group(['prefix' => '/bonuses', 'middleware' => 'role:,manage-clients'], function () {

        Route::any('/index', [
            "as" => "bonuses.index",
            "uses" => 'Admin\BonusController@index']);

        Route::any('/create', [
            "as" => "bonuses.create",
            "uses" => 'Admin\BonusController@create']);

        Route::any('/store', [
            "as" => "bonuses.store",
            "uses" => 'Admin\BonusController@store']);

        Route::any('/edit/{id}', [
            "as" => "bonuses.edit",
            "uses" => 'Admin\BonusController@edit'])->where('id', '[0-9]+');

        Route::any('/update/{id}', [
            "as" => "bonuses.update",
            "uses" => 'Admin\BonusController@update'])->where('id', '[0-9]+');

    });


    // Справочник Бонусов
    Route::group(['prefix' => '/bonus/ref', 'middleware' => 'role:,manage-clients'], function () {

        Route::any('/index', [
            "as" => "bonus.ref.index",
            "uses" => 'Common\BonusRefController@index']);

        Route::any('/create', [
            "as" => "bonus.ref.create",
            "uses" => 'Common\BonusRefController@create']);

        Route::any('/store', [
            "as" => "bonus.ref.store",
            "uses" => 'Common\BonusRefController@store']);

        Route::any('/edit/{id}', [
            "as" => "bonus.ref.edit",
            "uses" => 'Common\BonusRefController@edit'])->where('id', '[0-9]+');

        Route::any('/update/{id}', [
            "as" => "bonus.ref.update",
            "uses" => 'Common\BonusRefController@update'])->where('id', '[0-9]+');

        Route::any('/destroy/{id}', [
            "as" => "bonus.ref.destroy",
            "uses" => 'Common\BonusRefController@destroy'])->where('id', '[0-9]+');

    });

    // Продукция Количество
    Route::group(['prefix' => '/products/quantity', 'middleware' => 'role:developer,catalog-manage'], function () {

        Route::any('/index', [
            "as" => "quantity.index",
            "uses" => 'Catalog\QuantityController@index']);

        Route::any('/create', [
            "as" => "quantity.create",
            "uses" => 'Catalog\QuantityController@create']);

        Route::any('/store', [
            "as" => "quantity.store",
            "uses" => 'Catalog\QuantityController@store']);

        Route::any('/edit/{id}', [
            "as" => "quantity.edit",
            "uses" => 'Catalog\QuantityController@edit'])->where('id', '[0-9]+');

        Route::any('/update/{id}', [
            "as" => "quantity.update",
            "uses" => 'Catalog\QuantityController@update'])->where('id', '[0-9]+');

        Route::any('/destroy/{id}', [
            "as" => "quantity.destroy",
            "uses" => 'Catalog\QuantityController@destroy'])->where('id', '[0-9]+');

        Route::any('/search', [
            "as" => "quantity.product.search",
            "uses" => 'Catalog\QuantityController@searchProduct']);

    });

    // Управление Сделками-Статусами по ролями
    Route::group(['prefix' => '/manage/lead/roles', 'middleware' => 'role:developer,manage-leadref'], function () {

        Route::any('/index', [
            "as" => "manage.leadref.index",
            "uses" => 'Admin\LeadRefRolesController@index']);

        Route::any('/edit/{id}', [
            "as" => "manage.leadref.edit",
            "uses" => 'Admin\LeadRefRolesController@edit'])->where('id', '[0-9]+');

    });

    // Управление Шаблонами почты
    Route::group(['prefix' => '/mail/template', 'middleware' => 'role:developer,manage-users'], function () {

        Route::any('/index', [
            "as" => "mail.templates.index",
            "uses" => 'Admin\MailController@index']);

        Route::any('/create', [
            "as" => "mail.templates.create",
            "uses" => 'Admin\MailController@create']);

        Route::any('/update/{id}', [
            "as" => "mail.templates.update",
            "uses" => 'Admin\MailController@update'])->where('id', '[0-9]+');

        Route::any('/destroy/{id}', [
            "as" => "mail.templates.edit",
            "uses" => 'Admin\MailController@edit'])->where('id', '[0-9]+');

    });

    // Тестирование различных событий и пр.
    Route::group(['prefix' => '/test', 'middleware' => 'role:developer,'], function () {

        Route::any('/triggers', [
            "as" => "test.triggers",
            "uses" => 'Common\TestController@amoTriggers']);

        Route::any('/php', [
            "as" => "test.php",
            "uses" => 'Common\TestController@php']);
    });

    // Статистика по сделкам
    Route::group(['prefix' => '/analytics/leads', 'middleware' => 'role:developer,view-leads'], function () {
        Route::any('/index', [
            "as" => "analytics.leads.index",
            "uses" => 'Common\LeadController@analytics']);
    });

    // Статистика по стилистам
    Route::group(['prefix' => '/analytics/stylist', 'middleware' => 'role:developer,view-leads'], function () {
        Route::any('/index', [
            "as" => "analytics.stylist.index",
            "uses" => 'Common\LeadController@stylist']);
    });

    //отчеты
    Route::group(['prefix' => '/report', 'middleware' => 'role:manager'], function () {
        Route::get('stylists-salary', 'Admin\ReportController@stylists_salary');
        Route::get('anketa', 'Admin\ReportController@anketa');
        Route::any('/pr', [ "as" => "analytics.pr-report.index", "uses" => 'Admin\ReportController@anketa_coupons_pr']);
    });

    // Управление Статусами клиентов
    Route::group(['prefix' => '/client/status', 'middleware' => 'role:developer,manage-users'], function () {

        Route::any('/index', [
            "as" => "client.statuses.index",
            "uses" => 'Admin\ClientStatusController@index']);

        Route::any('/create', [
            "as" => "client.statuses.create",
            "uses" => 'Admin\ClientStatusController@create']);

        Route::post('/create', [
            "as" => "client.statuses.store",
            "uses" => 'Admin\ClientStatusController@store']);

        Route::any('/edit/{id}', [
            "as" => "client.statuses.edit",
            "uses" => 'Admin\ClientStatusController@edit'])->where('id', '[0-9]+');

        Route::any('/update/{id}', [
            "as" => "client.statuses.update",
            "uses" => 'Admin\ClientStatusController@update'])->where('id', '[0-9]+');

        Route::any('/destroy/{id}', [
            "as" => "client.statuses.destroy",
            "uses" => 'Admin\ClientStatusController@destroy'])->where('id', '[0-9]+');

    });

    // Статусы для сбербанка
    Route::any('/sber/status/dt', [
        "as" => "sber.statuses.dt",
        "uses" => 'Admin\SberStatusController@dt'])->where('id', '[0-9]+');
    Route::resource('sber/status', Admin\SberStatusController::class, [
        "names" => "sber.statuses"]);


    // Управление Настройками для клиентской части
    Route::group(['prefix' => '/client/settings', 'middleware' => 'role:developer,manage-users'], function () {

        Route::any('/index', [
            "as" => "client.settings.index",
            "uses" => 'Common\ClientSettingsController@index']);

        Route::any('/create', [
            "as" => "client.settings.create",
            "uses" => 'Common\ClientSettingsController@create']);

        Route::post('/store', [
            "as" => "client.settings.store",
            "uses" => 'Common\ClientSettingsController@store']);

        Route::any('/edit/{id}', [
            "as" => "client.settings.edit",
            "uses" => 'Common\ClientSettingsController@edit'])->where('id', '[0-9]+');

        Route::any('/update/{id}', [
            "as" => "client.settings.update",
            "uses" => 'Common\ClientSettingsController@update'])->where('id', '[0-9]+');

        Route::any('/destroy/{id}', [
            "as" => "client.settings.destroy",
            "uses" => 'Common\ClientSettingsController@destroy'])->where('id', '[0-9]+');

    });

    // Управление Купонами
    Route::group(['prefix' => '/coupon', 'middleware' => 'role:developer,manage-coupon'], function () {

        Route::any('/index', [
            "as" => "coupon.index",
            "uses" => 'Common\CouponController@index']);

        Route::any('/create', [
            "as" => "coupon.create",
            "uses" => 'Common\CouponController@create']);

        Route::any('/store', [
            "as" => "coupon.store",
            "uses" => 'Common\CouponController@store']);

        Route::any('/destroy/{id}', [
            "as" => "coupon.destroy",
            "uses" => 'Common\CouponController@destroy'])->where('id', '[0-9]+')->middleware('role:false,destroy-coupon');

        Route::any('/import', [
            "as" => "coupon.import",
            "uses" => 'Common\CouponController@import']);

        Route::any('/export', [
            "as" => "coupon.export",
            "uses" => 'Common\CouponController@export']);

    });

    // Управление Тегами
    Route::group(['prefix' => '/manage/tags', 'middleware' => 'role:developer,manage-leadref'], function() {

        Route::any('/index', [
            "as" => "manage.tags.index",
            "uses" => 'Admin\TagController@index']);

        Route::any('/add', [
            "as" => "manage.tags.add",
            "uses" => 'Admin\TagController@create']);

        Route::any('/edit/{tagId}', [
            "as" => "manage.tags.edit",
            "uses" => 'Admin\TagController@edit']);

        Route::any('/destroy/{tag}', [
            "as" => "manage.tags.destroy",
            "uses" => 'Admin\TagController@destroy']);

    });



    // ДОСТАВКА
    Route::group(['prefix' => '/delivery', 'middleware' => 'role:developer,manage-users'], function () {

        // BoxBerry
        Route::group(['prefix' => '/boxberry', 'middleware' => 'role:developer,manage-users'], function () {

            Route::any('/index', [
                "as" => "boxberry.index",
                "uses" => 'Delivery\BoxberryController@index']);

            Route::any('/delivering', [
                "as" => "boxberry.delivering",
                "uses" => 'Delivery\BoxberryController@deliveringOrders']);

            Route::any('/amo', [
                "as" => "boxberry.amo",
                "uses" => 'Delivery\BoxberryController@amo']);

            Route::any('/info', [
                "as" => "boxberry.info",
                "uses" => 'Delivery\BoxberryController@info']);

            Route::any('/destroy', [
                "as" => "boxberry.destroy",
                "uses" => 'Delivery\BoxberryController@destroy']);
        });
    });

    Route::resource('catalog/translator', Catalog\CategoriesTranslatorController::class, [
        "names" => "category.translator"]);

    // Ручная корректировка цен товаров сделки
    Route::resource('lead/corrections', Common\LeadCorrectionsController::class, [
        "names" => "lead.corrections"]);

    // DOLI управление
    Route::resource('doli', Common\DoliController::class, ["names" => "admin.doli"])->only(["index","edit","update"]);

    // Тестирование почтовика
    Route::any('/mail/test', [
        "as" => "mail.test",
        "uses" => 'Common\MailController@test']);

    // Просмотр логов
    Route::any('/logs', [
        "as" => "logs.view",
        "uses" => '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index']);

    // Управление клиентами
    include "admin/clients.php";

    // Перенос данных
    include "trans.php";

    // Новый интерфейс
    include "admin/vuex.php";
});



