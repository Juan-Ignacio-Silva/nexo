<header class="encabezado">
    <div class="contenedor-encabezado">
        <div class="seccion-logo">
            <div class="logo">
                <img src="/images/nexo_logo.png" alt="Nexo">
            </div>
        </div>
    </div>

    <nav class="navegacion">
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