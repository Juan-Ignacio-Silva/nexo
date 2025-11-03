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

    public static function registroVendedor()
    {
        $conexion = require ROOT . 'core/database.php';
        require ROOT . "app/models/Vendedor.php";
        require_once ROOT . 'core/Session.php';

        $input = json_decode(file_get_contents("php://input"), true);
        $nombreTienda = trim($input['nombreTienda'] ?? '');
        $rutEmpresa = trim($input['rutEmpresa'] ?? 'Sin RUT');
        $descripcion = trim($input['descripcion'] ?? '');
        $idUsuario = Session::get('usuario_id');

        header('Content-Type: application/json');

        if (empty($nombreTienda) || empty($descripcion)) {
            echo json_encode([
                'success' => false,
                'message' => 'Campos obligatorios faltantes.'
            ]);
            return;
        }

        $resultado = Vendedor::registroVendedor($conexion, $nombreTienda, $rutEmpresa, $descripcion, $idUsuario);

        if ($resultado === true) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $resultado]);
        }
    }

    public static function publicarProducto()
    {
        $conexion = require ROOT . 'core/database.php';
        require ROOT . "app/models/Producto.php";
        require ROOT . "app/models/Vendedor.php";
        require_once ROOT . 'core/Session.php';

        $data = json_decode(file_get_contents("php://input"), true);
        $nombreProducto = trim($data['nombreProducto'] ?? '');  
        $precio = trim($data['precio'] ?? '');
        $stock = trim($data['stock'] ?? '');
        $categoria = trim($data['categoria'] ?? '');
        $etiquetas = trim($data['etiquetas'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $imagen = trim($data['imagen'] ?? '');

        header('Content-Type: application/json');

        $idUsuario = Session::get('usuario_id');

        $idVendedorArray = Vendedor::identificarVendedor($conexion, $idUsuario);
        $idVendedor = $idVendedorArray['id_vendedor'] ?? null;
        
        if (empty($nombreProducto) || empty($precio) || empty($stock) || empty($categoria) || empty($etiquetas) || empty($descripcion) || empty($imagen)) {
            echo json_encode([
                'success' => false,
                'message' => 'Campos obligatorios faltantes.'
            ]);
            return;
        }

        $publicado = Producto::registrarProducto($conexion, $nombreProducto, $precio, $stock, $categoria, $etiquetas, $descripcion, $imagen, $idVendedor);

        if ($publicado === true) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al publicar el producto.']);
        }
    }
}
