<?php
class OrdenPago
{
    public static function crear($conexion, $idOrden, $idUsuario, $productos, $total, $direccion, $departamento, $localidad, $apartamento, $indicaciones, $nombre, $telefono)
    {
        $stmt = $conexion->prepare("
            INSERT INTO ordenes_temporales 
            (id, id_usuario, productos, total, direccion, departamento, localidad, apartamento, indicaciones, nombre, telefono)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $idOrden,
            $idUsuario,
            json_encode($productos, JSON_UNESCAPED_UNICODE),
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
        $stmt = $conexion->prepare("SELECT * FROM ordenes_temporales WHERE id = ?");
        $stmt->execute([$idOrden]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Eliminar una orden por id, después del pago exitoso)
    public static function eliminar($conexion, $idOrden)
    {
        $stmt = $conexion->prepare("DELETE FROM ordenes_temporales WHERE id = ?");
        return $stmt->execute([$idOrden]);
    }
}
