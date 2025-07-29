<?php

// Forzar recarga del favicon
if (preg_match('/favicon\.ico$/', $_SERVER['REQUEST_URI'])) {
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-Type: image/x-icon');
    readfile(__DIR__.'/favicon.ico');
    exit;
}

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
