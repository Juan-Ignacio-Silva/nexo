<link rel="stylesheet" href="<?= URL_PUBLIC ?>css/header.css">
<header class="encabezado">
    <div class="contenedor-encabezado">
        <div class="seccion-logo">
            <div class="logo">
                <img src="<?= URL_PUBLIC ?>images/nexo_logo.png" alt="Nexo">
            </div>
            <div class="ubicacion">
                <span class="texto-ubicacion">Ubicación</span>
                <span class="nombre-ubicacion">San José de Mayo</span>
            </div>
        </div>
        
        <div class="seccion-busqueda">
            <input type="text" class="barra-busqueda" placeholder="Buscar productos y más">
            <button class="boton-busqueda">
                <svg class="icono-busqueda" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
        </div>
        
        <div class="seccion-usuario">
            <?php if (Auth::restringirAcceso() === false): ?>
                <a href="../usuario/registro" class="enlace-usuario">Crear cuenta</a>
                <a href="usuario/login" class="enlace-usuario">Ingresar</a>
            <?php endif; ?>
            <?php if (Auth::restringirAcceso() === true): ?>
                <a href="../usuario/perfil" class="enlace-usuario">Perfil</a>
                <a href="#" class="enlace-usuario">Mis compras</a>
                <a href="usuario/logout" class="enlace-usuario">Cerrar sesion</a>
            <?php endif; ?>
        </div>
    </div>
    
    <nav class="navegacion">
        <div class="contenedor-navegacion">
            <a href="../home" class="enlace-navegacion">Inicio</a>
            <a href="#" class="enlace-navegacion">Tienda</a>
            <a href="#" class="enlace-navegacion">Ofertas</a>
            <a href="vender/vender" class="enlace-navegacion">Vender</a>
            <a href="#" class="enlace-navegacion">Ayuda</a>
            <a href="#" class="enlace-navegacion">Categorías</a>
        </div>
    </nav>
</header>