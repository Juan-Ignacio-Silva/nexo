<link rel="stylesheet" href="<?= URL_PUBLIC ?>css/home/section-productos.css">
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
            for ($i=1;$i <= 3; $i++) {
            ?>
            <div class="card-productos">
                <div class="card-header">
                    <img src="" alt="">
                </div>
                <div class="cuerpo-card">
                    <div class="header-cuerpo-card">
                        <p>Categoria</p>
                        <p>⭐⭐⭐⭐⭐</p>
                    </div>
                    <p class="nombre-producto">Nombre producto</p>
                    <div class="footer-card">
                        <p class="precio">US$ 30</p>
                        <p class="precio-oferta">US$ 25</p>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
