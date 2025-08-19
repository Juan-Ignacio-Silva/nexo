<?php
class VenderController {
    public function vender() {

        require_once ROOT . 'core/Auth.php';

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/vistaVender/seccion-vender.php';
        include ROOT . 'app/views/templates/footer.php';

    }
}
