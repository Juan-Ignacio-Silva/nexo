<!-- PHP -->
<?php
$infoUser = Auth::infoUser();
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfil de <?= Auth::usuario(); ?></title>
<link rel="stylesheet" href="/css/usuario/seccion-vista-perfil.css">
<section class="section-perfil-usuario">
    <div class="sidebar">
        <div class="opciones-sidebar">
            <h2>Mi cuenta</h2>
            <ul>
                <li class="btn-perfil">Cuenta</lil>
                <li class="btn-compras">Mis compras</li>
                <li class="btn-favoritos">Mis favoritos</li>
                <?php if (Auth::esAdmin() === true): ?>
                    <a href="<?= BASE_URL ?>admin/dashboard">Dashboard</a>
                <?php endif; ?>
            </ul>
        </div>
        <div class="opciones-sidebar-mobil">
            <ul>
                <li class="btn-sidebar-mobil selected-btn-sidebar btn-perfil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </li>
                <li class="btn-sidebar-mobil btn-compras">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag-icon lucide-shopping-bag">
                        <path d="M16 10a4 4 0 0 1-8 0" />
                        <path d="M3.103 6.034h17.794" />
                        <path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z" />
                    </svg>
                </li>
                <li class="btn-sidebar-mobil btn-favoritos">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text-icon lucide-scroll-text">
                        <path d="M15 12h-5" />
                        <path d="M15 8h-5" />
                        <path d="M19 17V5a2 2 0 0 0-2-2H4" />
                        <path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3" />
                    </svg>
                </li>
                <?php if (Auth::esAdmin() === true): ?>
                    <a href="<?= BASE_URL ?>admin/dashboard" class="btn-sidebar-mobil">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-user-icon lucide-shield-user">
                            <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                            <path d="M6.376 18.91a6 6 0 0 1 11.249.003" />
                            <circle cx="12" cy="11" r="4" />
                        </svg>
                    </a>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="vista-informacion-perfil" id="vista-informacion-perfil">
        <div class="header-perfil">
            <div class="foto-perfil">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-round-icon lucide-circle-user-round">
                    <path d="M18 20a6 6 0 0 0-12 0" />
                    <circle cx="12" cy="10" r="4" />
                    <circle cx="12" cy="12" r="10" />
                </svg>
            </div>
            <div class="informacion-perfil">
                <h3><?= ucfirst($infoUser['nombre']); ?> <?= ucfirst($infoUser['apellido']); ?></h3>
                <p><?= $infoUser['email']; ?></p>
            </div>
        </div>
        <div class="contenedor-config-user">
            <div class="cards-info-user">
                <form action="<?= BASE_URL . 'usuario/updateInfoUser' ?>" method="post" class="form-info-user">
                    <label for="nombre">Nombre</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Ingresar nuevo nombre">
                    <label for="apellido">Apellido</label>
                    <input type="text" name="apellido" id="apellido" placeholder="Ingresar nuevo apellido">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" autocomplete="off" placeholder="Ingresar nuevo email">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Ingresar nueva contraseña">
                    <button class="btn-save" type="submit">Guardar</button>
                </form>
            </div>
        </div>
    </div>
    <div class="vista-informacion-favoritos" id="vista-informacion-favoritos">

    </div>
    <div class="vista-informacion-compras" id="vista-informacion-compras">

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vistas = {
            perfil: document.getElementById('vista-informacion-perfil'),
            favoritos: document.getElementById('vista-informacion-favoritos'),
            compras: document.getElementById('vista-informacion-compras')
        };

        // Ocultamos todo menos el perfil
        for (const key in vistas) {
            if (key !== 'perfil') vistas[key].style.display = 'none';
        }

        // Función general para cambiar la vista
        async function mostrarVista(tipo) {
            // Oculta todas las vistas
            Object.values(vistas).forEach(v => v.style.display = 'none');

            // Muestra solo la elegida
            vistas[tipo].style.display = 'block';

            // Cargar datos del servidor si corresponde
            if (tipo === 'favoritos' || tipo === 'compras') {
                const urls = {
                    favoritos: "<?= BASE_URL ?>favoritos/getFavoritos",
                    compras: "<?= BASE_URL ?>compras/getComprasUsuario"
                };
                const vista = vistas[tipo];
                vista.innerHTML = "<p>Cargando datos...</p>";

                try {
                    const response = await fetch(urls[tipo]);
                    if (!response.ok) throw new Error("Error al obtener datos");
                    const data = await response.json();

                    if (data.length === 0) {
                        vista.innerHTML = `<p>No tenés ${tipo} todavía.</p>`;
                        return;
                    }

                    if (tipo === 'favoritos') {
                        vista.innerHTML = `
                        <h3>Mis favoritos</h3>
                        <div class="contenedor-favoritos">
                            ${data.map(prod => `
                                <div class="card-favorito">
                                    <img src="${prod.imagen}" alt="${prod.nombre}">
                                    <h4>${prod.nombre}</h4>
                                    <p>${prod.precio} USD</p>
                                    <a href="<?= BASE_URL ?>producto/ver/${prod.id}" class="btn-ver">Ver producto</a>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    }

                    if (tipo === 'compras') {
                        vista.innerHTML = `
                        <h3>Mis compras</h3>
                        <div class="contenedor-compras">
                            ${data.map(compra => `
                                <div class="card-compra">
                                    <p><strong>Producto:</strong> ${compra.nombre}</p>
                                    <p><strong>Fecha:</strong> ${compra.fecha}</p>
                                    <p><strong>Total:</strong> ${compra.total} USD</p>
                                </div>
                            `).join('')}
                        </div>
                    `;
                    }
                } catch (error) {
                    console.error(error);
                    vistas[tipo].innerHTML = `<p>Error al cargar ${tipo}.</p>`;
                }
            }
        }

        // Asignar eventos (desktop + móvil)
        const btnsPerfil = document.querySelectorAll('.btn-perfil');
        const btnsFavoritos = document.querySelectorAll('.btn-favoritos');
        const btnsCompras = document.querySelectorAll('.btn-compras');

        btnsPerfil.forEach(btn => btn.addEventListener('click', () => mostrarVista('perfil')));
        btnsFavoritos.forEach(btn => btn.addEventListener('click', () => mostrarVista('favoritos')));
        btnsCompras.forEach(btn => btn.addEventListener('click', () => mostrarVista('compras')));

        // Manejo visual del sidebar móvil (opcional)
        const sidebarMobil = document.querySelector('.opciones-sidebar-mobil ul');
        if (sidebarMobil) {
            const items = sidebarMobil.querySelectorAll('li');
            items.forEach(item => {
                item.addEventListener('click', function() {
                    items.forEach(i => i.classList.remove('selected-btn-sidebar'));
                    this.classList.add('selected-btn-sidebar');
                });
            });
        }
    });
</script>