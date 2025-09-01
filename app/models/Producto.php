<?php
class Producto {
    public static function productosDestacados($conexion) {
        $stmt = $conexion->prepare("
            SELECT 
                p.id_producto,
                p.nombre,
                p.precio,
                p.categoria,
                p.etiqueta,
                p.descripcion,
                p.imagen,
                ROUND(AVG(r.calificacion), 2) AS calificacion_promedio,
                COUNT(r.id_resena) AS total_resenas
            FROM producto p
            LEFT JOIN reseña r ON r.id_producto = p.id_producto
            GROUP BY 
                p.id_producto, 
                p.nombre, 
                p.precio, 
                p.categoria, 
                p.etiqueta, 
                p.descripcion, 
                p.imagen
            ORDER BY calificacion_promedio DESC, total_resenas DESC
            LIMIT 3
        ");

        $stmt->execute();
        $resultado = $stmt->get_result();

        $productos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $productos[] = $fila;
        }

        return $productos;
    }
}
