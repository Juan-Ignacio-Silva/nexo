<?php
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" href="/css/producto/catalogo-productos.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="filters">
                <h3>Filtros</h3>

                <!-- Productos encontrados -->
                <div class="results-count">
                    <span id="resultsCount">12 productos</span>
                </div>

                <!-- Filtro de Categorías -->
                <div class="filter-group">
                    <h4>Categorías</h4>
                    <div class="category-filters">
                        <label class="checkbox-label">
                            <input type="checkbox" value="electronica" class="category-filter">
                            <span class="checkmark"></span>
                            Electrónica
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" value="gaming" class="category-filter">
                            <span class="checkmark"></span>
                            Gaming
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" value="oficina" class="category-filter">
                            <span class="checkmark"></span>
                            Oficina
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" value="accesorios" class="category-filter">
                            <span class="checkmark"></span>
                            Accesorios
                        </label>
                    </div>
                </div>

                <!-- Filtro de Precio -->
                <div class="filter-group">
                    <h4>Precio (US$)</h4>
                    <div class="price-inputs">
                        <input type="number" id="minPrice" placeholder="Mínimo" min="0">
                        <input type="number" id="maxPrice" placeholder="Máximo" min="0">
                    </div>
                </div>

                <!-- Filtro de Calificación -->
                <div class="filter-group">
                    <h4>Calificación mínima</h4>
                    <select id="ratingFilter" class="rating-select">
                        <option value="">Todas las calificaciones</option>
                        <option value="1">⭐ 1 estrella o más</option>
                        <option value="2">⭐⭐ 2 estrellas o más</option>
                        <option value="3">⭐⭐⭐ 3 estrellas o más</option>
                        <option value="4">⭐⭐⭐⭐ 4 estrellas o más</option>
                        <option value="5">⭐⭐⭐⭐⭐ 5 estrellas</option>
                    </select>
                </div>

                <!-- Filtro de Disponibilidad -->
                <div class="filter-group">
                    <h4>Disponibilidad</h4>
                    <label class="checkbox-label">
                        <input type="checkbox" id="stockFilter">
                        <span class="checkmark"></span>
                        Solo productos en stock
                    </label>
                </div>

                <!-- Botón limpiar filtros -->
                <button class="clear-filters" onclick="clearAllFilters()">
                    Limpiar filtros
                </button>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content">
            <div class="products-grid" id="productsGrid">
                <!-- Los productos se generarán dinámicamente -->
                <?php if (!empty($productos)): ?>
                    <?php foreach ($productos as $producto): ?>
                        <a href="<?= BASE_URL ?>productos/producto/<?= $producto['id_producto'] ?>" class="card-accion">
                            <div class="card-productos">
                                <div class="card-header">
                                    <img src="<?= $producto['imagen'] ?>" alt="">
                                </div>
                                <div class="cuerpo-card">
                                    <div class="header-cuerpo-card">
                                        <p><?= htmlspecialchars($producto['categoria'] ?? 'Sin categoría') ?></p>
                                        <div>
                                            <?php
                                            $promedio = isset($producto['calificacion_promedio']) && is_numeric($producto['calificacion_promedio'])
                                                ? (int) round($producto['calificacion_promedio'])
                                                : 0; // si es null o no numérico, queda en 0

                                            for ($i = 1; $i <= 5; $i++):
                                                if ($i <= $promedio): ?>
                                                    <span class="star filled">★</span>
                                                <?php else: ?>
                                                    <span class="star">★</span>
                                            <?php endif;
                                            endfor;
                                            ?>
                                        </div>
                                    </div>
                                    <p class="nombre-producto"><?= htmlspecialchars($producto['nombre']) ?></p>
                                    <div class="footer-card">
                                        <p class="precio">US$ <?= number_format($producto['precio'] ?? 0, 2) ?></p>
                                        <p class="precio-oferta"></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span>No hay productos con esta categoria</span>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const productsGrid = document.getElementById("productsGrid");
        const categoryFilters = document.querySelectorAll(".category-filter");
        const minPriceInput = document.getElementById("minPrice");
        const maxPriceInput = document.getElementById("maxPrice");
        const ratingFilter = document.getElementById("ratingFilter");
        const stockFilter = document.getElementById("stockFilter");
        const resultsCount = document.getElementById("resultsCount");

        // Guardamos todos los productos originales (PHP los genera en HTML)
        const allProducts = Array.from(productsGrid.querySelectorAll(".card-accion"));

        function filterProducts() {
            const selectedCategories = Array.from(categoryFilters)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            const minPrice = parseFloat(minPriceInput.value) || 0;
            const maxPrice = parseFloat(maxPriceInput.value) || Infinity;
            const minRating = parseInt(ratingFilter.value) || 0;
            const onlyInStock = stockFilter.checked;

            let visibleCount = 0;

            allProducts.forEach(card => {
                const category = card.querySelector(".cuerpo-card p")?.textContent.toLowerCase() || "";
                const priceText = card.querySelector(".precio")?.textContent.replace("US$", "").trim();
                const price = parseFloat(priceText) || 0;
                const stars = card.querySelectorAll(".star.filled").length;

                // Si quisieras manejar stock, tendrías que agregar una clase o data-stock al HTML
                const inStock = true; // por ahora todos los productos están en stock

                const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(category);
                const priceMatch = price >= minPrice && price <= maxPrice;
                const ratingMatch = stars >= minRating;
                const stockMatch = !onlyInStock || inStock;

                const visible = categoryMatch && priceMatch && ratingMatch && stockMatch;
                card.style.display = visible ? "block" : "none";

                if (visible) visibleCount++;
            });

            resultsCount.textContent = `${visibleCount} productos`;
        }

        // Eventos para todos los filtros
        [...categoryFilters, minPriceInput, maxPriceInput, ratingFilter, stockFilter].forEach(input => {
            input.addEventListener("input", filterProducts);
            input.addEventListener("change", filterProducts);
        });

        // Botón de limpiar filtros
        window.clearAllFilters = function() {
            categoryFilters.forEach(cb => cb.checked = false);
            minPriceInput.value = "";
            maxPriceInput.value = "";
            ratingFilter.value = "";
            stockFilter.checked = false;
            filterProducts();
        };

        // Llamar una vez al cargar
        filterProducts();
    });
</script>

</html>