<?php
class Pago
{
    public static function guardarPago($conexion, $idUsuario, $idTransaccion, $montoTotal, $productosJson, $pedidoInfoJson)
    {
        $stmt = $conexion->prepare("
            INSERT INTO pago (
                id_pago, id_usuario, id_transaccion_mp, monto_total, estado_pago, productos, pedido_info
            ) VALUES (uuid_generate_v4(), ?, ?, ?, 'pagado', ?, ?)
        ");

        return $stmt->execute([
            $idUsuario,
            $idTransaccion,
            $montoTotal,
            $productosJson,
            $pedidoInfoJson
        ]);
    }
}
