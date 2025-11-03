<?php
class Carrito
{
    public static function obtenerProductosCarrito($conexion, $ids)
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
            SELECT id_producto, nombre, precio, imagen, cantidad, descripcion, categoria
            FROM producto 
            WHERE id_producto IN ($placeholders)
        ";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute($ids);

        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $productos;
    }

    
}
