<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
            'permission' => 0666,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 7,
            'permission' => 0666,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],
        'customlog' => [
            'driver' => 'daily',
            'path' => storage_path('logs/custom/custom.log'),
            'level' => 'info',
            'permission' => 0666,
        ],
        'exceptions' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/exceptions/exception.log'),
            'level' => 'info',
            'permission' => 0666,
        ],
        'ms' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/ms/errors.log'),
            'level' => 'info',
            'permission' => 0666,
        ],
        'cron' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/cron/info.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'moysklad' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/ms/errors.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'boxberry' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/boxberry/info.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'payments' => [
            'driver' => 'daily',
            'days' => 31,
            'path' => storage_path('logs/payments/payment.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'amo' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/amo/log.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'fb_events' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/fb_events/log.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'ym_events' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/ym_events/log.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'redtracker' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/redtrack/log.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'doli' => [
            'driver' => 'daily',
            'days' => 30,
            'path' => storage_path('logs/doli/info.log'),
            'emoji' => ':boom:',
            'level' => 'info',
            'permission' => 0777,
        ],

        'err_doli' => [
            'driver' => 'daily',
            'days' => 30,
            'path' => storage_path('logs/doli/errors.log'),
            'emoji' => ':boom:',
            'level' => 'info',
            'permission' => 0777,
        ],

        'logsys' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/logsys/logsys.log'),
            'level' => 'info',
            'permission' => 0777,
        ],

        'logsys_ok' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/logsys/ok.log'),
            'level' => 'info',
            'permission' => 0777,
        ],
        'sber_clients' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/sber/clients.log'),
            'level' => 'info',
            'permission' => 0777,
        ],
        'sber_orders' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/sber/orders.log'),
            'level' => 'info',
            'permission' => 0777,
        ],
        'clients' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/clients/amo_create.log'),
            'level' => 'info',
            'permission' => 0777,
        ],


        'anketa_err' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/anketa/errors.log'),
            'level' => 'debug',
            'permission' => 0777,
        ],

        'ms_webhook' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/ms/webhook.log'),
            'level' => 'info',
            'permission' => 0666,
        ],

        'backtrace_lead' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/trace/lead.log'),
            'level' => 'info',
            'permission' => 0777,
        ],

        'stock_cron' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/stock/cron.log'),
            'level' => 'info',
            'permission' => 0777,
        ],

        'stock_webhooks' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/stock/webhook.log'),
            'level' => 'info',
            'permission' => 0777,
        ],

        'stock' => [
            'driver' => 'daily',
            'days' => 7,
            'path' => storage_path('logs/stock/other.log'),
            'level' => 'info',
            'permission' => 0777,
        ],

    ],

];
