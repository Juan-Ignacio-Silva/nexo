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
                <p class="value"> <?= $totalP ?> </p>
            </div>
            <div class="card">
                <h4>Usuarios Registrados</h4>
                <p class="value"> <?= $totalU ?> </p>
            </div>
        </div>

        <div class="section usuarios">
            <h3>Nuevos Usuarios</h3>
            <p class="sub">Registros recientes - max 5</p>
            <ul class="lista-usuarios">
                <?php foreach ($recienRegistrados as $user): ?>
                    <li>
                        <div> <?= $user['nombre'] ?> <br><small> <?= $user['email'] ?> </small></div><span> <?= substr($user['fecha_registro'], 0, 19) ?> </span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <!-- Esto se puede implementar en caso de ser requerido -->
            <div class="search-box" style="display: none;">
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
                <button onclick="abrirModal('Categorias')" id="gestionCategorias">Gestionar Categorías</button>
                <button onclick="abrirModal('Roles')" id="gestionRoles">Gestión de Roles</button>
            </div>
            <!-- Esto se puede implementar en caso de ser requerido -->
            <div class="panel" style="display: none;">
                <h3>Seguridad y Control</h3>
                <p>Monitoreo de accesos</p>
                <div class="alert red">Intentos fallidos <span>12</span></div>
                <div class="alert green">Accesos exitosos <span>847</span></div>
                <button onclick="verLogs()">Ver Logs Completos</button>
            </div>
            <!-- Esto se puede implementar en caso de ser requerido -->
            <div class="panel" style="display: none;">
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

    <!-- Modal -->
    <div class="modalCategoria" id="modalCategoria">
        <div class="modal-content-cat">
            <div class="modal-header-cat">
                <h2>Gestor de Categorías</h2>
                <button class="close-btn" onclick="cerrarModal('Categorias')">&times;</button>
            </div>

            <div class="modal-body">
                <!-- Lista de categorías -->
                <div>
                    <div class="categories-list" id="categoriesList"></div>
                </div>

                <!-- Formulario para agregar -->
                <div class="form-section">
                    <h3>Agregar Nueva</h3>
                    <form>
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" id="nombreCategoria" required placeholder="Ej: Tecnología">
                        </div>
                        <div class="form-group">
                            <label for="url">URL del ícono</label>
                            <input type="url" id="iconoCategoria" required placeholder="Ej: https://...">
                        </div>
                        <button class="btn-add" id="agregarCategoria">+ Agregar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Roles-->
     <link rel="stylesheet" href="/css/admin/modal-roles.css">
    <div class="modalRoles" id="modalRoles">
        <div class="modal-content-roles">
            <div class="user-roles-modal-header">
                <h2>Gestor de roles de usuarios</h2>
                <button class="user-roles-close-btn" onclick="cerrarModal('Roles')">&times;</button>
            </div>

            <div class="user-roles-modal-body">
                <div class="user-roles-search-section">
                    <input type="text" class="user-roles-search-input" id="searchInput" placeholder="Buscar usuario por nombre o id" oninput="buscarUsuarios()">
                </div>

                <div class="user-roles-list" id="usersList">
                    <div class="user-roles-empty-state">Ingresa un nombre o ID para buscar usuarios</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal adicional para la modal de gestion de roles -->
    <link rel="stylesheet" href="/css/admin/modal-addic-role.css">
    <div class="user-roles-modal-edit" id="modalEdit">
        <div class="user-roles-modal-edit-content">
            <h3>Editar rol de usuario</h3>
            <div class="user-roles-form-group">
                <label>Usuario: <span id="editUserName"></span></label>
            </div>
            <div class="user-roles-form-group">
                <label for="roleSelect">Nuevo rol</label>
                <select id="roleSelect">
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="user">Usuario</option>
                </select>
            </div>
            <div class="user-roles-modal-buttons">
                <button class="user-roles-btn-cancel" onclick="cerrarEditModal()">Cancelar</button>
                <button class="user-roles-btn-save" onclick="guardarRol()">Guardar</button>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    /*  FUNCIONES DE NOTIFICACIÓN de TOASTIFY*/
    function mostrarToast(mensaje, tipo = "info") {
        let color = "#0263AA";
        if (tipo === "exito") color = "#28a745";
        if (tipo === "error") color = "#dc3545";
        if (tipo === "aviso") color = "#ffc107";

        Toastify({
            text: mensaje,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: color,
            close: true,
            stopOnFocus: true,
            style: {
                borderRadius: "4px",

            }
        }).showToast();
    }
