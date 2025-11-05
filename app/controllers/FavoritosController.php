<?php
class FavoritosController {

    public static function toggleFavorito() {

        $data = json_decode(file_get_contents("php://input"), true);
        $idProducto = $data['idProducto'] ?? null;

        if (!$idProducto) {
            echo json_encode(['success' => false, 'message' => 'Producto desconocido']);
            exit;
        }

        require_once ROOT . 'core/Session.php';
        if (!Session::has('usuario_id')) {
            echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión.']);
            exit;
        }

        $idUsuario = Session::get('usuario_id');

        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'app/models/FavoritoModel.php';

        if (FavoritoModel::esFavorito($conexion, $idUsuario, $idProducto)) {
            FavoritoModel::eliminarFavorito($conexion, $idUsuario, $idProducto);
            echo json_encode(['message' => 'Removido de favorito', 'status' => 'removed']);
        } else {
            FavoritoModel::agregarFavorito($conexion, $idUsuario, $idProducto);
            echo json_encode(['message' => 'Agregado a favorito', 'status' => 'added']);
        }
    }

    public static function esFavorito() {
        $data = json_decode(file_get_contents("php://input"), true);
        $idProducto = $data['idProducto'] ?? null;

        if (!$idProducto) {
            return;
        }

        require_once ROOT . 'core/Session.php';
        $conexion = require ROOT . 'core/database.php';
        require_once ROOT . 'app/models/FavoritoModel.php';

        if (!Session::has('usuario_id')) {
            return;
        }

        $idUsuario = Session::get('usuario_id');

        $favorito = FavoritoModel::esFavorito($conexion, $idUsuario, $idProducto);

        if ($favorito) {
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false]);
            exit;
        }
    }
}
