<header class="encabezado">
    <div class="contenedor-encabezado">
        <div class="seccion-logo">
            <div class="logo">
                <img src="/images/nexo_logo.png" alt="Nexo">
            </div>
        </div>
        <div class="seccion-buttons-header">
            <a href="../home"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-store-icon lucide-store">
                    <path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7" />
                    <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8" />
                    <path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4" />
                    <path d="M2 7h20" />
                    <path d="M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7" />
                </svg>
            </a>
        </div>

        <div class="seccion-usuario">
            <?php require_once ROOT . 'core/Auth.php';?>
            <p class="nombre-usuario"><?= Auth::usuario(); ?></p>
            <p class="role">Administrador</p>
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
        flex-direction: column;
    }

    .nombre-usuario {
        color: #EEF8FF;
        font-size: 18px;
    }

    .role {
        color: #1b2d3aff;
        font-size: 13px;
    }

    .seccion-buttons-header {
        display: flex;
        padding: 20px;
        gap: 20px;
    }

    .seccion-buttons-header a {
        color: #EEF8FF;
        width: 15px;
        text-decoration: none;
    }

    .seccion-buttons-header a:hover {
        color: #284356;
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