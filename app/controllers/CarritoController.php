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
        $conexion = require ROOT . 'core/database.php';

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

            require_once ROOT . 'app/models/OrdenPago.php';
            // Generar id unica para la orden
            $idOrden = uniqid('orden_');

            $productosData = [];
            foreach ($data['productos'] as $p) {
                $productosData[] = [
                    "id" => $p['id_producto'],
                    "cantidad" => (int)$p['cantidad_carrito'],
                    "subtotal" => (int)$p['subtotal'],
                    "precio" => (float)$p['precio']
                ];
            }

            // Registro una orden temporal
            $guardarDatos = OrdenPago::crear($conexion, $idOrden, $idUsuario, $productosData, $data['total'], $direccion, $departamento, $localidad, $apartamento, $indicaciones, $nombre, $telefono);

            if (!$guardarDatos) {
                echo json_encode([
                    'success' => false,
                    'msg' => 'Error al proceder con el pago. Intente de nuevo'
                ]);
                return;
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
                "external_reference" => $idOrden
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
        require_once ROOT . 'app/models/OrdenPago.php';
        require_once ROOT . 'app/models/Pago.php';
        require_once ROOT . 'core/Session.php';

        header('Content-Type: application/json');

        if (!isset($_GET['payment_id']) || !isset($_GET['external_reference'])) {
            echo json_encode(["success" => false, "msg" => "Faltan datos del pago."]);
            return;
        }

        $paymentId = $_GET['payment_id'];
        $idOrden = $_GET['external_reference'];

        // Buscar la orden temporal
        $infoOrden = OrdenPago::obtenerPorId($conexion, $idOrden);
        if (!$infoOrden) {
            return;
        }

        // Armamos los datos del pedido (dirección, contacto, etc.)
        $pedidoInfoUserJson = json_encode([
            "direccion" => $infoOrden['direccion'],
            "departamento" => $infoOrden['departamento'],
            "localidad" => $infoOrden['localidad'],
            "apartamento" => $infoOrden['apartamento'],
            "indicaciones" => $infoOrden['indicaciones'],
            "nombre" => $infoOrden['nombre'],
            "telefono" => $infoOrden['telefono']
        ], JSON_UNESCAPED_UNICODE);

        // Guardamos el pago en la tabla `pago`
        $resultado = Pago::guardarPago(
            $conexion,
            $infoOrden['id_usuario'],
            $paymentId,
            $infoOrden['total'],
            $infoOrden['productos'],
            $pedidoInfoUserJson
        );

        if ($resultado) {
            // Limpiar carrito y eliminar orden temporal
            Session::remove('carrito');
            OrdenPago::eliminar($conexion, $idOrden);
            
        } else {
            echo json_encode(["success" => false, "msg" => "Error al guardar el pago."]);
        }

        include ROOT . 'app/views/compra/successPago.php';
    }



    public function failure()
    {
        echo "❌ Pago fallido.";
        header('Location: ' . BASE_URL);
    }

    public function pending()
    {
        echo "⏳ Pago pendiente.";
    }
}
