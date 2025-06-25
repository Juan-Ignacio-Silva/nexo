<?php

require_once '../core/Router.php';

$url = $_GET['url'] ?? '';

$router = new Router($url);
$router->run();
