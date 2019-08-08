<?php

define('PUBLIC_PATH', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('APP_PATH', str_replace('public/index.php', '', $_SERVER['SCRIPT_FILENAME']));

require __DIR__.'/../vendor/autoload.php';

$dispatcher = new \App\Core\Dispatcher();
$dispatcher->dispatch();
