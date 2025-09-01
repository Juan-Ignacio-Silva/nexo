
<?php

class ProductoController {

    public function Producto() {
        
        require_once ROOT . 'core/Auth.php';

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/vistaProducto/seccion-producto.php';
        include ROOT . 'app/views/templates/footer.php';
    }

    public static function getProductosDestacados(){
        
        require_once ROOT . 'app/config/database.php';
        require_once ROOT . 'app/models/Producto.php';
        
        $productos = Producto::productosDestacados($conexion);

        if (!$productos) {
            return "Error! No se encontraron los productos.";
        } else {
            return $productos;
        }

    }
}