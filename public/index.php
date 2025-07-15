<?php

require_once '../core/Router.php';

define('ROOT', dirname(__DIR__) . '/');
define('URL_PUBLIC', '/nexo/public/');


$url = $_GET['url'] ?? '';

$router = new Router($url);
$router->run();
