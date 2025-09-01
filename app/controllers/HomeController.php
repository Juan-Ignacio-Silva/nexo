<?php
require_once ROOT . "core/View.php";
class HomeController
{
    public function index()
    {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "home/index", data: [
            "title" => "Nexo - Inicio",
            "usuario" => Auth::usuario()
        ]);
    }
}
