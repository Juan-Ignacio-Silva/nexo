<!-- PHP -->
<?php

?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/vistaProducto/seccion-producto.css">
<title><?= $producto['nombre'] ?></title>
<main class="producto">
    <div class="container">
        <!-- Sección del Producto general -->
        <div class="product-section">
            <!-- Imágenes del Producto seleccionado-->
            <div class="product-images">
                <div class="main-image" id="mainImage">
                    <img class="img-main-producto" src="<?= $producto['imagen'] ?>" alt="">
                </div>
                <div class="thumbnail-container">
                    <button class="nav-btn" onclick="previousImage()">‹</button>
                    <div class="thumbnails">
                        <div class="thumbnail active" onclick="changeImage(0)">
                            <img class="img-0-producto" src="<?= $producto['imagen'] ?>" alt="">
                        </div>
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
                        $promedio = (int) round($producto['promedio']); // Redondeo para mostrar estrellas enteras
                        for ($i = 1; $i <= 5; $i++):
                            if ($i <= $promedio): ?>
                                <span class="star filled">★</span>
                            <?php else: ?>
                                <span class="star">★</span>
                        <?php endif;
                        endfor;
                        ?>
                    </div>
                    <span class="rating-count">(1)</span>
                </div>

                <div class="price">$<?= $producto['precio'] ?></div>

                <div class="description">
                    <?= $producto['descripcion'] ?>
                </div>

                <div class="purchase-section">
                    <input type="number" value="1" min="1" class="quantity-input" id="cantidad">
                    <button class="add-to-cart-btn" data-id="<?= $producto['id_producto'] ?>">AGREGAR AL CARRITO</button>
                </div>

                <button class="btn-fav" id="btn-fav" data-id="<?= $producto['id_producto'] ?>">
                    <div class="svg-heart-on" id="heart-on">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-icon lucide-heart">
                            <path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5" />
                        </svg>
                    </div>
                    <div class="svg-heart-off" id="heart-off" style="display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-heart-off-icon lucide-heart-off">
                            <path d="M10.5 4.893a5.5 5.5 0 0 1 1.091.931.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 1.872-1.002 3.356-2.187 4.655" />
                            <path d="m16.967 16.967-3.459 3.346a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5a5.5 5.5 0 0 1 2.747-4.761" />
                            <path d="m2 2 20 20" />
                        </svg>
                    </div>
                    Agregar a favoritos.
                </button>

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
                <button id="btn-calificar" class="calificar">Agregar reseña</button>
            </div>
            <div class="tab-content">
                <?php
                foreach ($resenas as $resena): ?>
                    <div id="reviews" style="border-bottom: 2px solid #EEF8FF; margin-bottom: 10px;">
                        <div class="header-resena">
                            <p><strong><?= $resena['nombre_usuario'] ?></strong></p>
                            <div class="rating">
                                <?php
                                $promedio = (int) round($resena['calificacion']); // Redondeo para mostrar estrellas enteras
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
                        <p><?= $resena['comentario'] ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="modal-calificar" id="modal-calificar">
        <div class="contenedor-modal-calificar">
            <div class="opciones-calificar">
                <form action="">
                    <label for="estrellas">Estrellas</label>
                    <select name="estrellas" id="estrellas-calif">
                        <option><span class="star filled" disabled selected>Seleccionar estrellas</span></option>
                        <option value="1"><span class="star filled">★</span></option>
                        <option value="2"><span class="star filled">★★</span></option>
                        <option value="3"><span class="star filled">★★★</span></option>
                        <option value="4"><span class="star filled">★★★★</span></option>
                        <option value="5"><span class="star filled">★★★★★</span></option>
                    </select>
                    <label for="comentario">Comentario</label>
                    <textarea name="comentario" id="comentario-calif"></textarea>
                </form>
            </div>
            <div class="btns-modal-calificar">
                <button id="btnCalificarSave" class="btnCalificar primario">Guardar</button>
                <button id="btnCalificarCancel" class="btnCalificar secundario">Cancelar</button>
            </div>
        </div>
    </div>
    <!-- Librería Toastify -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        /*  FUNCIONES DE NOTIFICACIÓN de TOASTIFY*/
        function mostrarToast(mensaje, tipo = "info") {
            let color = "#0263AA";
            if (tipo === "exito") color = "#28a745";
            if (tipo === "error") color = "#dc3545";
            if (tipo === "aviso") color = "#ffc107";

            Toastify({
                text: mensaje,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: color,
                close: true,
                stopOnFocus: true,
                style: {
                    borderRadius: "4px",

                }
            }).showToast();
        }
    </script>

    <script>
        document.querySelectorAll(".add-to-cart-btn").forEach(boton => {
            boton.addEventListener("click", async () => {
                const idProducto = boton.dataset.id;
                const cantidad = document.getElementById('cantidad').value;

                const resp = await fetch("<?= BASE_URL ?>carrito/agregar", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        idProducto,
                        cantidad
                    })
                });

                const data = await resp.json();

                if (data.success) {
                    totalCarrito = data.total_productos;
                    localStorage.setItem("totalCarrito", totalCarrito);
                    document.getElementById("contador-carrito").textContent = totalCarrito;
                    mostrarToast(data.msg, "exito");
                } else {
                    mostrarToast(data.msg || "Error al agregar el producto", "error");
                }
            });
        });

        const btnFav = document.getElementById('btn-fav')
        btnFav.addEventListener('click', async () => {

            const idProducto = '<?= $producto['id_producto'] ?>';
            const heartOn = document.getElementById('heart-on');
            const heartOff = document.getElementById('heart-off');

            const resFav = await fetch('<?= BASE_URL ?>favoritos/toggleFavorito', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    idProducto
                })
            });

            const dataFav = await resFav.json();
            if (dataFav.success === false) {
                mostrarToast(dataFav.message, 'error');
            };

            if (dataFav.status === 'added') {
                mostrarToast(dataFav.message, 'exito');
                heartOn.style.display = 'none';
                heartOff.style.display = 'block';
            };

            if (dataFav.status === 'removed') {
                mostrarToast(dataFav.message, 'exito');
                heartOn.style.display = 'block';
                heartOff.style.display = 'none';
            };
        });

        document.addEventListener('DOMContentLoaded', async () => {
            const idProducto = '<?= $producto['id_producto'] ?>';
            const heartOn = document.getElementById('heart-on');
            const heartOff = document.getElementById('heart-off');

            const resFav = await fetch('<?= BASE_URL ?>favoritos/esFavorito', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    idProducto
                })
            });

            const dataEsFav = await resFav.json();

            if (dataEsFav.success) {
                heartOn.style.display = 'none';
                heartOff.style.display = 'block';
            } else {
                heartOn.style.display = 'block';
                heartOff.style.display = 'none';
            }
        })
    </script>
    <script>
        const btn = document.getElementById("btnCalificarSave");

        btn.addEventListener("click", async () => {
            if (btn.disabled) return;
            btn.disabled = true;
            btn.textContent = "Enviando...";

            const estrellas = document.getElementById('estrellas-calif').value;
            const comentario = document.getElementById('comentario-calif').value;
            const idProducto = '<?= $producto['id_producto'] ?>';

            try {
                // Enviar datos al backend
                const respR = await fetch("<?= BASE_URL ?>productos/recibirRsena", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        estrellas,
                        comentario,
                        idProducto
                    })
                });

                const dataR = await respR.json();

                if (dataR.success) {
                    mostrarToast("Reseña publicada con exito.", "exito");
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    mostrarToast(dataR.message, "error");
                    btn.disabled = false;
                    btn.textContent = "Guardar";
                }

            } catch (error) {
                console.error("Error en la solicitud:", error);
                alert("Hubo un problema al enviar la reseña!.");
                btn.disabled = false;
                btn.textContent = "Guardar";
            }
        });
    </script>

    <script>
        // Variables para controlar las imágenes
        let currentImageIndex = 0;
        let currentProductIndex = 0;
        const totalImages = 4;
        const productsPerView = window.innerWidth <= 768 ? 2 : 3; // Responsive products per view
        const totalProducts = 8; // Total de productos en el carrusel

        // Función para cambiar la imagen
        function changeImage(index) {
            currentImageIndex = index;

            // Actualizar miniaturas activas
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });
        }

        // Función para la imagen anterior
        function previousImage() {
            currentImageIndex = currentImageIndex > 0 ? currentImageIndex - 1 : totalImages - 1;
            changeImage(currentImageIndex);
        }

        // Función para la imagen siguiente
        function nextImage() {
            currentImageIndex = currentImageIndex < totalImages - 1 ? currentImageIndex + 1 : 0;
            changeImage(currentImageIndex);
        }

        // Funciones del carrusel de productos (Nueva modificacion)
        function previousProduct() {
            if (currentProductIndex > 0) {
                currentProductIndex--;
                updateCarousel();
            }
        }

        function nextProduct() {
            const currentProductsPerView = window.innerWidth <= 768 ? 2 : 3;
            const maxIndex = totalProducts - currentProductsPerView;
            if (currentProductIndex < maxIndex) {
                currentProductIndex++;
                updateCarousel();
            }
        }

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            changeImage(0);
        });
    </script>

    <script>
        const modalCalificar = document.getElementById('modal-calificar');
        const modalOpen = document.getElementById('btn-calificar');
        const btnCalificarCancel = document.getElementById('btnCalificarCancel');

        modalOpen.addEventListener('click', (e) => {
            modalCalificar.style.display = 'flex';
        });

        btnCalificarCancel.addEventListener('click', (e) => {
            modalCalificar.style.display = 'none'
        })
    </script>