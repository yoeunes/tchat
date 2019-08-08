<?php

use App\Core\Dispatcher;
use App\Core\Session;

define('PUBLIC_PATH', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('APP_PATH', str_replace('public/index.php', '', $_SERVER['SCRIPT_FILENAME']));

require __DIR__.'/../vendor/autoload.php';

Session::start();

$GLOBALS[ 'config' ] = include __DIR__ . '/../config/app.php';

$dispatcher = new Dispatcher();
$dispatcher->dispatch();
