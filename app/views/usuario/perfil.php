<!-- PHP -->
<?php
$infoUser = Auth::infoUser();
?>

<title>Perfil de <?= Auth::usuario(); ?></title>
<link rel="stylesheet" href="<?= URL_PUBLIC ?>css/usuario/seccion-vista-perfil.css">
<section class="section-perfil-usuario">
    <div class="sidebar-opciones">
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
    </div>
</section>
