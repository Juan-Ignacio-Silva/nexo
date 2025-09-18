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
                <li>Cuenta</li>
                <li>Mis compras</li>
                <li>Mis ventas</li>
                <li>Configuración</li>
                <?php if (Auth::esAdmin() === true): ?>
                    <a href="<?= BASE_URL ?>admin/dashboard">Dashboard</a>
                <?php endif; ?>
            </ul>
        </div>
        <div class="opciones-sidebar-mobil">
            <ul>
                <li class="btn-sidebar-mobil selected-btn-sidebar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </li>
                <li class="btn-sidebar-mobil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-bag-icon lucide-shopping-bag">
                        <path d="M16 10a4 4 0 0 1-8 0" />
                        <path d="M3.103 6.034h17.794" />
                        <path d="M3.4 5.467a2 2 0 0 0-.4 1.2V20a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6.667a2 2 0 0 0-.4-1.2l-2-2.667A2 2 0 0 0 17 2H7a2 2 0 0 0-1.6.8z" />
                    </svg>
                </li>
                <li class="btn-sidebar-mobil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-scroll-text-icon lucide-scroll-text">
                        <path d="M15 12h-5" />
                        <path d="M15 8h-5" />
                        <path d="M19 17V5a2 2 0 0 0-2-2H4" />
                        <path d="M8 21h12a2 2 0 0 0 2-2v-1a1 1 0 0 0-1-1H11a1 1 0 0 0-1 1v1a2 2 0 1 1-4 0V5a2 2 0 1 0-4 0v2a1 1 0 0 0 1 1h3" />
                    </svg>
                </li>
                <li class="btn-sidebar-mobil">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-cog-icon lucide-cog">
                        <path d="M11 10.27 7 3.34" />
                        <path d="m11 13.73-4 6.93" />
                        <path d="M12 22v-2" />
                        <path d="M12 2v2" />
                        <path d="M14 12h8" />
                        <path d="m17 20.66-1-1.73" />
                        <path d="m17 3.34-1 1.73" />
                        <path d="M2 12h2" />
                        <path d="m20.66 17-1.73-1" />
                        <path d="m20.66 7-1.73 1" />
                        <path d="m3.34 17 1.73-1" />
                        <path d="m3.34 7 1.73 1" />
                        <circle cx="12" cy="12" r="2" />
                        <circle cx="12" cy="12" r="8" />
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
    <div class="vista-informacion-perfil">
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
                <form action="<?= BASE_URL . 'usuario/updateInfoUser'?>" method="post" class="form-info-user">
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
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarMobil = document.querySelector('.opciones-sidebar-mobil ul');
        if (!sidebarMobil) return;
        const items = sidebarMobil.querySelectorAll('li');
        console.log(items)
        items.forEach(item => {
            item.addEventListener('click', function() {
                console.log("dsdsd")
                items.forEach(i => i.classList.remove('selected-btn-sidebar'));
                this.classList.add('selected-btn-sidebar');
            });
        });
    });
</script>