<?php
define('PUBLIC_PATH', 'anketa/re-anketa/');
define('ENVIRONMENT', config('app.env'));
define('API_BOXBERRY_TOKEN', env('API_BOXBERRY_TOKEN'));
define('TINKOFF_TERMINAL_KEY', config('config.TINKOFF_TERMINAL_KEY'));
include PUBLIC_PATH . "index.php";
?>
