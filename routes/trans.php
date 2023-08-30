<?php

// Ссылка на оплату
Route::any('/trans/anketa/index', [
"as" => "trans.anketa.index",
"uses" => 'Admin\Trans\TransController@index']);

Route::any('/trans/feedback/index', [
"as" => "trans.feedback.index",
"uses" => 'Admin\Trans\TransController@getAmoClient']);