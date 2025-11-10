<?php
class Carrito
{
    public static function obtenerProductosCarrito($conexion, $ids)
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
        SELECT 
            p.id_producto, 
            p.nombre, 
            p.precio, 
            p.imagen, 
            p.cantidad, 
            p.descripcion, 
            c.nombre AS categoria, 
            c.icono_url AS categoria_icono
        FROM producto p
        LEFT JOIN categorias c ON c.id = p.id_categoria
        WHERE p.id_producto IN ($placeholders)
    ";

        $stmt = $conexion->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
