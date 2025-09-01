<?php
// require_once ROOT . 'app/controllers/ProductoController.php';
// $productosDestacados = ProductoController::getProductosDestacados();
?>

<link rel="stylesheet" href="css/home/section-productos.css">
<section class="section-productos-home">
    <div class="contenedor-productos-home">
        <div class="header-section-productos">
            <div class="titulo-section-producto">
                <p>Productos</p>
                <h1>Productos <span>Destacados</span></h1>
            </div>
            <a href="#">Ver todos los productos →</a>
        </div>

        <div class="contenedor-cards-productos">
            <?php
            //foreach ($productosDestacados as $producto): ?>
            <div class="card-productos">
                <div class="card-header">
                    <img src="images/productos/portatil-samsung.png" alt="">
                </div>
                <div class="cuerpo-card">
                    <div class="header-cuerpo-card">
                        <p><?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría') ?></p>
                        <p><?= str_repeat('⭐', (int) round($producto['calificacion_promedio'] ?? 0)) ?></p>
                    </div>
                    <p class="nombre-producto"><?= htmlspecialchars($producto['nombre']) ?></p>
                    <div class="footer-card">
                        <p class="precio">US$ <?= number_format($producto['precio'] ?? 0, 2) ?></p>
                        <p class="precio-oferta">US$ 25</p>
                    </div>
                </div>
            </div>
            <?php //endforeach; ?>
        </div>
    </div>
</section>
