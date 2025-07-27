<header class="encabezado">
    <div class="contenedor-encabezado">
        <div class="seccion-logo">
            <div class="logo">
                <img src="<?= URL_PUBLIC ?>images/nexo_logo.png" alt="Nexo">
            </div>
        </div>

        <div class="seccion-busqueda">
            <input type="text" class="barra-busqueda" placeholder="Buscar algo???">
            <button class="boton-busqueda">
                <svg class="icono-busqueda" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>
            </button>
        </div>

        <div class="seccion-usuario">
            <a href="usuario/registro" class="enlace-usuario">Crear cuenta</a>
            <a href="usuario/login" class="enlace-usuario">Ingresar</a>
            <a href="#" class="enlace-usuario">Mis compras</a>
        </div>
    </div>

    <nav class="navegacion">
        <div class="contenedor-navegacion">
            <h1>Panel de administración</h1>
        </div>
    </nav>
</header>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Encabezado */
    .encabezado {
        background-color: #2c5aa0;
        color: white;
    }

    .contenedor-encabezado {
        max-width: 1200px;
        margin: 0 auto;
        padding: 12px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    /* Sección del logo */
    .seccion-logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
    }

    /* Sección de búsqueda */
    .seccion-busqueda {
        flex: 1;
        max-width: 600px;
        position: relative;
    }

    .barra-busqueda {
        width: 100%;
        padding: 12px 50px 12px 16px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        background-color: rgba(255, 255, 255, 0.9);
        color: #333;
    }

    .barra-busqueda::placeholder {
        color: #666;
    }

    .boton-busqueda {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 8px;
        cursor: pointer;
        color: #666;
    }

    .icono-busqueda {
        width: 20px;
        height: 20px;
    }

    /* Sección de usuario */
    .seccion-usuario {
        display: flex;
        gap: 20px;
    }

    .enlace-usuario {
        color: white;
        text-decoration: none;
        font-size: 14px;
        white-space: nowrap;
    }

    .enlace-usuario:hover {
        text-decoration: underline;
    }

    /* Navegación */
    .navegacion {
        background-color: rgba(0, 0, 0, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .contenedor-navegacion {
        max-width: 1200px;
        margin: 0 auto;
        padding: 5px 20px;
    }

    .contenedor-navegacion h1 {
        color: #EEF8FF;
    }
</style>