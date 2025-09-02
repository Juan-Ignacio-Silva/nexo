<?php
class Producto {
    public static function productosInfo($conexion) {
        $sql = "
            SELECT 
                p.id_producto,
                p.nombre,
                p.precio,
                p.categoria,
                p.etiqueta,
                p.descripcion,
                p.imagen,
                ROUND(AVG(r.calificacion)::numeric, 2) AS calificacion_promedio,
                COUNT(r.id_resena) AS total_resenas
            FROM producto p
            LEFT JOIN resena r ON r.id_producto = p.id_producto
            GROUP BY 
                p.id_producto, 
                p.nombre, 
                p.precio, 
                p.categoria, 
                p.etiqueta, 
                p.descripcion, 
                p.imagen
            ORDER BY calificacion_promedio DESC NULLS LAST, total_resenas DESC
            LIMIT 3
        ";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $productos;
    }

    public static function obtenerPorId($conexion, $id) {
        $sql = "
            SELECT 
                p.id_producto, 
                p.nombre, 
                p.precio, 
                p.categoria, 
                p.etiqueta, 
                p.descripcion, 
                p.imagen, 
            COALESCE(ROUND(AVG(r.calificacion), 2), 0) AS promedio
            FROM producto p
            LEFT JOIN resena r ON p.id_producto = r.id_producto
            WHERE p.id_producto = :id
            GROUP BY p.id_producto;
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
