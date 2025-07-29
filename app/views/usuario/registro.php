<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta</title>
    <link rel="stylesheet" href="<?= URL_PUBLIC ?>css/login.css">
</head>
<body>
    <div class="left-panel">
        <div class="logo">
            <img src="<?= URL_PUBLIC ?>images/nexo_logo.png" alt="Logo">
        </div>
        <h1 class="welcome-title">Bienvenido</h1>
        <p class="welcome-subtitle">
            Si ya tiene una cuenta,<br>
            inicie sesión con su información
        </p>
        <a href="login" class="switch-btn">Iniciar Sesión</a>
        <div class="copyright">© Copyright 2025 Todos los derechos reservados.</div>
    </div>
    
    <div class="right-panel">
        <div class="form-container">
            <h2 class="form-title">Crear cuenta</h2>
            
            <div class="avatar-section">
                <div class="avatar"></div>
            </div>
            
            <p class="email-hint">o ingrese su dirección email</p>
            
            <form method="post">
                <div class="form-row">
                    <input type="text" name="nombre" placeholder="Nombre" required>
                    <input type="text" name="apellido" placeholder="Apellido" required>
                </div>
                
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>
                
                <div class="form-group">
                    <input type="password" name="passwordConfirm" placeholder="Repita la contraseña" required>
                </div>
                <?php if (isset($error)): ?>
                    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
                <?php endif; ?>
                <button type="submit" class="submit-btn" name="register">Registrarse</button>
            </form>
        </div>
    </div>
</body>
</html>
