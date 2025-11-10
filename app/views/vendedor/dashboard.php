<?php
require_once ROOT . 'app/controllers/ProductosController.php';
$productos = ProductosController::getProductosIdVendedor();
$productosArray = json_decode(json_encode($productos), true);
$totalProductos = count($productosArray);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/vendedor/dashboard.css">
    <title></title>
</head>

<body>
    <?php
    include ROOT . 'app/views/vendedor/dashboard-header.php';
    ?>

    <div class="container">
        <button class="btn btn-primary" onclick="openAddModal()">+ Agregar Producto</button>
        <div class="top-cards">
            <div class="card">
                <h4>Todal recaudado</h4>
                <p class="value">$200.00</p>
            </div>
            <div class="card">
                <h4>Productos vendidos</h4>
                <p class="value">0</p>
            </div>
            <div class="card">
                <h4>Total de productos</h4>
                <p class="value"><?= $totalProductos ?></p>
            </div>
        </div>
        <div class="products-section">
            <h2>Mis Productos</h2>
            <div class="table-container">
                <table id="productosTable">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Ganancia</th>
                            <th>Vendidos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="productosBody">
                        <!-- Los productos se generarán dinámicamente -->
                        <?php if (!empty($productos)): ?>
                            <?php foreach ($productos as $producto): ?>
                                <a href="<?= BASE_URL ?>productos/producto/<?= $producto['id_producto'] ?>" class="card-accion">
                                    <tr>
                                        <td><strong><?= htmlspecialchars($producto['nombre']) ?></strong></td>
                                        <td>US$ <?= number_format($producto['precio'] ?? 0, 2) ?></td>
                                        <td><?= htmlspecialchars($producto['cantidad']) ?></td>
                                        <td><?= htmlspecialchars($producto['id_categoria'] ?? 'Sin categoría') ?></td>
                                        <td>${p.vendidos}</td>
                                        <td>${getStockBadge(p.stock)}</td>
                                        <td>
                                            <button class="btn-edit-p">Editar</button>
                                            <button class="btn-delete-p">Eliminar</button>
                                        </td>
                                    </tr>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <td colspan="7" class="empty-state">
                                <div>No hay productos registrados. ¡Agrega tu primer producto!</div>
                            </td>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>

<!-- Modal para Agregar/Editar Producto -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle">Agregar Producto</h2>
        <form id="productForm">
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" id="nombreProducto" name="nombreProducto" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio ($)</label>
                <input type="number" id="precio" name="precio" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select id="categoria" name="categoria" required>
                    <option value="">Seleccionar...</option>
                    <!-- Las categorias se generan dinamicamente -->
                </select>
            </div>
            <div class="form-group">
                <label for="etiqueta">Etiqueta del Producto</label>
                <input type="text" id="etiquetas" name="etiquetas" placeholder="etiqueta1, etiqueta2, etc" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"></textarea>
            </div>
            <div class="form-group">
                <label for="etiqueta">Imagen del Producto</label>
                <input type="file" id="imagen" name="imagen" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button type="button" id="btn-save-p" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>
<!-- Librería Toastify -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
    function openAddModal() {
        document.getElementById('modalTitle').textContent = 'Agregar Producto';
        document.getElementById('productForm').reset();
        document.getElementById('productModal').classList.add('active');

        // Ejecuta la funcion para mostrar las categorias
        obtenerCategorias();
    }

    function editProduct(id) {
        const producto = productos.find(p => p.id === id);
        if (!producto) return;

        editingId = id;
        document.getElementById('modalTitle').textContent = 'Editar Producto';
        document.getElementById('nombre').value = producto.nombre;
        document.getElementById('precio').value = producto.precio;
        document.getElementById('stock').value = producto.stock;
        document.getElementById('categoria').value = producto.categoria;
        document.getElementById('vendidos').value = producto.vendidos;
        document.getElementById('descripcion').value = producto.descripcion || '';
        document.getElementById('productModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('productModal').classList.remove('active');
        document.getElementById('productForm').reset();
        editingId = null;
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function obtenerCategorias() {
        fetch('<?= BASE_URL ?>/categoria/obtenerCategorias')
            .then(res => res.json())
            .then(data => {
                const contenedorCat = document.getElementById('categoria');
                // contenedorCat.innerHTML = '';

                if (data.status === 'success') {
                    data.categorias.forEach(cat => {
                        contenedorCat.innerHTML += `
                            <option value="${cat.id}">${cat.nombre}</option>
                        `; 
                        // Si quiero el icono
                        // ${cat.icono_url || 'https://via.placeholder.com/80'}" alt="${cat.nombre}
                    });
                }
            });
    }
</script>

<script>
    document.getElementById("btn-save-p").addEventListener("click", async () => {
        const nombreProducto = document.getElementById('nombreProducto').value;
        const precio = document.getElementById('precio').value;
        const stock = document.getElementById('stock').value;
        const categoria = document.getElementById('categoria').value;
        const etiquetas = document.getElementById('etiquetas').value;
        const descripcion = document.getElementById('descripcion').value;
        const imagen = document.getElementById('imagen').value;

        try {
            // Enviar datos al backend
            const resp = await fetch("<?= BASE_URL ?>vendedor/publicarProducto", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    nombreProducto,
                    precio,
                    stock,
                    categoria,
                    etiquetas,
                    descripcion,
                    imagen
                })
            });

            const data = await resp.json();

            if (data.success) {
                mostrarToast("Producto publicado con exito.", "exito");
                setTimeout(() => window.location.reload(), 1000);
            } else {
                mostrarToast("Error al publicar el producto.", "error");
            }

        } catch (error) {
            console.error("Error en la solicitud:", error);
            alert("Hubo un problema al enviar los datos.");
        }
    });
</script>

</html>