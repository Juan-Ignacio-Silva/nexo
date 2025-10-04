<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/css/header.css">
<header class="encabezado">
    <div class="contenedor-encabezado">
        <div class="seccion-logo">
            <div class="logo-header">
                <img src="/images/nexo_logo.png" alt="Nexo">
            </div>
            <div class="ubicacion">
                <span class="texto-ubicacion">Ubicación</span>
                <span class="nombre-ubicacion">San José de Mayo</span>
            </div>
        </div>

        <div class="seccion-busqueda">
            <form id="form-busqueda">
                <input type="text" id="busqueda" name="q" class="barra-busqueda" placeholder="Buscar productos y más">
                <button class="boton-busqueda" type="submit">
                    <svg class="icono-busqueda" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>

        <div class="seccion-usuario">
            <?php if (Auth::restringirAcceso() === false): ?>
                <a href="<?= BASE_URL ?>usuario/registro" class="enlace-usuario">Crear cuenta</a>
                <a href="<?= BASE_URL ?>usuario/login" class="enlace-usuario">Ingresar</a>
            <?php endif; ?>
            <?php if (Auth::restringirAcceso() === true): ?>
                <a href="<?= BASE_URL ?>usuario/perfil" class="enlace-usuario">Perfil</a>
                <a href="#" class="enlace-usuario">Mis compras</a>
                <a href="<?= BASE_URL ?>usuario/logout" class="enlace-usuario">Cerrar sesion</a>
            <?php endif; ?>
            <div class="carrito-compras">
                <a href="<?= BASE_URL ?>productos/carrito">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart">
                        <circle cx="8" cy="21" r="1" />
                        <circle cx="19" cy="21" r="1" />
                        <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
                    </svg>
                </a>
                <p id="contador-carrito" class="totalCarrito"></p>
            </div>
        </div>
        <div class="navegacion-header-mobil">
            <div class="menu-hamburguesa">
                <div class="hamburguesa-close" id="menuHclose">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu">
                        <path d="M4 12h16" />
                        <path d="M4 18h16" />
                        <path d="M4 6h16" />
                    </svg>
                </div>
                <div class="hamburguesa-open" id="menuHopen">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <nav class="navegacion">
        <div class="contenedor-navegacion">
            <a href="<?= BASE_URL ?>home" class="enlace-navegacion">Inicio</a>
            <a href="<?= BASE_URL ?>home/categorias" class="enlace-navegacion">Categorías</a>
            <a href="#" class="enlace-navegacion">Ofertas</a>
            <a href="<?= BASE_URL ?>home/vender" class="enlace-navegacion">Vender</a>
            <a href="<?= BASE_URL ?>home/ayuda" class="enlace-navegacion">Ayuda</a>
        </div>
    </nav>
</header>

<div class="modal-menu-hamburguesa" id="modalMenuHamburguesa">
    <div class="contenedor-menu-modal">
        <?php if (Auth::restringirAcceso() === false): ?>
            <div class="contenedor-opciones">
                <p>MENU</p>
                <div class="separador"></div>
                <a href="<?= BASE_URL ?>usuario/registro">Crear cuenta</a>
                <a href="<?= BASE_URL ?>usuario/login">Ingresar</a>
            </div>
        <?php endif; ?>
        <?php if (Auth::restringirAcceso() === true): ?>
            <div class="contenedor-opciones">
                <p>MENU</p>
                <div class="separador"></div>
                <a href="<?= BASE_URL ?>usuario/perfil">Perfil</a>
                <a href="<?= BASE_URL ?>usuario/perfil">Configuración</a>
                <div class="separador"></div>
                <a href="<?= BASE_URL ?>usuario/logout">Cerrar sesion</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        let totalCarrito = parseInt(localStorage.getItem("totalCarrito")) || 0;
        document.getElementById("contador-carrito").textContent = totalCarrito;
    });
</script>

<!-- Modal Menu Hamburguesa -->
<script>
    const modal = document.getElementById('modalMenuHamburguesa');
    const buttonMenuOpen = document.getElementById('menuHopen');
    const buttonMenuClose = document.getElementById('menuHclose');

    buttonMenuClose.addEventListener('click', (e) => {
        modal.style.display = 'flex';
        buttonMenuClose.style.display = 'none';
        buttonMenuOpen.style.display = 'flex'
    });

    buttonMenuOpen.addEventListener('click', (e) => {
        modal.style.display = 'none'
        buttonMenuClose.style.display = 'flex';
        buttonMenuOpen.style.display = 'none'
    })
</script>

<script>
document.getElementById("form-busqueda").addEventListener("submit", async (e) => {
    e.preventDefault();

    const termino = document.getElementById("busqueda").value.trim();
    if (!termino) return;

    // Enviar GET al controlador de búsqueda
    const url = "<?= BASE_URL ?>productos/buscar?busqueda=" + encodeURIComponent(termino);
    const resp = await fetch(url);

    // Redirigir al catálogo para mostrar resultados
    window.location.href = "<?= BASE_URL ?>productos/catalogo";
});
</script>
