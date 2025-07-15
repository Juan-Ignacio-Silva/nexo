<link rel="stylesheet" href="<?= URL_PUBLIC ?>css/home.css">
<link rel="stylesheet" href="<?= URL_PUBLIC ?>css/categorias-home.css">
<main class="contenido-principal">
    <div class="contenedor-principal">
        <div class="seccion-hero">
            <div class="insignia">
                <svg class="icono-carrito" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="8" cy="21" r="1"></circle>
                    <circle cx="19" cy="21" r="1"></circle>
                    <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"></path>
                </svg>
                <span class="texto-insignia">La mejor tienda online</span>
            </div>
            
            <h1 class="titulo-principal">Tu tienda en un solo lugar</h1>
            
            <p class="descripcion">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                sed do eiusmod tempor incididunt ut labore et dolore.
            </p>
            
            <button class="boton-comprar">
                Comprar Ahora
                <svg class="icono-flecha" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</main>
<main class="main-categorias">
    <div class="contenedor-seccion-categorias">
        <!-- Encabezado de categorías -->
        <header class="encabezado-seccion-categorias">
            <h2 class="subtitulo-categorias">Categorías</h2>
            <h1 class="titulo-seccion-categorias">
                <span class="texto-azul-categorias">Categorías</span> 
                <span class="texto-naranja-categorias">Destacadas</span>
            </h1>
        </header>
        <!-- Sección de categorías circulares -->
        <section class="seccion-categorias-circulares">
            <div class="contenedor-carrusel-categorias">
                <button class="flecha-carrusel-categorias flecha-izquierda-categorias" id="flechaIzquierdaCategorias">
                    <svg class="icono-flecha-carrusel-categorias" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </button>

                <div class="contenedor-circulos-categorias" id="contenedorCirculosCategorias">
                    <div class="categoria-circular">
                        <div class="circulo-categoria">
                            <img src="/placeholder.svg?height=80&width=80" alt="Laptop" class="imagen-categoria">
                        </div>
                        <h3 class="nombre-categoria">Electrodomésticos</h3>
                        <p class="cantidad-productos-categoria">40 Productos</p>
                    </div>
                    <div class="categoria-circular">
                        <div class="circulo-categoria">
                            <img src="/placeholder.svg?height=80&width=80" alt="Olla de cocina" class="imagen-categoria">
                        </div>
                        <h3 class="nombre-categoria">Cocina</h3>
                        <p class="cantidad-productos-categoria">40 Productos</p>
                    </div>
                </div>

                <button class="flecha-carrusel-categorias flecha-derecha-categorias" id="flechaDerechaCategorias">
                    <svg class="icono-flecha-carrusel-categorias" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </section>
        <!-- Sección de tarjetas promocionales -->
        <section class="seccion-promociones-categorias">
            <div class="contenedor-tarjetas-categorias">
                <!-- Tarjeta azul -->
                <div class="tarjeta-promocional-categorias tarjeta-azul-categorias">
                    <div class="contenido-tarjeta-categorias">
                        <div class="informacion-tarjeta-categorias">
                            <span class="etiqueta-descuento-categorias">25% de Descuento</span>
                            <h3 class="titulo-producto-categorias">Texto acá del producto</h3>
                            <p class="descripcion-producto-categorias">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                sed do eiusmod tempor incididunt ut labore et dolore.
                            </p>
                            <button class="boton-comprar-promocion-categorias">
                                Comprar Ahora
                                <svg class="icono-flecha-promocion-categorias" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="imagen-tarjeta-categorias">
                            <img src="/placeholder.svg?height=200&width=250" alt="Laptop promocional" class="producto-imagen-categorias">
                        </div>
                    </div>
                </div>
                <!-- Tarjeta roja -->
                <div class="tarjeta-promocional-categorias tarjeta-roja-categorias">
                    <div class="contenido-tarjeta-categorias">
                        <div class="informacion-tarjeta-categorias">
                            <span class="etiqueta-descuento-categorias">25% de Descuento</span>
                            <h3 class="titulo-producto-categorias">Texto acá del producto</h3>
                            <p class="descripcion-producto-categorias">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                                sed do eiusmod tempor incididunt ut labore et dolore.
                            </p>
                            <button class="boton-comprar-promocion-categorias">
                                Comprar Ahora
                                <svg class="icono-flecha-promocion-categorias" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14"></path>
                                    <path d="m12 5 7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="imagen-tarjeta-categorias">
                            <img src="/placeholder.svg?height=200&width=250" alt="Olla de cocina promocional" class="producto-imagen-categorias">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
<script src="<?= URL_PUBLIC ?>js/carrusel-categorias.js"></script>
