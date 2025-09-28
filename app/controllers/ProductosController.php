<?php
require_once ROOT . 'core/View.php';
class ProductosController {

    public function Producto($id) {
        
        require_once ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';
        require_once ROOT . 'core/Auth.php';

        if (!empty($id)) {
            $producto = Producto::obtenerPorId($conexion, $id);
            $resenas = Producto::resenasProducto($conexion, $id);
            if (!$producto) {
                http_response_code(404);
                include ROOT . 'app/views/templates/404.php';
                exit;
            }
        } else {
            http_response_code(404);
            include ROOT . 'app/views/templates/404.php';
            exit;
        }

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/producto/seccion-producto.php';
        include ROOT . 'app/views/templates/footer.php';
    }

    public function carrito() {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "producto/carrito", data: [
            "title" => "Nexo - Carrito",
            "usuario" => Auth::usuario()
        ]);
    }

    public function catalogo() {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "producto/catalogo-productos", data: [
            "title" => "Nexo - Catalogo",
            "usuario" => Auth::usuario()
        ]);
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

    public static function getProductosSoloInfo() {
        require_once ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';

        $productos = Producto::obtenerInfoProductos($conexion);

        if (!$productos) {
            return "Error! No se pudieron encontrar los productos";
        } else {
            return $productos;
        }
    }
}