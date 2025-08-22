
<?php

class ProductoController {

    public function Producto() {
        
        require_once ROOT . 'core/Auth.php';

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/vistaProducto/seccion-producto.php';
        include ROOT . 'app/views/templates/footer.php';
    }
}