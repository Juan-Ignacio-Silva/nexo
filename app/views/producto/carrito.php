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
                    <!-- Producto 1 -->
                    <div class="cart-item" data-id="1">
                        <div class="item-image">
                            <span>Imagen del producto</span>
                        </div>
                        <div class="item-details">
                            <h3>Auriculares Sony WH-1000XM4</h3>
                            <div class="stars">★★★★★ (1)</div>
                            <div class="item-description">Auriculares inalámbricos con cancelación de ruido</div>
                            <div class="item-category">Categoría: Electrónica | Vende: Tienda Tech</div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(1, -1)">-</button>
                                <input type="number" class="quantity-input" value="1" min="1" onchange="setQuantity(1, this.value)">
                                <button class="quantity-btn" onclick="updateQuantity(1, 1)">+</button>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-price">$320.00</div>
                            <button class="remove-btn" onclick="removeItem(1)">Eliminar</button>
                        </div>
                    </div>
                    <!-- Producto 2 -->
                    <div class="cart-item" data-id="2">
                        <div class="item-image">
                            <span>Imagen del producto</span>
                        </div>
                        <div class="item-details">
                            <h3>iPhone 15 Pro Max</h3>
                            <div class="stars"></div>
                            <div class="item-description">Smartphone con chip A17 Pro y cámara de 48MP</div>
                            <div class="item-category">Categoría: Electrónica | Vende: Tienda Tech</div>
                            <div class="quantity-controls">
                                <button class="quantity-btn" onclick="updateQuantity(2, -1)">-</button>
                                <input type="number" class="quantity-input" value="1" min="1" onchange="setQuantity(2, this.value)">
                                <button class="quantity-btn" onclick="updateQuantity(2, 1)">+</button>
                            </div>
                        </div>
                        <div class="item-actions">
                            <div class="item-price">$1,199.00</div>
                            <button class="remove-btn" onclick="removeItem(2)">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cart-summary">
                <h2 class="summary-title">Resumen del Pedido</h2>
                <div class="summary-row">
                    <span>Subtotal (3 artículos):</span>
                    <span id="subtotal">$2,818.00</span>
                </div>
                <div class="summary-row">
                    <span>Envío:</span>
                    <span id="shipping">Gratis</span>
                </div>
                <div class="summary-row">
                    <span>Impuestos:</span>
                    <span id="taxes">$281.80</span>
                </div>
                <div class="summary-total">
                    <span>Total:</span>
                    <span id="total">$3,099.80</span>
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
        // Datos del carrito
        let cartItems = [{
                id: 1,
                name: "Auriculares Sony WH-1000XM4",
                price: 320.00,
                quantity: 1
            },
            {
                id: 2,
                name: "iPhone 15 Pro Max",
                price: 1199.00,
                quantity: 1
            }
        ];
        // Actualizar cantidad
        function updateQuantity(itemId, change) {
            const item = cartItems.find(item => item.id === itemId);
            if (item) {
                const newQuantity = item.quantity + change;
                if (newQuantity > 0) {
                    item.quantity = newQuantity;
                    updateCartDisplay();
                }
            }
        }
        // Establecer cantidad específica
        function setQuantity(itemId, quantity) {
            const item = cartItems.find(item => item.id === itemId);
            if (item && quantity > 0) {
                item.quantity = parseInt(quantity);
                updateCartDisplay();
            }
        }
        // Eliminar artículo
        function removeItem(itemId) {
            cartItems = cartItems.filter(item => item.id !== itemId);
            const itemElement = document.querySelector(`[data-id="${itemId}"]`);
            if (itemElement) {
                itemElement.remove();
            }
            updateCartDisplay();
            if (cartItems.length === 0) {
                showEmptyCart();
            }
        }
        // Actualizar display del carrito
        function updateCartDisplay() {
            // Actualizar inputs de cantidad
            cartItems.forEach(item => {
                const input = document.querySelector(`[data-id="${item.id}"] .quantity-input`);
                if (input) {
                    input.value = item.quantity;
                }
                const priceElement = document.querySelector(`[data-id="${item.id}"] .item-price`);
                if (priceElement) {
                    priceElement.textContent = `$${(item.price * item.quantity).toFixed(2)}`;
                }
            });
            // Calcular totales
            const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const taxes = subtotal * 0.1; // 10% de impuestos
            const total = subtotal + taxes;
            // Actualizar resumen
            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('taxes').textContent = `$${taxes.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }
        // Mostrar carrito vacío
        function showEmptyCart() {
            document.getElementById('cart-content').innerHTML = `
                <div class="empty-cart">
                    <h2>Tu carrito está vacío</h2>
                    <p>¡Agrega algunos productos para comenzar!</p>
                </div>
            `;
        }
        // Proceder al pago
        function proceedToCheckout() {
            if (cartItems.length === 0) {
                alert('Tu carrito está vacío');
                return;
            }
            alert('Redirigiendo al proceso de pago...');
        }
        // Continuar comprando
        function continueShopping() {
            alert('Redirigiendo a la tienda...');
        }
        // Inicializar
        updateCartDisplay();
    </script>
</body>
</html>