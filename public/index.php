<?php
// Incio la session aca ya que si no la hago antes de que se cargue todo el contenido de la web,
// salta error.
require_once '../core/Session.php';
Session::start();

// Defino dos variables globales con el valor de las rutas de los directorios principales.
define('ROOT', dirname(__DIR__) . '/');
define('URL_PUBLIC', '/nexo/public/');

require_once '../core/Config.php';
require_once '../core/Router.php';
// Declaro una varibale $url que sea igual a la variable $_GET con el valor de la url del navegador
// y si esa url es nulla el valor de la variable sera 'vacio';
$url = $_GET['url'] ?? '';

// Instancio un nuevo router pasandole la url, para luego iniciarlo con el metodo run().
$router = new Router($url);
$router->run();
