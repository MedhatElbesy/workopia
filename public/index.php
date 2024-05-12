<?php
require __DIR__.'/../vendor/autoload.php';

use Framework\Session;
use Framework\Router;


Session::start();
require '../helper.php';

$router = new Framework\Router();

$routes = require basePath('routes.php');

$uri = parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);

$router->route($uri);



