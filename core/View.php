<?php
class View {
    public static function render($view, $data = [], $layout = "layouts/main")
    {
        extract($data); // convierte claves del array en variables ($usuario, $title, etc.)
        
        $viewFile = ROOT . "app/views/$view.php";
        if (!file_exists($viewFile)) {
            $viewFile = ROOT . "app/views/templates/404.php";
        }

        // El layout recibe la vista como $viewFile
        include ROOT . "app/views/$layout.php";
    }
}
