<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Pago</title>
    <style>
        /* --- tus estilos originales intactos --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8fa3c4 0%, #a8b8d8 100%);
            min-height: 100vh;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            padding: 40px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .info-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-left: 4px solid #8fa3c4;
            padding-left: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .divider {
            height: 1px;
            background: #f0f0f0;
            margin: 30px 0;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
        }

        .products-table th {
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 700;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #f9f9f9;
            border-bottom: 2px solid #e0e0e0;
        }

        .products-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
            color: #333;
        }

        .summary {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .summary-item {
            display: flex;
            flex-direction: column;
        }

        .summary-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-value {
            font-size: 22px;
            font-weight: 700;
            color: #333;
        }

        .summary-value.total {
            color: #8fa3c4;
            font-size: 28px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="container" id="recibo">
        <!-- Header -->
        <div class="header">
            <h1>Comprobante de Pago</h1>
            <p>Recibo de su compra realizada</p>
        </div>

        <!-- Información del Cliente -->
        <div class="info-section">
            <div class="section-title">Información del Cliente</div>
            <div class="info-grid" id="info-cliente">
                <p>Cargando datos del cliente...</p>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Información de Envío -->
        <div class="info-section">
            <div class="section-title">Dirección de Envío</div>
            <div class="info-grid" id="info-envio">
                <p>Cargando datos de envío...</p>
            </div>
        </div>

        <div class="divider"></div>

        <!-- Productos Comprados -->
        <div class="products-section">
            <div class="section-title">Productos Comprados</div>
            <table class="products-table" id="tabla-productos">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tbody-productos">
                    <tr>
                        <td colspan="4">Cargando productos...</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="divider"></div>

        <!-- Resumen de Pago -->
        <div class="summary" id="resumen">
            <div class="summary-item">
                <span class="summary-label">Total</span>
                <span class="summary-value total">$0.00</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Gracias por su compra</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async () => {
            const params = new URLSearchParams(window.location.search);
            const external_reference = params.get('external_reference');

            if (!external_reference) {
                document.getElementById("recibo").innerHTML = "<h2>Error: No se recibieron datos.</h2>";
                return;
            }

            try {
                const response = await fetch(window.location.pathname + "?external_reference=" + encodeURIComponent(external_reference), {
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                });
                const result = await response.json();

                if (!result.success) {
                    document.getElementById("recibo").innerHTML = `<h2>${result.msg}</h2>`;
                    return;
                }

                if (result.success) {
                    localStorage.removeItem('totalCarrito');
                }

                const d = result.data;

                // Cliente
                document.getElementById("info-cliente").innerHTML = `
                <div class="info-item"><span class="info-label">Nombre</span><span class="info-value">${d.nombre}</span></div>
                <div class="info-item"><span class="info-label">ID Usuario</span><span class="info-value">#${d.id_usuario}</span></div>
                <div class="info-item"><span class="info-label">Teléfono</span><span class="info-value">${d.telefono}</span></div>
                <div class="info-item full-width"><span class="info-label">Total</span><span class="info-value">$${d.total.toFixed(2)}</span></div>
            `;

                // Envío
                document.getElementById("info-envio").innerHTML = `
                <div class="info-item full-width"><span class="info-label">Dirección</span><span class="info-value">${d.direccion}</span></div>
                <div class="info-item"><span class="info-label">Departamento</span><span class="info-value">${d.departamento}</span></div>
                <div class="info-item"><span class="info-label">Apartamento</span><span class="info-value">${d.apartamento}</span></div>
                <div class="info-item"><span class="info-label">Localidad</span><span class="info-value">${d.localidad}</span></div>
                <div class="info-item full-width"><span class="info-label">Indicaciones</span><span class="info-value">${d.indicaciones}</span></div>
            `;

                // Productos
                const tbody = document.getElementById("tbody-productos");
                tbody.innerHTML = "";
                if (d.productos.length > 0) {
                    d.productos.forEach((p, i) => {
                        tbody.innerHTML += `
                        <tr>
                            <td class="product-name">${d.productoName[i]}</td>
                            <td class="product-quantity">${d.cantidades[i]}</td>
                            <td class="product-price">$${parseFloat(d.precios[i]).toFixed(2)}</td>
                            <td class="product-price">$${parseFloat(d.subtotales[i]).toFixed(2)}</td>
                        </tr>`;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="4">No se recibieron productos.</td></tr>`;
                }

                // Total resumen
                document.querySelector("#resumen .summary-value.total").textContent = `$${d.total.toFixed(2)}`;

            } catch (err) {
                console.error(err);
                document.getElementById("recibo").innerHTML = "<h2>Error al cargar la información.</h2>";
            }
        });
    </script>
</body>

</html>