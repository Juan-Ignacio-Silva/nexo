<?php
require_once ROOT . 'core/View.php';
class ProductosController
{

    public function Producto($id)
    {

        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';
        require_once ROOT . 'core/Auth.php';

        if (!empty($id)) {
            $producto = Producto::obtenerPorId($conexion, $id);
            $resenas = Producto::resenasProducto($conexion, $id);
            if (!$producto) {
                http_response_code(404);
                include ROOT . 'app/views/templates/404.php';
                exit;
            }
        } else {
            http_response_code(404);
            include ROOT . 'app/views/templates/404.php';
            exit;
        }

        include ROOT . 'app/views/templates/header.php';
        include ROOT . 'app/views/producto/seccion-producto.php';
        include ROOT . 'app/views/templates/footer.php';
    }

    public function carrito()
    {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "producto/carrito", data: [
            "title" => "Nexo - Carrito",
            "usuario" => Auth::usuario()
        ]);
    }

    public function catalogo()
    {
        require_once ROOT . 'core/Auth.php';
        View::render(view: "producto/catalogo-productos", data: [
            "title" => "Nexo - Catalogo",
            "usuario" => Auth::usuario()
        ]);
    }

    public function categoria($idCategoria)
    {
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';
        require_once ROOT . 'app/models/CategoriaModel.php';
        require_once ROOT . 'core/Auth.php';

        // Validar ID
        if (!is_numeric($idCategoria)) {
            die("Categoría inválida");
        }

        // Obtener los datos
        $productos = Producto::obtenerPorCategoria($conexion, $idCategoria);
        $categoria = CategoriaModel::obtenerPorId($conexion, $idCategoria);

        $nombreCat = $categoria['nombre'];

        // Si no hay productos
        if (empty($productos)) {
            $mensaje = "No hay productos disponibles en esta categoría.";
        }

        // Cargar la vista
        View::render(view: "producto/categoria", data: [
            "title" => "Nexo - $nombreCat",
            "usuario" => Auth::usuario(),
            "productos" => $productos
        ]);
    }


    public static function getProductosInfo()
    {

        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'app/models/Producto.php';

        $productos = Producto::productosInfo($conexion);

        if (!$productos) {
            return "Error! No se encontraron los productos.";
        } else {
            return $productos;
        }
    }

    public static function getProductosSoloInfo()
    {
        require_once ROOT . 'app/models/Producto.php';
        $conexion = require ROOT . 'core/database.php';
        $productos = Producto::obtenerTodosProductos($conexion);
        if (!$productos) {
            return [];
        } else {
            return $productos;
        }
    }

    public static function buscar()
    {
        require ROOT . 'app/models/Producto.php';
        $conexion = require ROOT . 'core/database.php';

        $busqueda = $_GET['busqueda'] ?? '';
        $busqueda = trim($busqueda);

        if ($busqueda === '') {
            echo json_encode([]);
            return;
        }

        $productos = Producto::buscarProductos($conexion, $busqueda);

        $_SESSION['ids_busqueda'] = array_map(fn($p) => $p['id_producto'], $productos);
    }

    public static function obtenerProductosDeBusqueda()
    {
        require ROOT . 'app/models/Producto.php';
        $conexion = require ROOT . 'core/database.php';

        if (!isset($_SESSION['ids_busqueda']) || empty($_SESSION['ids_busqueda'])) {
            return [];
        }

        $ids = $_SESSION['ids_busqueda'];
        // Llamamos al modelo para traer los productos por ID
        return Producto::buscarPorIds($ids, $conexion);
    }

    public static function getProductosIdVendedor()
    {
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'core/Session.php';
        require_once ROOT . 'app/models/Vendedor.php';
        require_once ROOT . 'app/models/Producto.php';
        $idUsuario = Session::get('usuario_id');
        // Identificar al vendedor
        $identificado = Vendedor::identificarVendedor($conexion, $idUsuario);
        if ($identificado) {
            // Traemos los productos que coincidan con el id del vendedor
            $productos = Producto::productosIdVendedor($conexion, $identificado['id_vendedor']);
            if ($productos) {
                return $productos;
            } else {
                return [];
            }
        } else {
            return $identificado;
        }
    }

    public static function recibirRsena()
    {
        $conexion = require ROOT . 'core/database.php';
        require ROOT . "app/models/Producto.php";
        require_once ROOT . 'core/Session.php';

        $data = json_decode(file_get_contents("php://input"), true);
        $estrellas = trim($data['estrellas'] ?? '');
        $comentario = trim($data['comentario'] ?? '');
        $idProducto = trim($data['idProducto'] ?? '');

        header('Content-Type: application/json');

        $idUsuario = Session::get('usuario_id');

        if (!$idUsuario) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión.']);
            return;
        }

        if (empty($estrellas) || empty($comentario) || empty($idProducto)) {
            echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes.']);
            return;
        }

        $publicada = Producto::registrarResena($conexion, $estrellas, $comentario, $idProducto, $idUsuario);

        if ($publicada === true) {
            echo json_encode(['success' => true, 'message' => 'Publicada con exito.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al publicar.']);
        }
    }

    public function subir()
    {
        require ROOT . 'models/Producto.php';
        require_once ROOT . 'core/Supabase.php';

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = $_FILES['imagen']['name'];
            $tmpPath = $_FILES['imagen']['tmp_name'];

            // Instanciar el servicio que maneja la subida a Supabase
            $storage = new SupabaseStorage();
            $pathEnBucket = 'productos/' . uniqid() . '-' . $nombreArchivo;

            $urlPublica = $storage->subirArchivo($pathEnBucket, $tmpPath);

            if ($urlPublica) {
                // Guardás la URL en la BD
                Producto::guardarImagen($conexion, $urlPublica);
                echo "Imagen subida correctamente: " . $urlPublica;
            } else {
                echo "Error al subir la imagen a Supabase.";
            }
        } else {
            echo "No se seleccionó ningún archivo o hubo un error.";
        }
    }
}
