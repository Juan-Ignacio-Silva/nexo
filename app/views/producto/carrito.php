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
        <div class="cart-items">
            <div id="cart-content">
                <?php
                foreach ($data['productos'] as $producto): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <span>Imagen del producto</span>
                        </div>
                        <div class="item-details">
                            <h3><?= $producto['nombre'] ?></h3>
                            <div class="item-description"><?= $producto['descripcion'] ?></div>
                            <div class="item-category">Categoría: <?= $producto['categoria'] ?></div>
                            <div class="quantity-controls">
                                <input type="number" class="quantity-input" value="<?= $producto['cantidad_carrito'] ?>" min="1">
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
            <button class="checkout-btn" id="btn-pagar">
                Proceder al Pago
            </button>
            <button class="continue-shopping" onclick="continueShopping()">
                Continuar Comprando
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

    /* BOTÓN DE CONTINUAR COMPRANDO */
    function continueShopping() {
        window.location.href = "<?= BASE_URL ?>";
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
                mostrarToast("Producto eliminado del carrito con exito", "exito");
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
</script>

<script>
    function continueShopping(){ 
        window.location.href="<?= BASE_URL ?>"; 
    }

    document.getElementById("btn-pagar").addEventListener("click", async () => {
        try {
            const res = await fetch("<?= BASE_URL ?>carrito/crearPreferencia", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                }
            });

            const data = await res.json();

            if (!data.success || !data.init_point) {
                alert("Error al crear la preferencia de pago.");
                console.error(data);
                return;
            }

            // Redirigir a Mercado Pago
            window.location.href = data.init_point;

        } catch (err) {
            console.error("Error general:", err);
            alert("Hubo un error al procesar el pago.");
        }
    });
</script>

</html>