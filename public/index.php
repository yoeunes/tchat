<?php

use App\Core\Config;
use App\Core\DB;
use App\Core\Dispatcher;
use App\Core\Session;

define('PUBLIC_PATH', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));
define('APP_PATH', str_replace('public/index.php', '', $_SERVER['SCRIPT_FILENAME']));

require __DIR__.'/../vendor/autoload.php';

Session::start();

$GLOBALS[ 'config' ] = include __DIR__ . '/../config/app.php';

$bdd = new DB('mysql:host=' . Config::get('database/host') . ';dbname=' . Config::get('database/database') . ';port=' . Config::get('database/port') . ';charset=' . Config::get('database/charset'), Config::get('database/username'), Config::get('database/password'));
function getError($error) { echo $error; }

$bdd->setErrorCallbackFunction('getError');

$dispatcher = new Dispatcher();
$dispatcher->dispatch();
