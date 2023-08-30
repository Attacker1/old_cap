<?php

$path = 'moy-sklad/webhook/';
return [
    'WEBHOOK_URI' => 'https://' . config('config.APP_SUBDOMAIN_BACKOFFICE') .'.'.config('app.url').'/' . $path,
    'ORDER_NAME' => 'Тест заказ ',
    'ORDER_DESC' => 'Тестовый заказ из ЛК '
];
