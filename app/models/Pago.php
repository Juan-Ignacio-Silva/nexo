<?php
class Pago
{
    public static function guardarPago($conexion, $idUsuario, $idTransaccion, $montoTotal, $productos, $pedidoInfoJson)
    {
        $idPago = uniqid('pago_');

        if (is_array($productos)) {
            $productosJson = json_encode($productos, JSON_UNESCAPED_UNICODE);
        }

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
}
