<?php
require_once ROOT . "core/View.php";
require_once ROOT .  'core/Auth.php';
class HomeController
{
    public function index()
    {
        View::render(view: "home/index", data: [
            "title" => "Nexo - Inicio",
            "usuario" => Auth::usuario()
        ]);
    }

    public function vender() {
        View::render(view:'navegation/vender', data: [
            'title'=> 'Nexo - Vender',
            'usuario'=> Auth::usuario()
        ]);
    }
}
