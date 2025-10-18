<?php
require ROOT . '/vendor/autoload.php';

use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;

class CarritoController
{
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

    public function pagarConMercadoPago() {
        // Tu Access Token (usa el de TEST en desarrollo)
        MercadoPagoConfig::setAccessToken("APP_USR-4560372115561328-101613-68b61a5349c6c6a363db9625082affdf-2929598777");

        // Crear cliente de preferencia
        $client = new PreferenceClient();

        // Ejemplo de items del carrito (luego los traés de la DB)
        $items = [
            [
                "title" => "Zapatillas Nike",
                "quantity" => 1,
                "unit_price" => 50.00
            ],
            [
                "title" => "Camiseta Adidas",
                "quantity" => 2,
                "unit_price" => 30.00
            ]
        ];

        // Crear la preferencia
        $preference = $client->create([
            "items" => $items,
            "back_urls" => [
                "success" => "https://tusitio.com/checkout/success",
                "failure" => "https://tusitio.com/checkout/failure",
                "pending" => "https://tusitio.com/checkout/pending"
            ],
            "auto_return" => "approved"
        ]);

        // Redirigir al checkout
        header("Location: " . $preference->init_point);
        exit;
    }

    public function success() {
        echo "✅ Pago aprobado.";
    }

    public function failure() {
        echo "❌ Pago fallido.";
    }

    public function pending() {
        echo "⏳ Pago pendiente.";
    }
}
