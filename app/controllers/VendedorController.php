<?php
require_once ROOT . 'core/Auth.php';
require_once 'ProductosController.php';
require_once ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
class VendedorController
{
    public function dashboard()
    {
        Auth::restringirDashboard(rolPermitido: 'vendedor');
        include ROOT . 'app/views/vendedor/dashboard.php';
    }

    public static function productosDeVendedor()
    {
        require_once ROOT . 'app/models/Producto.php';
        $conexion = require ROOT . 'core/database.php';



        $productos = Producto::productosIdVendedor($conexion, $id);
        if (!$productos) {
            return [];
        } else {
            return $productos;
        }
    }

    public function conectarMercadoPago()
    {
        $dotenv = Dotenv::createMutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $client_id = $_ENV['CLIENT_ID_MP']; // el que figura en tu app de Mercado Pago
        $redirect_uri = $_ENV['REDIRECT_URI_MP'];

        $auth_url = "https://auth.mercadopago.com/authorization?client_id={$client_id}&response_type=code&platform_id=mp&redirect_uri={$redirect_uri}";

        // Redirige al login/autorización de Mercado Pago
        header("Location: $auth_url");
        exit;
    }


    public function mercadoPagoCallback()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        if (!isset($_GET['code'])) {
            echo "Error: falta el código de autorización.";
            return;
        }

        $code = $_GET['code'];

        $client_id = getenv('PUBLIC_KEY_MP');
        $client_secret = getenv('ACCESS_TOKEN_MP');
        $redirect_uri = getenv('REDIRECT_URI_MP');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.mercadopago.com/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                "grant_type" => "authorization_code",
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "code" => $code,
                "redirect_uri" => $redirect_uri
            ]),
            CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"]
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        if (isset($data['access_token'])) {

            $access_token = $data['access_token'];
            $user_id = $data['user_id'];

            $db = require ROOT . 'core/database.php';
            $stmt = $db->prepare("UPDATE vendedor SET mp_access_token = ?, mp_user_id = ? WHERE usuario_id = ?");
            $stmt->execute([$access_token, $user_id, $_SESSION['usuario_id']]);

            echo "✅ Cuenta de Mercado Pago conectada correctamente.";
        } else {
            echo "❌ Error al conectar con Mercado Pago.";
            var_dump($data);
        }
    }
}
