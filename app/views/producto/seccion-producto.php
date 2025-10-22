<!-- PHP -->
<?php

?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/vistaProducto/seccion-producto.css">

<script>
    const BASE_URL = "<?= BASE_URL ?>"; // Ajusta si es necesario
</script>

<main class="producto">
    <div class="container">
        <!-- Sección del Producto general -->
        <div class="product-section">
            <!-- Imágenes del Producto seleccionado-->
            <div class="product-images">
                <div class="main-image" id="mainImage"></div>
                <div class="thumbnail-container">
                    <button class="nav-btn" onclick="previousImage()">‹</button>
                    <div class="thumbnails">
                        <div class="thumbnail active" onclick="changeImage(0)"></div>
                        <div class="thumbnail" onclick="changeImage(1)"></div>
                        <div class="thumbnail" onclick="changeImage(2)"></div>
                        <div class="thumbnail" onclick="changeImage(3)"></div>
                    </div>
                    <button class="nav-btn" onclick="nextImage()">›</button>
                </div>
            </div>

            <!-- Info del Producto -->
            <div class="product-info">
                <h1 class="product-title"><?= $producto['nombre'] ?></h1>

                <div class="rating">
                    <div class="stars">
                        <?php
                        $promedio = (int) round($producto['promedio']);
                        for ($i = 1; $i <= 5; $i++):
                            if ($i <= $promedio): ?>
                                <span class="star filled">★</span>
                            <?php else: ?>
                                <span class="star">★</span>
                        <?php endif; endfor; ?>
                    </div>
                    <span class="rating-count">(1)</span>
                </div>

                <div class="price">$<?= $producto['precio'] ?></div>
                <div class="description"><?= $producto['descripcion'] ?></div>

                <div class="purchase-section">
                    <input type="number" value="1" min="1" class="quantity-input">
                    <button class="add-to-cart-btn" data-id="<?= $producto['id_producto'] ?>">AGREGAR AL CARRITO</button>
                </div>

                <button class="wishlist-btn">♡ Examinar Lista de Deseos</button>

                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Categoría:</span>
                        <span class="meta-value"><?= $producto['categoria'] ?></span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Vende:</span>
                        <span class="meta-value">Tienda Tech</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de las Pestañas -->
        <div class="tabs-section">
            <div class="tabs">
                <button class="tab">Reseñas</button>
                <button>Calificar</button>
            </div>
            <div class="tab-content">
                <?php foreach ($resenas as $resena): ?>
                    <div id="reviews" style="border-bottom: 2px solid #EEF8FF; margin-bottom: 10px;">
                        <div class="header-resena">
                            <p><strong><?= $resena['nombre_usuario'] ?></strong></p>
                            <div class="rating">
                                <?php
                                $promedio = (int) round($resena['calificacion']);
                                for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $promedio): ?>
                                        <span class="star filled">★</span>
                                    <?php else: ?>
                                        <span class="star">★</span>
                                <?php endif; endfor; ?>
                            </div>
                        </div>
                        <p><?= $resena['comentario'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Esta es la libreria de Toastify -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


    <script>
        // Función para estilo de Toastify 
     function mostrarToast(mensaje, tipo = "info") {
    let color = "#0263AA"; 
    if(tipo === "exito") color = "#28a745"; 
    if(tipo === "error") color = "#dc3545"; 
    if(tipo === "aviso") color = "#ffc107"; 

    Toastify({
        text: mensaje,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: color
    }).showToast();
}


        //Agregar al carrito 
        document.querySelectorAll(".add-to-cart-btn").forEach(boton => {
    boton.addEventListener("click", async () => {
        const idProducto = boton.dataset.id;

        try {
            const resp = await fetch(BASE_URL + "carrito/agregar", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: idProducto })
            });

            if (!resp.ok) throw new Error("Respuesta inválida del servidor");

            const data = await resp.json();

            if (data.success) {
                const totalCarrito = data.total_productos;
                localStorage.setItem("totalCarrito", totalCarrito);
                document.getElementById("contador-carrito").textContent = totalCarrito;
                mostrarToast(data.msg, "exito"); 
            } else {
                mostrarToast(data.msg || "Error al agregar el producto", "error");
            }
        } catch(err) {
            console.error(err);
            mostrarToast("Error de conexión o JSON inválido", "error");
        }
    });
});


        // Control de imágenes 
        let currentImageIndex = 0;
        const totalImages = 4;

        function changeImage(index) {
            currentImageIndex = index;
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach((thumb, i) => thumb.classList.toggle('active', i === index));
        }

        function previousImage() {
            currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : totalImages - 1;
            changeImage(currentImageIndex);
        }

        function nextImage() {
            currentImageIndex = currentImageIndex < totalImages - 1 ? currentImageIndex + 1 : 0;
            changeImage(currentImageIndex);
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            changeImage(0);
        });
    </script>
</main>
