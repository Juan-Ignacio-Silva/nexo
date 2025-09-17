<?php 
require_once ROOT . "core/View.php";
class CategoriasController {
    public function categorias() {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "categoriasvista/categorias", data: [
            "title" => "Nexo - Inicio",
            "usuario" => Auth::usuario()
        ]);
    }
}