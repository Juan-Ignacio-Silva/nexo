<link rel="stylesheet" href="<?= URL_PUBLIC ?>/css/vistaUsuario/seccion-usuario.css">
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once ROOT . 'core/Auth.php';?>
    <title>Perfil de <?= Auth::usuario(); ?></title>
</head>
<body>
<main>
    <h1>Perfil de usuario de <?= Auth::usuario(); ?></h1>
    </main>
</body>
</html>

