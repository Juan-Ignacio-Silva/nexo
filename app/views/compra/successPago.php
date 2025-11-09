<?php
    include ROOT . 'app/views/compra/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago Exitoso</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: calc(100vh - 50px);
            background-color: #E1E9EF;
        }

        .card {
            animation: fadeIn 0.8s ease-out;
            background: white;
            border-radius: 20px;
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0) rotate(-45deg);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1) rotate(0);
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: checkmark 0.6s ease-out;
        }

        .success-icon svg {
            width: 50px;
            height: 50px;
            stroke: white;
            stroke-width: 3;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            color: #1f2937;
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 700;
        }

        p {
            color: #6b7280;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .payment-details {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #4b5563;
        }

        .detail-row:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #374151;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }

        .button-group {
            display: flex;
            gap: 15px;
            flex-direction: column;
        }

        @media (min-width: 480px) {
            .button-group {
                flex-direction: row;
            }
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #1976d2;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(63, 36, 219, 0.3);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #d1d5db;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="success-icon">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <h1>¡Pago Exitoso!</h1>
            <p>Tu transacción ha sido procesada correctamente. Pronto recibirás una confirmación en tu correo electrónico.</p>
            <div class="button-group">
                <a href="<?= BASE_URL ?>home" class="btn btn-secondary">Volver a la tienda</a>
                <a href="<?= BASE_URL ?>usuario/perfil/pago" class="btn btn-primary">Ver el pago</a>
            </div>
        </div>
    </div>
</body>
</html>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        total_productos = 0;
        localStorage.setItem("totalCarrito", total_productos);
        document.getElementById("contador-carrito").textContent = total_productos;
    })
</script>