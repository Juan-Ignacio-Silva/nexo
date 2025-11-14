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
        require_once ROOT . "app/models/Vendedor.php";
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

        header('Content-Type: application/json');

        // Si viene desde un form con multipart/form-data
        $nombreProducto = trim($_POST['nombreProducto'] ?? '');
        $precio = trim($_POST['precio'] ?? '');
        $stock = trim($_POST['stock'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $etiquetas = trim($_POST['etiquetas'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $imagen = trim($_POST['imagen'] ?? '');

        $idUsuario = Session::get('usuario_id');
        $idVendedorArray = Vendedor::identificarVendedor($conexion, $idUsuario);
        $idVendedor = $idVendedorArray['id_vendedor'] ?? null;

        // Validar campos obligatorios
        if (
            empty($nombreProducto) || empty($precio) || empty($stock) ||
            empty($categoria) || empty($etiquetas) || empty($descripcion)
        ) {
            echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes.']);
            return;
        }

        // Guardar producto en la base de datos
        $publicado = Producto::registrarProducto(
            $conexion,
            $nombreProducto,
            $precio,
            $stock,
            $categoria,
            $etiquetas,
            $descripcion,
            $imagen,
            $idVendedor
        );

        if ($publicado === true) {
            echo json_encode(['success' => true, 'message' => 'Producto publicado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al publicar el producto.']);
        }
    }

    public static function eliminarProducto()
    {
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'core/Session.php';
        require ROOT . "app/models/Vendedor.php";

        $data = json_decode(file_get_contents("php://input"), true);
        $idProducto = $data['idProducto'] ?? null;

        header('Content-Type: application/json');

        if (!$idProducto) {
            echo json_encode(['success' => false, 'message' => 'ID de producto faltante.']);
            return;
        }

        $idUsuario = Session::get('usuario_id');
        $idVendedorArray = Vendedor::identificarVendedor($conexion, $idUsuario);
        $idVendedor = $idVendedorArray['id_vendedor'] ?? null;

        $eliminado = Vendedor::eliminarProducto($conexion, $idProducto, $idVendedor);

        echo json_encode(['success' => $eliminado]);
    }

    public static function editarProducto()
    {
        $conexion = require ROOT . 'core/database.php';
        require ROOT . "app/models/Vendedor.php";
        require_once ROOT . 'core/Session.php';

        header('Content-Type: application/json');

        $idUsuario = Session::get('usuario_id');
        $idVendedorArray = Vendedor::identificarVendedor($conexion, $idUsuario);
        $idVendedor = $idVendedorArray['id_vendedor'] ?? null;

        $idProducto = $_POST['idProducto'] ?? null;
        $nombre = trim($_POST['nombreProducto'] ?? '');
        $precio = trim($_POST['precio'] ?? '');
        $stock = trim($_POST['stock'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $etiquetas = trim($_POST['etiquetas'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $imagen = trim($_POST['imagen'] ?? '');

        if (!$idProducto) {
            echo json_encode(['success' => false, 'message' => 'ID faltante.']);
            return;
        }

        $editado = Vendedor::editarProducto(
            $conexion,
            $idProducto,
            $idVendedor,
            $nombre,
            $precio,
            $stock,
            $categoria,
            $etiquetas,
            $descripcion,
            $imagen
        );

        echo json_encode(['success' => $editado]);
    }



    public static function obtnerProductosVendidos()
    {
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . "app/models/Vendedor.php";
        require_once ROOT . 'core/Session.php';

        $idUsuario = Session::get('usuario_id');

        $idVendedorArray = Vendedor::identificarVendedor($conexion, $idUsuario);
        $idVendedor = $idVendedorArray['id_vendedor'] ?? null;

        $totalVendido = Vendedor::obtenerCantidadVendidaPorVendedor($conexion, $idVendedor);

        return $totalVendido;
    }

    public static function obtenerTotalRecaudado()
    {
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . "app/models/Vendedor.php";
        require_once ROOT . 'core/Session.php';

        $idUsuario = Session::get('usuario_id');

        $idVendedorArray = Vendedor::identificarVendedor($conexion, $idUsuario);
        $idVendedor = $idVendedorArray['id_vendedor'] ?? null;

        $totalRecaudado = Vendedor::obtenerTotalRecaudadoPorVendedor($conexion, $idVendedor);

        return $totalRecaudado;
    }
}
