<?php
class HomeController
{
    public function index()
    {
        require_once ROOT . 'core/Auth.php';
        Auth::restringir();

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/home/seccion-hero.php';
        include ROOT . 'app/views/home/seccion-categorias.php';
        include ROOT . 'app/views/home/seccion-productos.php';
        include ROOT . 'app/views/home/seccion-faq.php';
        include ROOT . 'app/views/templates/footer.php';
        ?>
        <h1>TEST: Bienvenido, <?= htmlspecialchars(Auth::usuario()) ?></h1><!-- Esto lo puse como una prueba para ver si funcionaba el sistema de sessions-->
        <?php
    }
}
