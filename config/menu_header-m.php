<?php
// Header menu
return [

    'items' => [
        [],
        [
            'title' => 'Главная',
            'icon' => 'flaticon-home',
            'root' => true,
            'page' => '/',
            'new-tab' => false,
        ],
        [
            'title' => 'Выход',
            'root' => true,
            'page' => 'admin/logout',
            'new-tab' => false,
            'icon' => 'flaticon-logout',
            'route_name' => 'admin.logout',
        ],
        [
            'title' => 'Новый офис',
            'root' => true,
            'page' => 'admin/v/index',
            'new-tab' => false,
            'icon' => 'flaticon-light',
            'route_name' => 'admin.v.index',
        ],
    ]

];
