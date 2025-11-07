<?php
require ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class CarritoController
{
    public function agregar()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['idProducto'] ?? null;
        $cantidad = $input['cantidad'] ?? 1;

        if (!$id || $cantidad <= 0) {
            echo json_encode(["success" => false, "msg" => "Datos inválidos"]);
            return;
        }

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }

        if (isset($_SESSION['carrito'][$id])) {
            echo json_encode([
                "success" => false,
                "msg" => "Producto ya está agregado al carrito"
            ]);
            return;
        }

        $_SESSION['carrito'][$id] = [
            "id" => $id,
            "cantidad" => (int)$cantidad
        ];

        $total_productos = count($_SESSION['carrito']);
        $total_items = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

        echo json_encode([
            "success" => true,
            "total_items" => $total_items,
            "total_productos" => $total_productos,
            "msg" => "Producto agregado."
        ]);
    }

    public function actualizarCantidad()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['idProducto'] ?? null;
        $cantidad = (int)($input['cantidad'] ?? 0);

        if (!$id || $cantidad < 0) {
            echo json_encode(["success" => false, "msg" => "Datos inválidos"]);
            return;
        }

        if (!isset($_SESSION['carrito'][$id])) {
            echo json_encode(["success" => false, "msg" => "El producto no está en el carrito"]);
            return;
        }

        if ($cantidad === 0) {
            // Si la cantidad es 0, eliminar el producto
            unset($_SESSION['carrito'][$id]);
            $msg = "Producto eliminado del carrito";
        } else {
            // Actualizar la cantidad
            $_SESSION['carrito'][$id]['cantidad'] = $cantidad;
            $msg = "Cantidad actualizada";
        }

        $total_productos = count($_SESSION['carrito']);
        $total_items = array_sum(array_column($_SESSION['carrito'], 'cantidad'));

        echo json_encode([
            "success" => true,
            "msg" => $msg,
            "total_items" => $total_items,
            "total_productos" => $total_productos
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
        require_once ROOT . 'core/Session.php';

        $dataEnvio = json_decode(file_get_contents("php://input"), true);

        $direccion = trim($dataEnvio['direccion'] ?? '');
        $departamento = trim($dataEnvio['departamento'] ?? '');
        $localidad = trim($dataEnvio['localidad'] ?? '');
        $apartamento = trim($dataEnvio['apartamento'] ?? '');
        $indicaciones = trim($dataEnvio['indicaciones'] ?? '');
        $nombre = trim($dataEnvio['nombre'] ?? '');
        $telefono = trim($dataEnvio['telefono'] ?? '');

        header('Content-Type: application/json');

        if (empty($direccion) || empty($departamento) || empty($localidad) || empty($nombre) || empty($telefono)) {
            echo json_encode([
                'success' => false,
                'msg' => 'Campos obligatorios faltantes.'
            ]);
            return;
        }

        $idUsuario = Session::get('usuario_id');

        if (empty($idUsuario)) {
            echo json_encode([
                'success' => false,
                'msg' => 'No tienes sesion iniciada.'
            ]);
            return;
        }

        try {

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
                    "success" => BASE_URL . "carrito/success",
                    "failure" => BASE_URL . "carrito/failure",
                    "pending" => BASE_URL . "carrito/pending"
                ],
                "binary_mode" => true,
                "statement_descriptor" => "Nexo Store",
                "payer" => [
                    "email" => "test_user_7525121157191801824@testuser.com"
                ],
                "external_reference" => json_encode([
                    "id_usuario" => $idUsuario, // ID del usuario logueado
                    "productos" => array_column($data['productos'], 'id_producto'),
                    "productoName" => array_column($data['productos'], 'nombre'),
                    "precioProducto" => array_column($data['productos'], 'precio'),
                    "subtotal" => array_column($data['productos'], 'subtotal'),
                    "cantidad" => array_column($data['productos'], 'cantidad_carrito'),
                    "total" => $data['total'],
                    "direccion" => $direccion,
                    "departamento" => $departamento,
                    "localidad" => $localidad,
                    "apartamento" => $apartamento,
                    "indicaciones" => $indicaciones,
                    "nombre" => $nombre,
                    "telefono" => $telefono
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
        require ROOT . 'core/Session.php';

        if (!isset($_GET['external_reference'])) {
            echo json_encode(["success" => false, "msg" => "No se recibieron datos de la compra."]);
            return;
        }

        $external_ref = json_decode($_GET['external_reference'], true);

        if (!$external_ref) {
            echo json_encode(["success" => false, "msg" => "Error al decodificar la información recibida."]);
            return;
        }

        Session::remove('carrito');

        // Extraer los datos
        $idUsuario = $external_ref['id_usuario'] ?? 'Desconocido';
        $productos = $external_ref['productos'] ?? [];
        $productoName = $external_ref['productoName'] ?? [];
        $precios = $external_ref['precioProducto'] ?? [];
        $cantidades = $external_ref['cantidad'] ?? [];
        $subtotales = $external_ref['subtotal'] ?? [];
        $total = $external_ref['total'] ?? 0;

        $direccion = $external_ref['direccion'] ?? '';
        $departamento = $external_ref['departamento'] ?? '';
        $localidad = $external_ref['localidad'] ?? '';
        $apartamento = $external_ref['apartamento'] ?? '';
        $indicaciones = $external_ref['indicaciones'] ?? '';
        $nombre = $external_ref['nombre'] ?? '';
        $telefono = $external_ref['telefono'] ?? '';


        // Detectar si el request viene de un fetch (AJAX)
        $isFetch = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

        if ($isFetch) {
            header('Content-Type: application/json');
            echo json_encode([
                "success" => true,
                "data" => [
                    "id_usuario"    => $idUsuario,
                    "productos"     => $productos,
                    "productoName"  => $productoName,
                    "precios"       => $precios,
                    "cantidades"    => $cantidades,
                    "subtotales"    => $subtotales,
                    "total"         => $total,
                    "direccion"     => $direccion,
                    "departamento"  => $departamento,
                    "localidad"     => $localidad,
                    "apartamento"   => $apartamento,
                    "indicaciones"  => $indicaciones,
                    "nombre"        => $nombre,
                    "telefono"      => $telefono
                ]
            ]);
            return;
        }

        // Si es una visita normal (no fetch), carga la vista
        include ROOT . 'app/views/compra/successPago.php';
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
