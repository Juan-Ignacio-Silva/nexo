<?php

class UsuarioController
{
    public function login()
    {
        include ROOT . 'app/views/usuario/login.php';
    }

    public function registro()
    {
        // Si se envió el formulario (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'app/config/database.php';
            require_once ROOT . 'app/models/Usuario.php';

            // Obtener datos y validarlos
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($password)) {
                $registrado = Usuario::registrar($conexion, $nombre, $apellido, $email, $password);

                if ($registrado) {
                    header("Location: ../home");
                    exit;
                } else {
                    $error = "No se pudo registrar el usuario. Inténtalo más tarde.";
                }
            } else {
                $error = "Todos los campos son obligatorios.";
            }
        }

        // Mostrar la vista (si es GET o falló POST)
        include ROOT . 'app/views/usuario/registro.php';
    }
}
