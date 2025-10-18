<?php
require_once ROOT . 'core/Auth.php';
require_once 'ProductosController.php';
require_once 'UsuarioController.php';

class AdminController {
    
    public function dashboard() {
        Auth::restringirDashboard('admin');
        // Total de productos
        $productosRegistrados = ProductosController::getProductosSoloInfo();        
        $totalP = count($productosRegistrados);
        // Total de usuarios
        $usuariosRegistrados = UsuarioController::getUsuariosRegistrados();        
        $totalU = count($usuariosRegistrados);
        //Usuarios recien registrados
        $recienRegistrados = UsuarioController::getRecienRegistrados();

        include ROOT . 'app/views/admin/dashboard.php';
    }
}
