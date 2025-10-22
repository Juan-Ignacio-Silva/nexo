<?php
require_once ROOT . 'core/Auth.php';
require_once 'ProductosController.php';
require_once ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
class VendedorController
{
    public function dashboard()
    {
        Auth::restringirDashboard(rolPermitido: 'vendedor');
        include ROOT . 'app/views/vendedor/dashboard.php';
    }

    public static function productosDeVendedor()
    {
        require_once ROOT . 'app/models/Producto.php';
        $conexion = require ROOT . 'core/database.php';

        $productos = Producto::productosIdVendedor($conexion, $id);
        if (!$productos) {
            return [];
        } else {
            return $productos;
        }
    }

    public static function registroVendedor() {
        
    }
}
