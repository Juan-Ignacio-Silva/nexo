<?php
class OrdenPago
{
    public static function crear($conexion, $idOrden, $idUsuario, $productos, $total, $direccion, $departamento, $localidad, $apartamento, $indicaciones, $nombre, $telefono)
    {
        // Estructuramos los productos con id + cantidad
        $productosFormateados = array_map(function ($p) {
            return [
                'id' => $p['id_producto'],
                'cantidad' => (int)$p['cantidad_carrito'] ?? 1
            ];
        }, $productos);

        $productosJson = json_encode($productosFormateados, JSON_UNESCAPED_UNICODE);

        $stmt = $conexion->prepare("
        INSERT INTO ordenes_temporales 
        (id_orden, id_usuario, productos, total, direccion, departamento, localidad, apartamento, indicaciones, nombre, telefono)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

        return $stmt->execute([
            $idOrden,
            $idUsuario,
            $productosJson,
            $total,
            $direccion,
            $departamento,
            $localidad,
            $apartamento,
            $indicaciones,
            $nombre,
            $telefono
        ]);
    }


    // Obtener una orden por ID
    public static function obtenerPorId($conexion, $idOrden)
    {
        $stmt = $conexion->prepare("SELECT * FROM ordenes_temporales WHERE id_orden = ?");
        $stmt->execute([$idOrden]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar una orden por id, después del pago exitoso)
    public static function eliminar($conexion, $idOrden)
    {
        $stmt = $conexion->prepare("DELETE FROM ordenes_temporales WHERE id_orden = ?");
        return $stmt->execute([$idOrden]);
    }
}
