<?php
class Pago
{
    public static function guardarPago($conexion, $idUsuario, $idTransaccion, $montoTotal, $productosArray, $pedidoInfoJson)
    {
        $idPago = uniqid('pago_');
        $productosJson = json_encode($productosArray);

        $stmt = $conexion->prepare("
            INSERT INTO pago (
                id_pago, id_usuario, id_transaccion_mp, monto_total, estado_pago, productos, pedido_info, fecha_pago
            ) VALUES (?, ?, ?, ?, 'pagado', ?, ?, NOW())
        ");

        return $stmt->execute([
            $idPago,
            $idUsuario,
            $idTransaccion,
            $montoTotal,
            $productosJson,
            $pedidoInfoJson
        ]);
    }

    public static function obtenerPorTransaccion($conexion, $idTransaccion)
    {
        $stmt = $conexion->prepare("
            SELECT * FROM pago
            WHERE id_transaccion_mp = ?
            LIMIT 1
        ");
        $stmt->execute([$idTransaccion]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function obtenerProductosDePago($conexion, $productosJson)
    {
        require_once ROOT . 'app/models/Producto.php';

        $productosIds = json_decode($productosJson, true);
        if (empty($productosIds)) {
            return [];
        }

        // Preparamos la consulta para obtener productos
        $placeholders = implode(',', array_fill(0, count($productosIds), '?'));
        $stmt = $conexion->prepare("SELECT id_producto, nombre, precio FROM producto WHERE id_producto IN ($placeholders)");
        $stmt->execute($productosIds);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agregar cantidad y subtotal
        foreach ($productos as &$p) {
            $p['cantidad'] = $p['cantidad'] ?? 1;
            $p['subtotal'] = $p['precio'] * $p['cantidad'];
        }

        return $productos;
    }
}
