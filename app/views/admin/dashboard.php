<link rel="stylesheet" href="/css/admin/dashboard.css">
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Panel de administración</title>
</head>

<body>
    <?php
    include ROOT . 'app/views/admin/header-dashboard.php';
    ?>

    <div class="container">

        <div class="top-cards">
            <div class="card">
                <h4>Productos Registrados</h4>
                <p class="value">213</p>
            </div>
            <div class="card">
                <h4>Usuarios Registrados</h4>
                <p class="value">1,847</p>
            </div>
        </div>

        <div class="section usuarios">
            <h3>Nuevos Usuarios</h3>
            <p class="sub">Registros recientes</p>
            <ul class="lista-usuarios">
                <li>
                    <div>Sofia Chen<br><small>sofia@email.com</small></div><span>2024-01-15</span>
                </li>
                <li>
                    <div>Diego Ruiz<br><small>diego@email.com</small></div><span>2024-01-15</span>
                </li>
                <li>
                    <div>Elena Vega<br><small>elena@email.com</small></div><span>2024-01-14</span>
                </li>
                <li>
                    <div>Elena Vega<br><small>elena@email.com</small></div><span>2024-01-14</span>
                </li>
            </ul>
            <div class="search-box">
                <input type="text" placeholder="Buscar usuarios...">
                <button>
                    <svg class="icono-busqueda" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="bottom-panels">
            <div class="panel">
                <h3>Administración</h3>
                <p>Configuración del sistema</p>
                <button>Gestionar Categorías</button>
                <button>Configuración General</button>
                <button>Gestión de Roles</button>
            </div>

            <div class="panel">
                <h3>Seguridad y Control</h3>
                <p>Monitoreo de accesos</p>
                <div class="alert red">Intentos fallidos <span>12</span></div>
                <div class="alert green">Accesos exitosos <span>847</span></div>
                <button onclick="verLogs()">Ver Logs Completos</button>
            </div>

            <div class="panel">
                <h3>Actividad Reciente</h3>
                <p>Últimas acciones del sistema</p>
                <ul class="actividad">
                    <li><b>Actualización de producto</b><br>admin@tienda.com - 2024-01-15 14:30 - 192.168.1.100</li>
                    <li><b>Procesamiento de pedido</b><br>vendedor@tienda.com - 2024-01-15 14:25 - 192.168.1.101</li>
                    <li><b>Respuesta a ticket</b><br>soporte@tienda.com - 2024-01-15 14:20 - 192.168.1.102</li>
                    <li><b>Respuesta a ticket</b><br>soporte@tienda.com - 2024-01-15 14:20 - 192.168.1.102</li>
                </ul>
            </div>
        </div>

    </div>
</body>

</html>