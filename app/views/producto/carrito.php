<?php
require_once ROOT . 'app/controllers/CarritoController.php';
$data = CarritoController::infoProductoCarrito();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/producto/carrito.css">
</head>

<div class="container">
    <div class="cart-container">
        <div class="cart-items" id="cart-items">
            <div id="cart-content">
                <?php
                foreach ($data['productos'] as $producto): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <img src="<?= $producto['imagen'] ?>" alt="" style="width: 100%; height: 100%;">
                        </div>
                        <div class="item-details">
                            <h3><?= $producto['nombre'] ?></h3>
                            <div class="item-description"><?= $producto['descripcion'] ?></div>
                            <div class="item-category">Categoría: <?= $producto['categoria'] ?></div>
                            <div class="quantity-controls">
                                <input type="number" class="quantity-input" data-id="<?= $producto['id_producto'] ?>" value="<?= $producto['cantidad_carrito'] ?>" min="1">
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-price"><?= $producto['precio'] ?></div>
                            <button class="remove-btn" data-id="<?= $producto['id_producto'] ?>">Eliminar</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="cart-info-envio" id="cart-info-envio">
            <div class="header-cart-envio">
                <h2>Información de Envío</h2>
                <p id="btn-volver" class="btn-volver">Volver al carrito</p>
            </div>
            <form class="form-envio">
                <!-- Ingresar dirección o lugar -->
                <div class="form-group">
                    <label for="address">Ingresar dirección o lugar</label>
                    <input type="text" id="address" name="address" placeholder="Calle Principal 123" required>
                </div>

                <!-- Departamento y Localidad -->
                <div class="form-group">
                    <label for="department">Departamento</label>
                    <input type="text" id="department" name="department" placeholder="Tu departamento" required>
                </div>

                <div class="form-group">
                    <label for="locality">Localidad o barrio</label>
                    <input type="text" id="locality" name="locality" placeholder="Tu barrio o localidad" required>
                </div>

                <!-- Apartamento (opcional) -->
                <div class="form-group">
                    <label for="apartment">Apartamento <span class="optional">(opcional)</span></label>
                    <input type="text" id="apartment" name="apartment" placeholder="Apto. 5B">
                </div>

                <!-- Indicaciones para la entrega (opcional) -->
                <div class="form-group">
                    <label for="delivery-instructions">Indicaciones para la entrega <span class="optional">(opcional)</span></label>
                    <textarea id="delivery-instructions" name="delivery-instructions" placeholder="Ej: Toca timbre rojo, puerta lateral"></textarea>
                </div>

                <!-- Datos de contacto -->
                <h3 class="section-title">Datos de contacto</h3>
                <p class="contact-info-text">Te llamaremos si hay un problema con la entrega.</p>

                <!-- Nombre y Apellido -->
                <div class="form-group">
                    <label for="fullname">Nombre y Apellido</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Juan Pérez" required>
                </div>

                <!-- Teléfono -->
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="tel" id="phone" name="phone" placeholder="000 000 000" required>
                </div>
            </form>
        </div>
        <div class="cart-summary">
            <h2 class="summary-title">Resumen del Pedido</h2>
            <div class="summary-row">
                <span class="sub">Subtotal:</span>
                <?php
                foreach ($data['productos'] as $producto): ?>
                    <span id="subtotal">Articulo (<?= $producto['posicion'] ?>): $<?= $producto['subtotal'] ?></span>
                <?php endforeach; ?>
            </div>
            <div class="summary-row-envio">
                <span>Envío:</span>
                <span id="shipping">Gratis</span>
            </div>
            <div class="summary-total">
                <span>Total:</span>
                <span id="total">$<?= number_format($data['total'], 2) ?> </span>
            </div>
            <button class="checkout-btn" id="btn-continuar">
                Continuar compra
            </button>
            <button class="checkout-btn" id="btn-pagar" style="display: none;">
                Proceder con el pago
            </button>
        </div>
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
            stopOnFocus: true
        }).showToast();
    }
</script>

