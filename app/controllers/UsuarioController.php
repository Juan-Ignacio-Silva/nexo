<?php
require_once ROOT . 'core/View.php';
class UsuarioController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'core/database.php';
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
            require_once ROOT . 'core/database.php';
            require_once ROOT . 'app/models/Usuario.php';
            require_once ROOT . 'core/Session.php';

            // Obtener datos y validarlos
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($password)) {
                $registrado = Usuario::registrar($conexion, $nombre, $apellido, $email, $password);

                if ($registrado) {
                    // Esto seria para que al registrar el usuario, a su vez se inicie sesion con ese usuario_id.
                    // Session::set('usuario_id', $identificado['id_usuarios']);
                    // Session::set('usuario_nombre', $identificado['nombre']);
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

    public function logout()
    {
        Session::start();
        Session::destroy();
        header('Location: ../home');
        exit;
    }

    public function perfil()
    {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "usuario/perfil", data: [
            "title" => "Nexo - Perfil de " . Auth::usuario(),
            "usuario" => Auth::usuario(),
            "acceso" => Auth::restringirAccesoWeb()
        ]);
    }

    public static function updateInfoUser(){
        require_once ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Usuario.php';
        require_once ROOT . 'core/Session.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $campos = [];
            $parametros = [];
            $id_user = Session::get('usuario_id');

            // Obtener datos y validarlos
            if (!empty($_POST['nombre'])) {
                $campos[] = "nombre = :nombre";
                $parametros[':nombre'] = $_POST['nombre'];
            }

            if (!empty($_POST['apellido'])) {
                $campos[] = "apellido = :apellido";
                $parametros[':apellido'] = $_POST['apellido'];
            }

            if (!empty($_POST['email'])) {
                $campos[] = "email = :email";
                $parametros[':email'] = $_POST['email'];
            }

            if (!empty($_POST['password'])) {
                $campos[] = "password = :password";
                $parametros[':password'] = $_POST['password'];
            }

            if (empty($campos)) {
                header("Location: /usuario/perfil?error=1");
                exit;
            }
            
            $actualizado = Usuario::updateInfoUser($conexion,  $id_user, $campos, $parametros);

            if ($actualizado) {
                header("Location: /usuario/perfil?active=1");
                exit;
            } else {
                header("Location: /usuario/perfil?error=1");
                exit;
            } 
        }

        header("Location: /usuario/perfil");
        exit;
    }

    public static function getUsuariosRegistrados() {
        require_once ROOT . 'app/models/Usuario.php';
        $conexion = require ROOT . 'core/database.php';
        $usuarios = Usuario::todosLosUsuarios($conexion);
        if (!$usuarios) {
            return [];
        } else {
            return $usuarios;
        }
    }

    public static function getRecienRegistrados() {
        require_once ROOT . 'app/models/Usuario.php';
        $conexion = require ROOT . 'core/database.php';
        $usuarios = Usuario::usuariosPorFecha($conexion);
        if (!$usuarios) {
            return [];
        } else {
            return $usuarios;
        }
    }
}
