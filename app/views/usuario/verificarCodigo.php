<?php
    include ROOT . 'app/views/compra/header.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código</title>
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
            font-family: segoe ui, tahoma, geneva, verdana, sans-serif;
            background: #E1E9EF;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .card {
            animation: fadeIn 0.8s ease-out;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .lock-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            background: #1976d2;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: lockBounce 0.8s ease-in-out 2s infinite;
        }

        @keyframes lockBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .lock-icon svg {
            width: 50px;
            height: 50px;
            stroke: white;
            stroke-width: 2;
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

        .form-group {
            text-align: left;
            margin-bottom: 30px;
        }

        label {
            display: block;
            color: #374151;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 4px;
            text-align: center;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input::placeholder {
            color: #d1d5db;
            font-weight: 400;
            letter-spacing: 2px;
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
            display: block;
            width: 100%;
            background: #1976d2;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="lock-icon">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
            </div>

            <h1>Verificar tu cuenta</h1>
            <p>Ingresa el código de 6 dígitos que recibiste en tu correo electrónico.</p>
            <form method="POST" action="/usuario/verificarCodigo">
                <div class="form-group">
                    <label for="code">Código de verificación</label>
                    <input
                        type="text"
                        id="code"
                        name="codigo"
                        placeholder="000000"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        required>
                </div>
                <button type="submit" class="btn">Verificar</button>
                <?php if (!empty($error)): ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>

</html>