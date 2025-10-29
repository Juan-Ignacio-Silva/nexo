<?php
class IdGenerator
{
    public static function generarID($tipo, PDO $conexion)
    {
        $conexion = require 'database.php';

        // Busca o crea la secuencia
        $stmt = $conexion->prepare("SELECT ultimo_numero FROM secuencias WHERE tipo = ? FOR UPDATE");
        $stmt->execute([$tipo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $numero = $row['ultimo_numero'] + 1;
            $update = $conexion->prepare("UPDATE secuencias SET ultimo_numero = ? WHERE tipo = ?");
            $update->execute([$numero, $tipo]);
        } else {
            $numero = 1;
            $insert = $conexion->prepare("INSERT INTO secuencias (tipo, ultimo_numero) VALUES (?, ?)");
            $insert->execute([$tipo, $numero]);
        }

        // Formatear número con 8 dígitos
        $numeroFormateado = str_pad($numero, 8, '0', STR_PAD_LEFT);
        return "id_{$tipo}_{$numeroFormateado}";
    }
}
