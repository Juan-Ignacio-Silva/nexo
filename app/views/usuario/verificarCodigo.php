<h2>Verificación de código</h2>
<form method="POST" action="/usuario/verificarCodigo">
    <input type="text" name="codigo" placeholder="Código recibido" required>
    <button type="submit">Verificar</button>
</form>
<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
