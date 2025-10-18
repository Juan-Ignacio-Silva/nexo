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
                <p class="value">3</p>
            </div>
            <div class="card">
                <h4>Total de productos</h4>
                <p class="value">4</p>
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
                <input type="text" id="nombre" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio ($)</label>
                <input type="number" id="precio" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input type="number" id="stock" min="0" required>
            </div>
            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select id="categoria" required>
                    <option value="">Seleccionar...</option>
                    <option value="Electrónica">Electrónica</option>
                    <option value="Ropa">Ropa</option>
                    <option value="Alimentos">Alimentos</option>
                    <option value="Hogar">Hogar</option>
                    <option value="Deportes">Deportes</option>
                    <option value="Otros">Otros</option>
                </select>
            </div>
            <div class="form-group">
                <label for="vendidos">Unidades Vendidas</label>
                <input type="number" id="vendidos" min="0" value="0" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion"></textarea>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>

<script>
    let productos = JSON.parse(localStorage.getItem('productos')) || [];
    let editingId = null;

    function saveToLocalStorage() {
        localStorage.setItem('productos', JSON.stringify(productos));
    }

    function updateStats() {
        const totalRecaudado = productos.reduce((sum, p) => sum + (p.precio * p.vendidos), 0);
        const productosVendidos = productos.reduce((sum, p) => sum + p.vendidos, 0);
        const totalProductos = productos.length;

        document.getElementById('totalRecaudado').textContent = `$${totalRecaudado.toFixed(2)}`;
        document.getElementById('productosVendidos').textContent = productosVendidos;
        document.getElementById('totalProductos').textContent = totalProductos;
    }

    function getStockBadge(stock) {
        if (stock === 0) return '<span class="badge badge-danger">Sin Stock</span>';
        if (stock < 10) return '<span class="badge badge-warning">Stock Bajo</span>';
        return '<span class="badge badge-success">Disponible</span>';
    }

    function renderProductos() {
        const tbody = document.getElementById('productosBody');

        if (productos.length === 0) {
            tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div>No hay productos registrados. ¡Agrega tu primer producto!</div>
                        </td>
                    </tr>
                `;
            return;
        }

        tbody.innerHTML = productos.map(p => `
                <tr>
                    <td><strong>${p.nombre}</strong></td>
                    <td>$${p.precio.toFixed(2)}</td>
                    <td>${p.stock}</td>
                    <td>${p.categoria}</td>
                    <td>${p.vendidos}</td>
                    <td>${getStockBadge(p.stock)}</td>
                    <td>
                        <button class="btn-edit-p" onclick="editProduct(${p.id})">Editar</button>
                        <button class="btn-delete-p" onclick="deleteProduct(${p.id})">Eliminar</button>
                    </td>
                </tr>
            `).join('');
    }

    function openAddModal() {
        editingId = null;
        document.getElementById('modalTitle').textContent = 'Agregar Producto';
        document.getElementById('productForm').reset();
        document.getElementById('productModal').classList.add('active');
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

    function deleteProduct(id) {
        if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
            productos = productos.filter(p => p.id !== id);
            saveToLocalStorage();
            renderProductos();
            updateStats();
        }
    }

    document.getElementById('productForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const productoData = {
            nombre: document.getElementById('nombre').value,
            precio: parseFloat(document.getElementById('precio').value),
            stock: parseInt(document.getElementById('stock').value),
            categoria: document.getElementById('categoria').value,
            vendidos: parseInt(document.getElementById('vendidos').value),
            descripcion: document.getElementById('descripcion').value
        };

        if (editingId) {
            const index = productos.findIndex(p => p.id === editingId);
            productos[index] = {
                ...productos[index],
                ...productoData
            };
        } else {
            const newProduct = {
                id: Date.now(),
                ...productoData
            };
            productos.push(newProduct);
        }

        saveToLocalStorage();
        renderProductos();
        updateStats();
        closeModal();
    });

    // Cerrar modal al hacer clic fuera
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Inicializar
    renderProductos();
    updateStats();
</script>

</html>