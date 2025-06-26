<?php

class Router
{
    protected $controller = 'HomeController'; // Controlador por defecto.
    protected $method = 'index'; // Metodo por defecto
    protected $params = []; // Parametros por defecto y vacio

    public function __construct($url)
    {
        $url = $this->parseUrl(url: $url);

        // Controlador
        if (!empty($url[0])) {
            $ctrlName = ucfirst($url[0]) . 'Controller';
            if (file_exists("../app/controllers/$ctrlName.php")) {
                $this->controller = $ctrlName;
                require_once "../app/controllers/$ctrlName.php";
                $url = array_slice($url, 1);
            } else {
                $this->error404("Controlador '$ctrlName' no encontrado.");
            }
        } else {
            require_once "../app/controllers/{$this->controller}.php";
        }

        $this->controller = new $this->controller; // Creo un a nueva instancia del controlador [controller].

        // Verificación del metodo en el controlador, si el array esta vacio devuele un 404.
        if (!empty($url[0])) {
            if (method_exists($this->controller, $url[0])) {
                $this->method = $url[0];
                $url = array_slice($url, 1);
            } else {
                $this->error404("Método '{$url[0]}' no encontrado.");
            }
        }

        // Parámetros
        $this->params = $url;
    }
    
    public function run()//Función que ejecuta el controlador mas el metodo y sus parametros si es que tiene.
    {
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl($url)//Función para limpar la URL y devolver un array con las palabras de la misma.
    {
        return explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
    }

    private function error404($msg)
    {
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>$msg</p>";
        exit;
    }
}
