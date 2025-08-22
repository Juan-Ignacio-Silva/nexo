 <link rel="stylesheet" href="<?= URL_PUBLIC ?>css/vistaProducto/seccion-producto.css">

<main class="producto">   
    <div class="container">
        <!-- Sección del Producto general -->
        <div class="product-section">
            <!-- Imágenes del Producto seleccionado-->
            <div class="product-images">
                <div class="main-image" id="mainImage">
                    <!-- Aquí es donde va la imagen principal -->
                </div>
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
                <h1 class="product-title">Zapatillas Deportivas Premium</h1>
                
                <div class="rating">
                    <div class="stars">
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star filled">★</span>
                        <span class="star">★</span>
                    </div>
                    <span class="rating-count">(1)</span>
                </div>

                <div class="price">$150.00</div>

                <div class="description">
                    Experimenta la comodidad y el estilo con estas zapatillas deportivas premium. Diseñadas para el rendimiento y la versatilidad, estas zapatillas son perfectas tanto para entrenamientos intensos como para el uso diario. Con una parte superior transpirable y una suela que brinda excelente tracción.
                </div>

                <div class="purchase-section">
                    <input type="number" value="1" min="1" class="quantity-input">
                    <button class="add-to-cart-btn">AGREGAR AL CARRITO</button>
                </div>

                <button class="wishlist-btn">
                    ♡ Examinar Lista de Deseos
                </button>

                <div class="product-meta">
                    <div class="meta-item">
                        <span class="meta-label">Categorías:</span>
                        <span class="meta-value">Calzado, Laptops & Escritorios</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Etiqueta:</span>
                        <span class="meta-value">deportivo</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección de las Pestañas -->
        <div class="tabs-section">
            <div class="tabs">
                <button class="tab active" onclick="showTab('description')">Descripción</button>
                <button class="tab" onclick="showTab('reviews')">Reseñas (1)</button>
            </div>
            <div class="tab-content" id="tabContent">
                <div id="description">
                    <p>Estas zapatillas deportivas premium combinan tecnología avanzada con un diseño moderno. La parte superior está construida con materiales transpirables de alta calidad que mantienen tus pies frescos y cómodos durante todo el día.</p>
                    <br>
                    <p>La suela intermedia ofrece una amortiguación excepcional, perfecta para absorber el impacto durante actividades de alta intensidad. La suela exterior proporciona una tracción superior en diversas superficies, desde asfalto hasta senderos ligeros.</p>
                </div>
                <div id="reviews" style="display: none;">
                    <p><strong>Reseña de Cliente Verificado</strong></p>
                    <div class="rating" style="margin: 10px 0;">
                        <div class="stars">
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star filled">★</span>
                            <span class="star">★</span>
                        </div>
                    </div>
                    <p>"Excelentes zapatillas, muy cómodas y con gran calidad de materiales. Las uso tanto para el gimnasio como para caminar por la ciudad. Recomendadas."</p>
                </div>
            </div>
        </div>

        <!-- Productos Relacionados al mismo -->
        <div class="related-products">
            <h2 class="related-title">Productos Relacionados</h2>
            <div class="carousel-container">
                <button class="carousel-btn prev" onclick="previousProduct()">‹</button>
                <div class="products-carousel">
                    <div class="products-grid">
                        <!-- Los productos se hacen con JS -->
                    </div>
                </div>
                <button class="carousel-btn next" onclick="nextProduct()">›</button>
            </div>
        </div>
    </div>

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

        // Función para mostrar las pestañas
        function showTab(tabName) {
            // Ocultar todos los contenidos
            document.getElementById('description').style.display = 'none';
            document.getElementById('reviews').style.display = 'none';
            
            // Mostrar el contenido seleccionado
            document.getElementById(tabName).style.display = 'block';
            
            // Actualizar pestañas activas
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.textContent.toLowerCase().includes(tabName === 'description' ? 'descripción' : 'reseñas')) {
                    tab.classList.add('active');
                }
            });
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

        function updateCarousel() {
            const carousel = document.querySelector('.products-grid');
            const currentProductsPerView = window.innerWidth <= 768 ? 2 : 3;
            const cardWidth = 100 / currentProductsPerView; // Porcentaje que ocupa cada producto visible
            const translateX = -(currentProductIndex * cardWidth);
            carousel.style.transform = `translateX(${translateX}%)`;
            
            // Actualizar estados de los botones
            const prevBtn = document.querySelector('.carousel-btn.prev');
            const nextBtn = document.querySelector('.carousel-btn.next');
            
            prevBtn.disabled = currentProductIndex === 0;
            nextBtn.disabled = currentProductIndex >= totalProducts - currentProductsPerView;
        }

        // Generar productos dinámicamente
        function generateProducts() {
            const productsData = [
                { name: "Zapatillas Casual Urbanas", category: "Calzado, Laptops & Escritorios", currentPrice: "$80.00", oldPrice: "$120.00", sale: true, rating: 3 },
                { name: "Cartera de Cuero Premium", category: "Accesorios, Bolsos", currentPrice: "$120.00", oldPrice: null, sale: false, rating: 4 },
                { name: "Mochila Deportiva", category: "Calzado, Mochilas & Tabletas", currentPrice: "$95.00", oldPrice: null, sale: false, rating: 3 },
                { name: "Sudadera con Capucha", category: "Ropa, Mochilas & Tabletas", currentPrice: "$180.00", oldPrice: null, sale: false, rating: 4 },
                { name: "Reloj Deportivo", category: "Accesorios, Tecnología", currentPrice: "$200.00", oldPrice: "$250.00", sale: true, rating: 5 },
                { name: "Gafas de Sol", category: "Accesorios, Moda", currentPrice: "$75.00", oldPrice: null, sale: false, rating: 4 },
                { name: "Pantalones Deportivos", category: "Ropa, Deportes", currentPrice: "$65.00", oldPrice: "$85.00", sale: true, rating: 4 },
                { name: "Auriculares Bluetooth", category: "Tecnología, Audio", currentPrice: "$150.00", oldPrice: null, sale: false, rating: 5 }
            ];

            const grid = document.querySelector('.products-grid');
            grid.innerHTML = '';

            productsData.forEach((product, index) => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                
                const starsHtml = Array.from({length: 5}, (_, i) => 
                    `<span class="star ${i < product.rating ? 'filled' : ''}">★</span>`
                ).join('');

                const oldPriceHtml = product.oldPrice ? `<span class="old-price">${product.oldPrice}</span>` : '';
                const saleBadgeHtml = product.sale ? '<div class="sale-badge">OFERTA</div>' : '';

                productCard.innerHTML = `
                    <div class="product-image">
                        ${saleBadgeHtml}
                    </div>
                    <div class="product-details">
                        <div class="product-category">${product.category}</div>
                        <div class="product-rating">
                            ${starsHtml}
                        </div>
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">
                            <span class="current-price">${product.currentPrice}</span>
                            ${oldPriceHtml}
                        </div>
                    </div>
                `;
                
                grid.appendChild(productCard);
            });
        }

        // Manejar redimensionamiento de ventana
        function handleResize() {
            currentProductIndex = 0; // Reset al redimensionar
            updateCarousel();
        }

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            changeImage(0);
            generateProducts();
            updateCarousel();
            
            // Escuchar cambios de tamaño de ventana
            window.addEventListener('resize', handleResize);
        });
    </script>
</main>