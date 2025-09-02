
<?php

class ProductosController {

    public function Producto($id) {
        
        require_once ROOT . 'core/Auth.php';
        require_once ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';

        $producto = Producto::obtenerPorId($conexion, $id);

        if (!$producto) {
            http_response_code(404);
            include ROOT . 'app/views/templates/404.php';
            exit;
        }

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/vistaProducto/seccion-producto.php';
        include ROOT . 'app/views/templates/footer.php';
    }

    public static function getProductosInfo(){
        
        require_once ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';
        
        $productos = Producto::productosInfo($conexion);

        if (!$productos) {
            return "Error! No se encontraron los productos.";
        } else {
            return $productos;
        }

    }
}