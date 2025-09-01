<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? "Nexo" ?></title>
</head>
<body>
    <?php include ROOT . "app/views/templates/header.php"; ?>

    <main>
        <?php include $viewFile; ?> <!-- Aquí se cargan las vistas -->
    </main>

    <?php include ROOT . "app/views/templates/footer.php"; ?>
</body>
</html>