<script>
    document.querySelectorAll(".remove-btn").forEach(boton => {
        boton.addEventListener("click", async () => {
            const idProducto = boton.dataset.id;
            if (!idProducto) return mostrarToast("No se encontró el producto", "error");

            const resp = await fetch("<?= BASE_URL ?>carrito/eliminarProductoCarrito", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: idProducto
                })
            });

            const data = await resp.json();

            if (data.success) {
                mostrarToast("Producto eliminado.", "exito");
                let total_productos = parseInt(localStorage.getItem("totalCarrito")) || 0;
                total_productos = Math.max(0, total_productos - 1);
                localStorage.setItem("totalCarrito", total_productos);

                document.getElementById("contador-carrito").textContent = total_productos;
                setTimeout(() => window.location.reload(), 1000);
            } else {
                mostrarToast("Error al comunicarse con el servidor", "error");
            }
        });
    });

    document.querySelectorAll(".quantity-input").forEach(input => {
        input.addEventListener("change", async (e) => {
            const idProducto = e.target.dataset.id;
            const cantidad = parseInt(e.target.value);

            if (isNaN(cantidad) || cantidad < 0) {
                mostrarToast("Cantidad inválida.", "error");
                return;
            }

            try {
                const resp = await fetch("<?= BASE_URL ?>carrito/actualizarCantidad", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        idProducto,
                        cantidad
                    })
                });

                const data = await resp.json();

                if (data.success) {
                    mostrarToast(data.msg, "exito");
                    document.getElementById("contador-carrito").textContent = data.total_productos;
                } else {
                    mostrarToast(data.msg, "error");
                }

            } catch (err) {
                console.error(err);
                mostrarToast("Error al actualizar el carrito.", "error");
            }
        });
    });
</script>

<script>
    const btnContinuar = document.getElementById('btn-continuar');
    const cartItems = document.getElementById('cart-items');
    const cartDatos = document.getElementById('cart-info-envio');
    const btnPagar = document.getElementById('btn-pagar');
    const btnVolver = document.getElementById('btn-volver');

    document.addEventListener("DOMContentLoaded", () => {
        const envioVisible = localStorage.getItem("envioVisible");
        if (envioVisible === "true") {
            mostrarFormularioEnvio();
        }
    });

    function mostrarFormularioEnvio() {
        cartItems.style.display = 'none';
        cartDatos.style.display = 'flex';
        btnContinuar.style.display = 'none';
        btnPagar.style.display = 'block';
        btnVolver.style.display = 'inline-block';
    }

    function mostrarCarrito() {
        cartItems.style.display = 'block';
        cartDatos.style.display = 'none';
        btnContinuar.style.display = 'block';
        btnPagar.style.display = 'none';
        btnVolver.style.display = 'none';
        localStorage.removeItem("envioVisible");
    }

    btnContinuar.addEventListener("click", () => {
        mostrarFormularioEnvio();
        localStorage.setItem("envioVisible", "true");
    });

    btnVolver.addEventListener("click", () => {
        mostrarCarrito();
    });

    btnPagar.addEventListener("click", async () => {

        const direccion = document.getElementById('address').value.trim();
        const departamento = document.getElementById('department').value.trim();
        const localidad = document.getElementById('locality').value.trim();
        const apartamento = document.getElementById('apartment').value.trim();
        const indicaciones = document.getElementById('delivery-instructions').value.trim();
        const nombre = document.getElementById('fullname').value.trim();
        const telefono = document.getElementById('phone').value.trim();

        if (!direccion || !departamento || !localidad || !nombre || !telefono) {
            mostrarToast('Campos obligatorios faltantes.', 'error');
            return;
        }

        try {
            const res = await fetch("<?= BASE_URL ?>carrito/crearPreferencia", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    direccion,
                    departamento,
                    localidad,
                    apartamento,
                    indicaciones,
                    nombre,
                    telefono
                })
            });

            const data = await res.json();

            if (!data.success || !data.init_point) {
                mostrarToast(data.msg, "error");
                return;
            }

            localStorage.removeItem("envioVisible");

            // Redirigir a Mercado Pago
            window.location.href = data.init_point;

        } catch (err) {
            console.error("Error general:", err);
            mostrarToast("Error al procesar el pago.", "error");
        }
    });
</script>

</html>