<?php
require_once __DIR__ . '/../models/Carrito.php';


class CarritoController
{
    private static $accessToken = 'APP_USR-7784038279915965-101419-91a31efa40387bc701c4c68c268cae06-2919258437'; // 🔒 ACCESS TOKEN MercadoPago
    private static $baseUrl = ''; // 🌍 URL pública (tu ngrok o dominio)

    // Mostrar el carrito (JSON)
    public static function index() {
        $data = self::infoProductoCarrito();
        echo json_encode($data);

    }
    // Agregar un producto
    public function agregar() {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;
        if (!$id){ 
            echo json_encode(["success"=>false,"msg"=>"Producto no válido"]); 
            return; 
        }

        if(!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

        if(isset($_SESSION['carrito'][$id])){
            $_SESSION['carrito'][$id]['cantidad']++;
            $msg = "Cantidad incrementada";
        } else {
            $_SESSION['carrito'][$id] = ["id"=>$id,"cantidad"=>1];
            $msg = "Producto agregado";
        }

        $total_items = array_sum(array_column($_SESSION['carrito'],'cantidad'));
        $total_productos = count($_SESSION['carrito']);

        echo json_encode([
            "success"=>true,
            "total_items"=>$total_items,
            "total_productos"=>$total_productos,
            "msg"=>$msg
        ]);
    }

    // Obtener información detallada del carrito
    public static function infoProductoCarrito() {
        $conexion = require __DIR__ . '/../../core/database.php';
        $carrito = $_SESSION['carrito'] ?? [];
        $ids = array_keys($carrito);
        if(empty($ids)) return ["productos"=>[],"total"=>0];

        $productos = Carrito::obtenerProductosCarrito($conexion, $ids);
        $total = 0; 
        $pos = 1;

        foreach($productos as &$p){
            $id = $p['id_producto'];
            $p['cantidad_carrito'] = $carrito[$id]['cantidad'];
            $p['subtotal'] = $p['precio'] * $p['cantidad_carrito'];
            $p['posicion'] = $pos++;
            $total += $p['subtotal'];
        }

        return ["productos"=>$productos,"total"=>$total];
    }

    // Eliminar producto del carrito
    public static function eliminarProductoCarrito() {
        $input = json_decode(file_get_contents("php://input"), true);
        $id = $input['id'] ?? null;
        if(!$id){ 
            echo json_encode(["success"=>false,"msg"=>"ID no válido"]); 
            return; 
        }

        if(isset($_SESSION['carrito'][$id])){
            unset($_SESSION['carrito'][$id]);
            echo json_encode(["success"=>true,"msg"=>"Producto eliminado"]);
            return;
        }
        echo json_encode(["success"=>false,"msg"=>"Producto no estaba en el carrito"]);
    }

    // Crear preferencia Mercado Pago
    public static function crearPreferencia() {
        try {
            $data = self::infoProductoCarrito();

            if(empty($data['productos']) || $data['total']==0){
                http_response_code(400);
                echo json_encode(["success"=>false,"msg"=>"Carrito vacío"]);
                return;
            }

            // Construir items
            $items = [];
            foreach($data['productos'] as $p){
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
                "back_urls" => [
                    "success" => self::$baseUrl . "/carrito/success",
                    "failure" => self::$baseUrl . "/carrito/failure",
                    "pending" => self::$baseUrl . "/carrito/pending"
                ],
                "auto_return" => "approved",
                "statement_descriptor" => "Nexo Store"
            ];

            // Request a Mercado Pago
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/checkout/preferences");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer " . self::$accessToken
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if($curlError){
                throw new Exception("Error de conexión: " . $curlError);
            }

            $result = json_decode($response, true);

            if($httpCode !== 201 && $httpCode !== 200){
                $errorMsg = $result['message'] ?? ($result['error'] ?? "Error en Mercado Pago");
                throw new Exception($errorMsg);
            }

            if(!isset($result['id']) || !isset($result['init_point'])) {
                throw new Exception("No se obtuvo información completa de la preferencia");
            }

            echo json_encode([
                "success" => true,
                "preferenceId" => $result['id'],
                "init_point" => $result['init_point'], // 👈 URL checkout
                "msg" => "Preferencia creada exitosamente"
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success"=>false,"msg"=>"Error: " . $e->getMessage()]);
        }
}
}
    