</script>
<script>
    let categorias = [];

    // Abrir modales
    function abrirModal($tipo) {
        const modalCat = document.getElementById('modalCategoria');
        const modalRoles = document.getElementById('modalRoles');

        if ($tipo === 'Categorias') {
            modalCat.style.display = 'flex';
            obtenerCategorias();
        } else if ($tipo === 'Roles') {
            modalRoles.style.display = 'flex';
        } else {
            mostrarToast('Error al abrir la modal', 'error')
        }
    }

    // Cerrar modales
    function cerrarModal($tipo) {
        const modalCat = document.getElementById('modalCategoria');
        const modalRoles = document.getElementById('modalRoles');

        if ($tipo === 'Categorias') {
            modalCat.style.display = 'none';
        } else if ($tipo === 'Roles') {
            modalRoles.style.display = 'none';
        } else {
            mostrarToast('Error al cerrar la modal', 'error')
        }
    }

    // Obtener categorías desde el servidor
    function obtenerCategorias() {
        fetch('<?= BASE_URL ?>/categoria/obtenerCategorias')
            .then(res => res.json())
            .then(data => {
                const contenedorCat = document.getElementById('categoriesList');
                contenedorCat.innerHTML = '';

                if (data.status === 'success') {
                    categorias = data.categorias;

                    if (categorias.length === 0) {
                        contenedorCat.innerHTML = `
                            <div class="empty-state">
                                No hay categorías. ¡Agrega una nueva!
                            </div>`;
                        return;
                    }

                    renderizarCategorias();
                } else {
                    contenedorCat.innerHTML = `
                        <div class="error-state">
                            Error al cargar las categorías.
                        </div>`;
                }
            })
            .catch(err => {
                console.error("Error al obtener categorías:", err);
                document.getElementById('categoriesList').innerHTML = `
                    <div class="error-state">Error de conexión al cargar las categorías.</div>
                `;
            });
    }

    // Renderizar categorías en la vista
    function renderizarCategorias() {
        const contenedorCat = document.getElementById('categoriesList');
        contenedorCat.innerHTML = '';

        categorias.forEach(cat => {
            contenedorCat.innerHTML += `
                <div class="category-item" data-id="${cat.id}">
                    <div class="category-info">
                        <div class="category-icon">
                            <img src="${cat.icono_url}" alt="${cat.nombre}" 
                                 onerror="this.style.display='none'">
                        </div>
                        <div class="category-details">
                            <div class="category-name">${cat.nombre}</div>
                            <div class="category-url">${cat.icono_url}</div>
                        </div>
                    </div>
                    <div class="category-actions">
                        <button class="btn-action btn-delete" onclick="eliminarCategoria(${cat.id})">Eliminar</button>
                    </div>
                </div>
            `; // <button class="btn-action btn-edit" onclick="editarCategoria(${cat.id})">✏️ Editar</button>
        });
    }

    // Eliminar categoría del array (simulación frontend)
    function eliminarCategoria(id) {
        if (!confirm('¿Estás seguro de que deseas eliminar esta categoría?')) return;

        fetch('<?= BASE_URL ?>/categoria/eliminarCategoria', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    categorias = categorias.filter(c => c.id !== id);
                    renderizarCategorias();
                    mostrarToast('Categoría eliminada correctamente.', 'exito');
                } else {
                    mostrarToast(data.msg || 'Error al eliminar la categoría.', 'error');
                }
            })
            .catch(err => {
                console.error("Error al eliminar categoría:", err);
                mostrarToast('Error de conexión al eliminar la categoría.', 'error');
            });
    }

    function crearCategoria() {
        const nombre = document.getElementById('nombreCategoria').value.trim();
        const icono_url = document.getElementById('iconoCategoria').value.trim();

        if (nombre === '') {
            mostrarToast('El nombre de la categoría es obligatorio.', 'error');
            return;
        }

        fetch('<?= BASE_URL ?>/categoria/crearCategoria', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    nombre: nombre,
                    icono_url: icono_url
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    mostrarToast('Categoría creada correctamente', 'success');

                    // Actualiza la lista de categorías
                    obtenerCategorias();

                    // Limpia los campos del formulario
                    document.getElementById('nombreCategoria').value = '';
                    document.getElementById('iconoCategoria').value = '';
                } else {
                    mostrarToast(data.mensaje || 'Error al crear la categoría', 'error');
                }
            })
            .catch(err => {
                console.error('Error al crear categoría:', err);
                mostrarToast('Error de conexión con el servidor.', 'error');
            });
    }

    const agregarCategoria = document.getElementById('agregarCategoria').addEventListener('click', () => {
        crearCategoria();
    })
</script>

<style>
    /* Modal */
    .modalCategoria {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .modal.active {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .modal-content-cat {
        background: white;
        border-radius: 4px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 900px;
        max-height: 80vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header-cat {
        padding: 24px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header-cat h2 {
        font-size: 24px;
        color: #333;
    }

    .close-btn {
        background: none;
        border: none;
        font-size: 28px;
        cursor: pointer;
        color: #999;
        transition: color 0.3s;
    }

    .close-btn:hover {
        color: #333;
    }

    .modal-body {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 24px;
        padding: 24px;
        min-height: 400px;
    }

    /* Lista de categorías */
    .categories-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .category-item {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .category-item:hover {
        background: #f0f0f0;
        border-color: #667eea;
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 1;
    }

    .category-icon {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        min-width: 40px;
    }

    .category-icon img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .category-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .category-name {
        font-weight: 600;
        color: #333;
        font-size: 15px;
    }

    .category-url {
        font-size: 12px;
        color: #999;
        word-break: break-all;
    }

    .category-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        border: none;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-delete {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-delete:hover {
        background: #ffcdd2;
    }

    /* Formulario */
    .form-section {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-section h3 {
        font-size: 18px;
        color: #333;
        text-align: left;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #555;
        text-align: left;
    }

    .form-group input {
        padding: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-add {
        background: #0263AA;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 4px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 8px;
    }

    .btn-add:hover {
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-add:active {
        transform: translateY(0);
    }

    /* Mensaje vacío */
    .empty-state {
        text-align: center;
        color: #999;
        padding: 40px 20px;
        font-size: 14px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .modal-body {
            grid-template-columns: 1fr;
        }

        .form-section {
            border-top: 1px solid #e0e0e0;
            padding-top: 16px;
        }

        .category-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .category-actions {
            align-self: flex-end;
        }
    }
</style>