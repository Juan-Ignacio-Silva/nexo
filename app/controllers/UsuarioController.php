<?php
class UsuarioController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'app/config/database.php';
            require_once ROOT . 'app/models/Usuario.php';
            require_once ROOT . 'core/Session.php';

            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($email) && !empty($password)) {
                $identificado = Usuario::login($conexion, $email, $password);
                
                if ($identificado) {
                    Session::set('usuario_id', $identificado['id_usuarios']);
                    Session::set('usuario_nombre', $identificado['nombre']);
                    header("Location: ../home");
                    exit;
                } else {
                    $error = "Email o contraseña incorrecto. Intente de nuevo.";
                }
            } else {
                $error = "Todos los campos son obligatorios.";
            }
        }

        include ROOT . 'app/views/usuario/login.php';
    
    }

    public function registro()
    {
        // Si se envió el formulario (POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'app/config/database.php';
            require_once ROOT . 'app/models/Usuario.php';

            // Obtener datos y validarlos
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($password)) {
                $registrado = Usuario::registrar($conexion, $nombre, $apellido, $email, $password);

                if ($registrado) {
                    header("Location: ../home");
                    exit;
                } else {
                    $error = "Email ya registrado, intente de nuevo.";
                }
            } else {
                $error = "Todos los campos son obligatorios.";
            }
        }

        include ROOT . 'app/views/usuario/registro.php';
    }

    public function logout() {
        Session::start();
        Session::destroy();
        header('Location: /usuario/login');
        exit;
    }
}
