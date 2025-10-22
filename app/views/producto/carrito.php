<?php 
if(session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../controllers/CarritoController.php';
$data = CarritoController::infoProductoCarrito();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/producto/carrito.css">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">


</head>
<body>
    <div class="container">
        <div class="cart-container">
            <div class="cart-items">
                <div id="cart-content">
                    <?php foreach($data['productos'] as $p): ?>
                    <div class="cart-item">
                        <div class="item-image"><span>Imagen</span></div>
                        <div class="item-details">
                            <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                            <div class="item-description"><?= htmlspecialchars($p['descripcion']) ?></div>
                            <div class="item-category">Categoría: <?= htmlspecialchars($p['categoria']) ?></div>
                            <div class="quantity-controls">
                                <input type="number" class="quantity-input" value="<?= $p['cantidad_carrito'] ?>" min="1">
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-price">$<?= number_format($p['precio'],2) ?></div>
                            <button class="remove-btn" data-id="<?= $p['id_producto'] ?>">Eliminar</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="cart-summary">
                <h2>Resumen del Pedido</h2>
                <div>
                    <span>Subtotal:</span>
                    <?php foreach($data['productos'] as $p): ?>
                    <div>Artículo <?= $p['posicion'] ?>: $<?= number_format($p['subtotal'],2) ?></div>
                    <?php endforeach; ?>
                </div>
                <div>Envío: Gratis</div>
                <div>Total: $<?= number_format($data['total'],2) ?></div>

                <button id="btn-pagar" class="checkout-btn">Proceder al pago</button>
                <button onclick="continueShopping()" class="checkout-btn">Continuar Comprando</button>
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

/* ELIMINAR PRODUCTO DEL CARRITO */
document.querySelectorAll(".remove-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
        const id = btn.dataset.id;
        if (!id) return mostrarToast("No se encontró el producto", "error");

        try {
            const resp = await fetch("<?= BASE_URL ?>carrito/eliminarProductoCarrito", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id })
            });

            const data = await resp.json();

            if (data.success) {
                mostrarToast("Producto eliminado del carrito..", "exito");
                setTimeout(() => window.location.reload(), 1000);
            } else {
                mostrarToast(data.msg || "Error al eliminar producto", "error");
            }

        } catch(err) {
            console.error(err);
            mostrarToast("Error al comunicarse con el servidor", "error");
        }
    });
});

/*  PROCESAR PAGO*/
const botonPagar = document.getElementById("btn-pagar");
if(botonPagar){
    botonPagar.addEventListener("click", async () => {
        try {
            const res = await fetch("<?= BASE_URL ?>carrito/crearPreferencia", {
                method: "POST",
                headers: { "Content-Type": "application/json" }
            });

            const data = await res.json();

            if(!data.success || !data.init_point){
                mostrarToast("Error al crear la preferencia de pago..", "error");
                console.error(data);
                return;
            }

            mostrarToast("Redirigiendo a medios de pago...", "aviso");

            // Redirigir al link de pago
            window.location.href = data.init_point;

        } catch(err){
            console.error(err);
            mostrarToast("Hubo un error al procesar el pago, intentalo de nuevo mas tarde..", "error");
        }
    });
}
</script>



</body>
</html>
