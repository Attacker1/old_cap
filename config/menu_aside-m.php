<?php
// Aside menu

return [

    'items' => [

        [
            'title' => 'Главная страница',
            'root' => true,
            'page' => 'admin/dashboard',
            'icon' => 'flaticon-home',
            'route_name' => 'admin.main.index',
            'new-tab' => false,
        ],
        [
            'title' => 'Записка стилиста',
            'root' => true,
            'page' => 'admin/note/list',
            'icon' => 'm/media/svg/icons/Clothes/Hanger.svg',
            'route_name' => 'notes.list',
            'new-tab' => false,
        ],
        [
            'title' => 'Товары',
            'root' => true,
            'page' => 'admin/catalog/products/index',
            'icon' => 'm/media/svg/icons/Clothes/Dress.svg',
            'route_name' => 'admin.catalog.products.index',
            'user_rights'=>'catalog-manage',
            'new-tab' => false,
        ],
        [
            'title' => 'Сделки',
            'root' => true,
            'icon' => 'm/media/svg/icons/Shopping/Price1.svg',
            'page' => 'admin/leads/list',
            'route_name' => 'leads.list',
            'user_rights'=>'view-leads',
            'new-tab' => false,
        ],
        [
            'title' => 'Анкеты (поддержка) ',
            'root' => true,
            'page' => 'admin/anketa/listing',
            'icon' => 'm/media/svg/icons/General/Clipboard.svg',
            'bullet' => 'dot',
            'user_rights'=>'anketa-support',
            'route_name' => 'admin.anketa.listing',
        ],
        [
            'title' => 'Анкеты (Настройка) ',
            'root' => true,
            'page' => 'admin/anketas/',
            'icon' => 'm/media/svg/icons/General/Clipboard.svg',
            'bullet' => 'dot',
            'user_rights'=>'anketa-support',
            'route_name' => 'admin.anketas',
        ],
        [
            'title' => 'Оплаты',
            'root' => true,
            'icon' => 'm/media/svg/icons/Shopping/Wallet2.svg',
            'route_name' => 'payments.list',
            'page' => 'admin/payments/list',
            'user_rights'=>'view-payments',
            'new-tab' => false,
        ],
        [
            'title' => 'Настройки каталога',
            'root' => true,
            'icon' => 'm/media/svg/icons/Shopping/Settings.svg',
            'route_name' => 'payments.list',
            'new-tab' => false,
            'user_rights'=>'catalog-manage',
            'submenu' => [

                [
                    'title' => 'Категории МС->ЛК',
                    'page' => 'admin/catalog/translator',
                    'bullet' => 'dot',
                    'route_name' => 'category.translator.index',
                ],
                [
                    'title' => 'Остатки',
                    'page' => 'admin/products/quantity/index',
                    'bullet' => 'dot',
                    'route_name' => 'quantity.index',
                ],
                [
                    'title' => 'Бренды',
                    'page' => 'admin/catalog/brands/index',
                    'bullet' => 'dot',
                    'route_name' => 'admin.catalog.brands.index',
                ],
                [
                    'title' => 'Категории',
                    'page' => 'admin/catalog/categories/index',
                    'bullet' => 'dot',
                    'route_name' => 'admin.catalog.categories.index',
                    'user_rights'=>'catalog-manage',
                ],
                [
                    'title' => 'Характеристики',
                    'page' => 'admin/catalog/attributes/index',
                    'bullet' => 'dot',
                    'route_name' => 'admin.catalog.attributes.index',
                    'user_rights'=>'manage-attributes',
                ],
                [
                    'title' => 'Пресеты',
                    'page' => 'admin/catalog/presets',
                    'bullet' => 'dot',
                    'route_name' => 'admin.catalog.presets.index',
                    'user_rights'=>'manage-presets',

                ],
                [
                    'title' => 'Цвета',
                    'bullet' => 'dot',
                    'page' => 'admin/catalog/colors/index',
                    'route_name' => 'admin.color.index',
                ],

            ],
        ],

        [
            'title' => 'Клиенты',
            'root' => true,
            'icon' => 'm/media/svg/icons/General/User.svg',
            'route_name' => 'payments.list',
            'new-tab' => false,
            'user_rights'=>'manage-clients',
            'submenu' => [

                [
                    'title' => 'Список клиентов',
                    'page' => 'admin/clients/list',
                    'icon' => 'm/media/svg/icons/General/Clipboard.svg',
                    'bullet' => 'dot',
                    'route_name' => 'clients.list',
                ],

                [
                    'title' => 'Купоны',
                    'page' => 'admin/coupon/index',
                    'icon' => 'm/media/svg/icons/Shopping/Sale1.svg',
                    'bullet' => 'dot',
                    'route_name' => 'coupon.index',
                ],

                [
                    'title' => 'Статусы',
                    'page' => 'admin/clients-statuses/list',
                    'icon' => 'm/media/svg/icons/Shopping/Chart-bar1.svg',
                    'bullet' => 'dot',
                    'route_name' => 'admin.clients-statuses.list',
                ],
                [
                    'title' => 'Бонусы',
                    'page' => 'admin/bonuses/index',
                    'icon' => 'm/media/svg/icons/Shopping/Sale2.svg',
                    'bullet' => 'dot',
                    'route_name' => 'bonuses.index',
                ],
                [
                    'title' => 'Бонусы (Справочник)',
                    'page' => 'admin/bonus/ref/index',
                    'icon' => 'm/media/svg/icons/Shopping/Sale2.svg',
                    'bullet' => 'dot',
                    'route_name' => 'bonus.ref.index',
                ],
                [
                    'title' => 'Анкеты',
                    'page' => 'admin/anketa/list',
                    'icon' => 'm/media/svg/icons/Files/File.svg',
                    'bullet' => 'dot',
                    'user_rights'=>'viewing-anketa-list',
                    'route_name' => 'admin.anketa.list.fill',
                ],
                [
                    'title' => 'Повторные анкеты',
                    'page' => 'admin/reanketa/list',
                    'icon' => 'm/media/svg/icons/Files/File.svg',
                    'bullet' => 'dot',
                    'user_rights'=>'viewing-anketa-list',
                    'route_name' => 'admin.reanketa.list.fill',
                ],
            ],
        ],

//        [
//            'title' => 'Обратная связь',
//            'root' => true,
//            'page' => 'admin/feedback/list',
//            'icon' => 'm/media/svg/icons/Communication/Chat6.svg',
//            'route_name' => 'admin.feedback.list.fill',
//            'new-tab' => false,
//            'user_rights'=>'viewing-feedback-list'
//        ],

        [
            'title' => 'Настройки системы',
            'root' => true,
            'icon' => 'm/media/svg/icons/General/Settings-2.svg',
            'route_name' => 'payments.list',
            'new-tab' => false,
            'user_rights'=>'manage-users',
            'submenu' => [
                [
                    'title' => 'Пользователи',
                    'page' => 'admin/manage/users/index',
                    'icon' => 'm/media/svg/icons/General/Clipboard.svg',
                    'bullet' => 'dot',
                    'route_name' => 'admin.manage.users.index',
                ],
                [
                    'title' => 'Роли',
                    'page' => 'admin/manage/roles/index',
                    'icon' => 'm/media/svg/icons/General/Settings#3.svg',
                    'bullet' => 'dot',
                    'route_name' => 'manage.roles.index',
                ],
                [
                    'title' => 'Права',
                    'page' => 'admin/manage/permissions/index',
                    'icon' => 'm/media/svg/icons/Code/Git3.svg',
                    'bullet' => 'dot',
                    'route_name' => 'manage.permissions.index',
                ],
                [
                    'title' => 'Сделка-статус',
                    'page' => 'admin/manage/lead/roles/index',
                    'icon' => 'm/media/svg/icons/Communication/Clipboard-list.svg',
                    'bullet' => 'dot',
                    'route_name' => 'manage.leadref.index',
                ],
                [
                    'title' => 'Статусы клиентам',
                    'page' => 'admin/client/status/index',
                    'icon' => 'm/media/svg/icons/Code/Info-circle.svg',
                    'bullet' => 'dot',
                    'route_name' => 'client.statuses.index',
                ],
                [
                    'title' => 'Статусы СБЕР',
                    'page' => 'admin/sber/status',
                    'icon' => 'm/media/svg/icons/Code/Info-circle.svg',
                    'bullet' => 'dot',
                    'route_name' => 'sber.statuses.index',
                ],
                [
                    'title' => 'Клиентские настройки',
                    'page' => 'admin/client/settings/index',
                    'icon' => 'm/media/svg/icons/General/Other2.svg',
                    'bullet' => 'dot',
                    'route_name' => 'client.settings.index',
                ],
                [
                    'title' => 'Почтовые шаблоны',
                    'page' => 'admin/mail/template/index',
                    'icon' => 'm/media/svg/icons/Communication/Chat-check.svg',
                    'bullet' => 'dot',
                    'route_name' => 'mail.templates.index',
                ],
                [
                    'title' => 'Теги',
                    'page' => 'admin/manage/tags/index',
                    'icon' => 'm/media/svg/icons/Shopping/Price1.svg',
                    'bullet' => 'dot',
                    'route_name' => 'manage.tags.index',
                ],
            ],
        ],

        [
            'title' => 'Тестирование',
            'root' => true,
            'icon' => 'm/media/svg/icons/Communication/Clipboard-check.svg',
            'route_name' => 'payments.list',
            'new-tab' => false,
            'user_rights'=>'manage-clients',
            'submenu' => [
                [
                    'title' => 'Доли транзакции',
                    'root' => true,
                    'page' => 'admin/doli/index',
                    'icon' => 'm/media/svg/icons/Communication/Clipboard-check.svg',
                    'route_name' => 'admin.doli.index',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ],
                [
                    'title' => 'Триггеры',
                    'root' => true,
                    'page' => 'admin/test/triggers',
                    'icon' => 'm/media/svg/icons/Communication/Clipboard-check.svg',
                    'route_name' => 'test.triggers',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ],

                [
                    'title' => 'Доставка',
                    'root' => true,
                    'page' => 'admin/delivery/boxberry/index',
                    'icon' => 'm/media/svg/icons/Communication/Clipboard-check.svg',
                    'route_name' => 'boxberry.index',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ],

                [
                    'title' => 'PHP info',
                    'root' => true,
                    'page' => 'admin/test/php',
                    'icon' => 'm/media/svg/icons/Communication/Clipboard-check.svg',
                    'route_name' => 'test.php',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ],
            ],
        ],

        [
            'title' => 'Аналитика',
            'root' => true,
            'icon' => 'm/media/svg/icons/Shopping/Price1.svg',
            'route_name' => 'payments.list',
            'new-tab' => false,
            'user_rights'=>'manage-clients',
            'submenu' => [
                [
                    'title' => 'По сделкам',
                    'root' => true,
                    'page' => 'admin/analytics/leads/index',
                    'route_name' => 'analytics.leads.index',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ],

                [
                    'title' => 'По дедлайнам стилистов',
                    'root' => true,
                    'page' => 'admin/analytics/stylist/index',
                    'route_name' => 'analytics.stylist.index',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ],

                [
                    'title' => 'Отчет для PR',
                    'root' => true,
                    'page' => 'admin/analytics/pr-report/index',
                    'route_name' => 'analytics.pr-report.index',
                    'new-tab' => false,
                    'user_rights'=>'manage-users'
                ]

            ],
        ],
    ]

];
