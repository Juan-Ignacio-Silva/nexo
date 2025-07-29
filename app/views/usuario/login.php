<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="<?= URL_PUBLIC ?>css/login.css">
</head>
<body>
    <div class="left-panel">
        <div class="logo">
            <img src="<?= URL_PUBLIC ?>images/nexo_logo.png" alt="Logo">
        </div>
        <h1 class="welcome-title">Hola de nuevo</h1>
        <p class="welcome-subtitle">
            Si no tiene una cuenta,<br>
            porfavor regístrese
        </p>
        <a href="registro" class="switch-btn">Registrarse</a>
        <div class="copyright">© Copyright 2025 Todos los derechos reservados.</div>
    </div>
    
    <div class="right-panel">
        <div class="form-container">
            <h2 class="form-title">Iniciar sesión</h2>
            
            <div class="avatar-section">
                <div class="avatar"></div>
            </div>
            
            <p class="email-hint">o ingrese su dirección email</p>
            
            <form method="post">
                <div class="form-group">
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Contraseña" name="password" required>
                </div>
                <?php if (isset($error)): ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <button type="submit" class="submit-btn">Iniciar sesión</button>
            </form>
        </div>
    </div>
</body>
</html>