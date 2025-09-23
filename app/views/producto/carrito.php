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

<body>
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
                <button class="checkout-btn" onclick="proceedToCheckout()">
                    Proceder al Pago
                </button>
                <button class="continue-shopping" onclick="continueShopping()">
                    Continuar Comprando
                </button>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll(".remove-btn").forEach(boton => {
            boton.addEventListener("click", async () => {
                const idProducto = boton.dataset.id;

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
                    let total_productos = parseInt(localStorage.getItem("totalCarrito")) || 0;
                    total_productos = Math.max(0, total_productos - 1);
                    localStorage.setItem("totalCarrito", total_productos);

                    document.getElementById("contador-carrito").textContent = total_productos;
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            });
        });
    </script>
</body>

</html>