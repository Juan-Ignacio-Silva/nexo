<?php
class Vendedor
{
    public static function registroVendedor($conexion, $nombreTienda, $rut_empresa, $descripcion, $id_usuarios)
    {
        require_once ROOT . 'core/IdGenerator.php';

        try {
            // Iniciar transacción
            $conexion->beginTransaction();

            // Verificar si el nombre ya existe
            $stmt = $conexion->prepare("SELECT * FROM vendedor WHERE nombre_empresa = :nombre_empresa");
            $stmt->execute(['nombre_empresa' => $nombreTienda]);

            if ($stmt->rowCount() === 1) {
                return "El nombre de tienda ya está registrado.";
            }

            // Generar id único para el vendedor
            $id = IdGenerator::generarID('vendedor', $conexion);

            // Insertar vendedor
            $stmt = $conexion->prepare("
                INSERT INTO vendedor(id_vendedor, id_usuarios, nombre_empresa, rut_empresa, descripcion) 
                VALUES (:id_vendedor, :id_usuarios, :nombre_tienda, :rut_empresa, :descripcion)
            ");
            $stmt->execute([
                'id_vendedor' => $id,
                'id_usuarios' => $id_usuarios,
                'nombre_tienda' => $nombreTienda,
                'rut_empresa' => $rut_empresa ?: 'nada', // Si viene vacío, se guarda 'nada'
                'descripcion' => $descripcion
            ]);

            // Actualizar role del usuario
            $stmt = $conexion->prepare("
                UPDATE usuarios
                SET role = 'vendedor'
                WHERE id_usuarios = :id_usuarios
            ");
            $stmt->execute(['id_usuarios' => $id_usuarios]);

            // Confirmar transacción
            $conexion->commit();

            return true;
        } catch (Exception $e) {
            // Revertir cambios si algo falla
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            return "Error al registrar vendedor: " . $e->getMessage();
        }
    }

    public static function identificarVendedor($conexion, $idUsuario)
    {
        $stmt = $conexion->prepare("SELECT id_vendedor FROM vendedor WHERE id_usuarios = :id_usuarios");
        $stmt->execute(['id_usuarios' => $idUsuario]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return 'No se pudo identificar al vendedor';
        }
    }
}
