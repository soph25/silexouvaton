<?php
ini_set('display_errors', 1);

// call_deprecated_function_here()
error_reporting(E_ALL ^ E_WARNING);
error_reporting(error_reporting() ^ E_DEPRECATED);
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
//$app['debug'] = true;
require __DIR__.'/../app/config/prod.php';
require __DIR__.'/../app/app.php';
require __DIR__.'/../app/routes.php';

$app->run();
