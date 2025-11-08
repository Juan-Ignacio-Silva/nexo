<?php
class Pago
{
    public static function guardarPago($conexion, $idUsuario, $idTransaccion, $montoTotal, $productosJson, $pedidoInfoJson)
    {
        $idPago = uniqid('pago_');

        $stmt = $conexion->prepare("
            INSERT INTO pago (
                id_pago, id_usuario, id_transaccion_mp, monto_total, estado_pago, productos, pedido_info
            ) VALUES (?, ?, ?, ?, 'pagado', ?, ?)
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
}
