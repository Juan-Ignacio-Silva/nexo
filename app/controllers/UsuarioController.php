<?php
require_once ROOT . 'core/View.php';
require_once ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Google\Client as Google_Client;
use Google\Service\Oauth2 as Google_Service_Oauth2;

class UsuarioController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'core/database.php';
            require_once ROOT . 'core/Mailer.php';
            require_once ROOT . 'app/models/Usuario.php';
            require_once ROOT . 'core/Session.php';

            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (!empty($email) && !empty($password)) {
                $identificado = Usuario::login($conexion, $email, $password);

                if ($identificado) {

                    // Generar código aleatorio
                    $codigo = rand(100000, 999999);

                    // Guardar código en BD
                    Usuario::guardarCodigo($conexion, $identificado['id_usuarios'], $codigo);

                    // Enviar correo
                    Mailer::enviarCorreo(
                        $email,
                        'Código de verificación',
                        "Tu código de verificación es: <b>$codigo</b>"
                    );

                    // Guardar ID temporal de usuario
                    Session::set('verificacion_usuario', $identificado['id_usuarios']);

                    // Redirigir a verificación
                    header("Location:" . BASE_URL . "usuario/verificarCodigo");
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'core/database.php';
            require_once ROOT . 'core/Mailer.php';
            require_once ROOT . 'app/models/Usuario.php';
            require_once ROOT . 'core/Session.php';

            // Obtener datos y validarlos
            $nombre = trim($_POST['nombre'] ?? '');
            $apellido = trim($_POST['apellido'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmar = trim($_POST['confirmar_password'] ?? '');

            if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($password) && !empty($confirmar)) {

                // Validar que las contraseñas coincidan
                if ($password !== $confirmar) {
                    $error = "Las contraseñas no coinciden.";
                }
                // Validar largo mínimo
                elseif (strlen($password) < 8) {
                    $error = "La contraseña debe tener al menos 8 caracteres.";
                } else {
                    // Intentar registrar al usuario
                    $idUser = Usuario::registrar($conexion, $nombre, $apellido, $email, $password);

                    if ($idUser) {
                        // Generar código de verificación
                        $codigo = rand(100000, 999999);
                        Usuario::guardarCodigo($conexion, $idUser, $codigo);

                        // Enviar correo de verificación
                        $mensaje = "
                        <h2>Verificación de cuenta</h2>
                        <p>Hola $nombre, tu código de verificación es:</p>
                        <h3>$codigo</h3>
                        <p>Ingresa este código en la página para activar tu cuenta.</p>
                    ";

                        Mailer::enviarCorreo($email, "Código de verificación", $mensaje);

                        Session::set('verificacion_usuario', $idUser);

                        header("Location:" . BASE_URL . "usuario/verificarCodigoR");
                        exit;
                    } else {
                        // Si el método registrar devuelve false o null
                        $error = "El email ya está registrado, intente con otro.";
                    }
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

    public static function updateInfoUser()
    {
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

    public static function getUsuariosRegistrados()
    {
        require_once ROOT . 'app/models/Usuario.php';
        $conexion = require ROOT . 'core/database.php';
        $usuarios = Usuario::todosLosUsuarios($conexion);
        if (!$usuarios) {
            return [];
        } else {
            return $usuarios;
        }
    }

    public static function getRecienRegistrados()
    {
        require_once ROOT . 'app/models/Usuario.php';
        $conexion = require ROOT . 'core/database.php';
        $usuarios = Usuario::usuariosPorFecha($conexion);
        if (!$usuarios) {
            return [];
        } else {
            return $usuarios;
        }
    }

    // Login mediante GOOGLE !!!!!!!!! -_-
    public static function loginGoogle()
    {
        $dotenv = Dotenv::createMutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));
        $client->addScope('email');
        $client->addScope('profile');

        // Generar URL de autenticación
        $authUrl = $client->createAuthUrl();

        // Redirigir al usuario a Google
        header('Location: ' . $authUrl);
        exit;
    }

    public static function loginGoogleAuthorized()
    {
        require ROOT . 'app/models/Usuario.php';
        $conexion = require ROOT . 'core/database.php';

        $dotenv = Dotenv::createMutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));
        $client->addScope('email');
        $client->addScope('profile');

        if (isset($_GET['code'])) {
            $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if (isset($token['error'])) {
                die('Error en auth de Google');
            }

            $client->setAccessToken($token);

            $oAuth = new Google_Service_Oauth2($client);
            $userInfo = $oAuth->userinfo->get();

            $usuario = Usuario::registroWithGoogle($conexion, $userInfo);

            if ($usuario) {
                Session::set('usuario_id', $usuario['id_usuarios']);
                Session::set('usuario_nombre', $usuario['nombre']);
                header("Location: ../home");
                exit;
            } else {
                $verificado = Usuario::loginWithGoogle($conexion, $userInfo);

                if ($verificado) {
                    Session::set('usuario_id', $verificado['id_usuarios']);
                    Session::set('usuario_nombre', $verificado['nombre']);
                    header("Location: ../home");
                    exit;
                }
            }

            return false;
        }

        header("Location: ../login");
    }

    public function verificarCodigo()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT . 'core/database.php';
            require_once ROOT . 'app/models/Usuario.php';
            require_once ROOT . 'core/Session.php';

            $codigo = trim($_POST['codigo'] ?? '');
            $usuarioId = Session::get('verificacion_usuario');

            if (!empty($codigo) && $usuarioId) {
                $valido = Usuario::verificarCodigo($conexion, $usuarioId, $codigo);

                if ($valido) {
                    $usuario = Usuario::obtenerPorId($conexion, $usuarioId);

                    Session::set('usuario_id', $usuario['id_usuarios']);
                    Session::set('usuario_nombre', $usuario['nombre']);
                    Session::remove('verificacion_usuario');

                    header("Location: ../home");
                    exit;
                } else {
                    $error = "Código incorrecto o expirado.";
                }
            } else {
                $error = "Debe ingresar un código válido.";
            }
        }

        include ROOT . 'app/views/usuario/verificarCodigo.php';
    }

    public function verificarCodigoR()
    {
        require_once ROOT . 'core/Session.php';
        require_once ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Usuario.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo = trim($_POST['codigo'] ?? '');
            $usuarioId = Session::get('verificacion_usuario');

            if (!empty($codigo) && $usuarioId) {
                $valido = Usuario::verificarCodigo($conexion, $usuarioId, $codigo);

                if ($valido) {
                    $usuario = Usuario::obtenerPorId($conexion, $usuarioId);

                    Session::set('usuario_id', $usuario['id_usuarios']);
                    Session::set('usuario_nombre', $usuario['nombre']);
                    Session::remove('verificacion_usuario');

                    header("Location: ../home");
                    exit;
                } else {
                    $error = "Código incorrecto o expirado.";
                }
            } else {
                $error = "Debe ingresar un código válido.";
            }
        }

        include ROOT . 'app/views/usuario/verificarCodigo.php';
    }
}
