<?php
require_once ROOT . 'app/models/CategoriaModel.php';

class CategoriaController
{
    // Crear categoría
    public static function crearCategoria()
    {
        require_once ROOT . 'core/Headers.php';
        $conexion = require ROOT . 'core/database.php';

        $data = json_decode(file_get_contents("php://input"), true);
        $nombre = trim($data['nombre'] ?? '');
        $icono = trim($data['icono_url'] ?? '');

        if ($nombre === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'mensaje' => 'El nombre es obligatorio.']);
            return;
        }

        try {
            $id = CategoriaModel::crear($conexion, $nombre, $icono);
            echo json_encode(['status' => 'success', 'id' => $id]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'mensaje' => $e->getMessage()]);
        }
    }

    // Obtener todas las categorías
    public static function obtenerCategorias()
    {
        require_once ROOT . 'core/Headers.php';
        $conexion = require ROOT . 'core/database.php';

        try {
            $categorias = CategoriaModel::obtenerTodas($conexion);
            echo json_encode(['status' => 'success', 'categorias' => $categorias]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'mensaje' => $e->getMessage()]);
        }
    }

    // Eliminar categoría
    public static function eliminarCategoria()
    {
        require_once ROOT . 'core/Headers.php';
        $conexion = require ROOT . 'core/database.php';

        $data = json_decode(file_get_contents("php://input"), true);
        $id = intval($data['id'] ?? 0);

        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'mensaje' => 'ID inválido.']);
            return;
        }

        try {
            $ok = CategoriaModel::eliminar($conexion, $id);
            echo json_encode(['status' => $ok ? 'success' : 'error']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'mensaje' => $e->getMessage()]);
        }
    }
    public static function obtenerTopCategorias()
    {
        require_once ROOT . 'core/Headers.php';
        require_once ROOT . 'core/database.php';

        try {
            $conexion = require ROOT . 'core/database.php';
            $categorias = CategoriaModel::obtenerCategoriasDestacadas($conexion);

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'categorias' => $categorias
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}
