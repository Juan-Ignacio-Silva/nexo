<?php
class FavoritoModel {

    public static function agregarFavorito($conexion, $idUsuario, $idProducto) {
        $query = "INSERT INTO favoritos (id_usuarios, id_producto)
                  VALUES (:id_usuarios, :id_producto)";
        $stmt = $conexion->prepare($query);
        $stmt->execute([
            ':id_usuarios' => $idUsuario,
            ':id_producto' => $idProducto
        ]);
    }

    public static function eliminarFavorito($conexion, $idUsuario, $idProducto) {
        $query = "DELETE FROM favoritos WHERE id_usuarios = :id_usuarios AND id_producto = :id_producto";
        $stmt = $conexion->prepare($query);
        $stmt->execute([
            ':id_usuarios' => $idUsuario,
            ':id_producto' => $idProducto
        ]);
    }

    public static function obtenerFavoritosPorUsuario($conexion, $idUsuario) {
        $query = "SELECT p.* FROM producto p
                  JOIN favoritos f ON f.id_producto = p.id_producto
                  WHERE f.id_usuarios = :id_usuarios";
        $stmt = $conexion->prepare($query);
        $stmt->execute([':id_usuarios' => $idUsuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function esFavorito($conexion, $idUsuario, $idProducto) {
        $query = "SELECT 1 FROM favoritos WHERE id_usuarios = :id_usuarios AND id_producto = :id_producto";
        $stmt = $conexion->prepare($query);
        $stmt->execute([
            ':id_usuarios' => $idUsuario,
            ':id_producto' => $idProducto
        ]);
        return $stmt->fetch() ? true : false;
    }
}
