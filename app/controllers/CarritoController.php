<?php
require ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class CarritoController
{
    private static $baseUrl = BASE_URL;
    public function agregar()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(["success" => false, "msg" => "Producto no válido"]);
            return;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        $msg = "Producto agregado";

        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad']++;
            $msg = "Producto ya agregado, cantidad incrementada";
        } else {
            $_SESSION['carrito'][$id] = ["id" => $id, "cantidad" => 1];
        }
        $total_productos = count($_SESSION['carrito']);
        $total_items = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

        echo json_encode([
            "success" => true,
            "total_items" => $total_items,
            "total_productos" => $total_productos,
            "msg" => $msg
        ]);
    }

    public static function infoProductoCarrito()
    {
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Carrito.php';

        $carrito = $_SESSION['carrito'] ?? [];
        $ids = array_keys($carrito);

        if (empty($ids)) {
            return [
                'productos' => [],
                'total' => 0
            ];
        }

        $productos = Carrito::obtenerProductosCarrito($conexion, $ids);

        if (!$productos) {
            return "Error al obtener los productos";
        }

        $total = 0;
        $posicion = 1;

        foreach ($productos as &$producto) {
            $id = $producto['id_producto'];
            $producto['cantidad_carrito'] = $carrito[$id]['cantidad'];
            $producto['subtotal'] = $producto['precio'] * $producto['cantidad_carrito'];
            $total += $producto['subtotal'];
            $producto['posicion'] = $posicion;

            $posicion++;
        }

        return [
            "productos" => $productos,
            "total" => $total
        ];
    }

    public static function eliminarProductoCarrito()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode([
                "success" => false,
                "msg" => "ID de producto no válido"
            ]);
            return;
        }

        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);

            echo json_encode([
                "success" => true,
                "msg" => "Producto eliminado del carrito"
            ]);
            return;
        }

        echo json_encode([
            "success" => false,
            "msg" => "El producto no estaba en el carrito"
        ]);
    }

    // Crear preferencia Mercado Pago
    public static function crearPreferencia()
    {
        try {
            require_once ROOT . 'core/Session.php';
            $idUsuario = Session::get('usuario_id');

            $dotenv = Dotenv::createMutable(__DIR__ . '/../../');
            $dotenv->safeLoad();
            $accessToken = $_ENV['ACCESS_TOKEN_MP'] ?? null;


            if (empty($accessToken)) {
                throw new Exception("Falta configurar el token de Mercado Pago");
            }

            $data = self::infoProductoCarrito();

            if (empty($data['productos']) || $data['total'] == 0) {
                http_response_code(400);
                echo json_encode(["success" => false, "msg" => "Carrito vacío"]);
                return;
            }

            // Construir items
            $items = [];
            foreach ($data['productos'] as $p) {
                $items[] = [
                    "title" => $p['nombre'],
                    "quantity" => (int)$p['cantidad_carrito'],
                    "unit_price" => (float)$p['precio'],
                    "currency_id" => "UYU"
                ];
            }

            // Datos de preferencia
            $preference = [
                "items" => $items,
                "auto_return" => "approved",
                "back_urls" => [
                    "success" => ROOT. "carrito/success",
                    "failure" => ROOT. "/carrito/failure",
                    "pending" => ROOT. "carrito/pending"
                ],
                "binary_mode" => true,
                "statement_descriptor" => "Nexo Store",
                "external_reference" => json_encode([
                    "id_usuario" => $idUsuario, // ID del usuario logueado
                    "productos" => array_column($data['productos'], 'id_producto')
                ])
            ];


            // Request a Mercado Pago
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/checkout/preferences");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer " . $accessToken
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                throw new Exception("Error de conexión: " . $curlError);
            }

            $result = json_decode($response, true);

            if ($httpCode !== 201 && $httpCode !== 200) {
                $errorMsg = $result['message'] ?? ($result['error'] ?? "Error en Mercado Pago");
                throw new Exception($errorMsg);
            }

            if (!isset($result['id']) || !isset($result['init_point'])) {
                throw new Exception("No se obtuvo información completa de la preferencia");
            }

            echo json_encode([
                "success" => true,
                "preferenceId" => $result['id'],
                "init_point" => $result['init_point'], // URL checkout
                "msg" => "Preferencia creada exitosamente"
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "msg" => "Error: " . $e->getMessage()]);
        }
    }

    public function success()
    {
        $conexion = require ROOT . 'core/database.php';

        $data = $_GET;
        $external_ref = json_decode($data['external_reference'], true);

        $idUsuario = $external_ref['id_usuario'];
        $productos = $external_ref['productos'];

        echo $idUsuario;
        echo $productos;
    }

    public function failure()
    {
        echo "❌ Pago fallido.";
        header('Location:' . BASE_URL);
    }

    public function pending()
    {
        echo "⏳ Pago pendiente.";
    }
}
