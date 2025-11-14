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

    public static function obtenerCantidadVendidaPorVendedor($conexion, $idVendedor)
    {
        // Obtener todos los registros de pago
        $sql = "SELECT productos FROM pago";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inicializamos el contador total
        $totalVendidos = 0;

        foreach ($pagos as $pago) {
            $productos = json_decode($pago['productos'], true);

            if (!is_array($productos)) continue;

            // Recorremos cada producto del JSON
            foreach ($productos as $prod) {
                $idProducto = $prod['id'] ?? null;
                $cantidad = (int)($prod['cantidad'] ?? 0);

                if (!$idProducto || $cantidad <= 0) continue;

                // Buscamos el producto y verificamos si pertenece al vendedor
                $sqlProd = "SELECT id_vendedor FROM producto WHERE id_producto = :id";
                $stmtProd = $conexion->prepare($sqlProd);
                $stmtProd->execute([':id' => $idProducto]);
                $producto = $stmtProd->fetch(PDO::FETCH_ASSOC);

                // Si el producto es del vendedor, sumamos su cantidad
                if ($producto && $producto['id_vendedor'] === $idVendedor) {
                    $totalVendidos += $cantidad;
                }
            }
        }

        return $totalVendidos;
    }

    public static function obtenerTotalRecaudadoPorVendedor($conexion, $idVendedor)
    {
        // Obtenemos todos los pagos registrados
        $sql = "SELECT productos FROM pago";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalRecaudado = 0;

        foreach ($pagos as $pago) {
            $productos = json_decode($pago['productos'], true);

            if (!is_array($productos)) continue;

            foreach ($productos as $prod) {
                $idProducto = $prod['id'] ?? null;
                $subtotal = (float)($prod['subtotal'] ?? 0);

                if (!$idProducto || $subtotal <= 0) continue;

                // Verificamos si el producto pertenece al vendedor
                $sqlProd = "SELECT id_vendedor FROM producto WHERE id_producto = :id";
                $stmtProd = $conexion->prepare($sqlProd);
                $stmtProd->execute([':id' => $idProducto]);
                $producto = $stmtProd->fetch(PDO::FETCH_ASSOC);

                if ($producto && $producto['id_vendedor'] === $idVendedor) {
                    $totalRecaudado += $subtotal;
                }
            }
        }

        return $totalRecaudado;
    }

    public static function editarProducto($conexion, $id_producto, $id_vendedor, $nombre, $precio, $cantidad, $id_categoria, $etiqueta, $descripcion, $ruta_imagen = null)
    {
        // --- Sanitización y validaciones seguras ---
        $nombre = trim($nombre);
        $descripcion = trim($descripcion ?? '');

        // Precio siempre número decimal
        $precio = is_numeric($precio) ? (float)$precio : 0;

        // Cantidad siempre integer
        $cantidad = is_numeric($cantidad) ? (int)$cantidad : 0;

        // Categoría puede venir "", null, undefined
        if ($id_categoria === '' || $id_categoria === null || $id_categoria === 'undefined') {
            $id_categoria = null;
        } else {
            $id_categoria = (int)$id_categoria;
        }

        // Etiquetas puede venir vacía
        if ($etiqueta === '' || $etiqueta === null || $etiqueta === 'undefined') {
            $etiqueta = null;
        }

        // Si no hay imagen nueva, NO actualizar ese campo
        if ($ruta_imagen) {
            $sql = "
            UPDATE producto
            SET nombre = $1,
                precio = $2,
                cantidad = $3,
                id_categoria = $4,
                etiqueta = $5,
                descripcion = $6,
                imagen = $7
            WHERE id_producto = $8
            AND id_vendedor = $9
        ";

            $params = [
                $nombre,
                $precio,
                $cantidad,
                $id_categoria,
                $etiqueta,
                $descripcion,
                $ruta_imagen,
                $id_producto,
                $id_vendedor
            ];
        } else {
            // Sin imagen
            $sql = "
            UPDATE producto
            SET nombre = $1,
                precio = $2,
                cantidad = $3,
                id_categoria = $4,
                etiqueta = $5,
                descripcion = $6
            WHERE id_producto = $7
            AND id_vendedor = $8
        ";

            $params = [
                $nombre,
                $precio,
                $cantidad,
                $id_categoria,
                $etiqueta,
                $descripcion,
                $id_producto,
                $id_vendedor
            ];
        }

        // --- Ejecutar la consulta ---
        $stmt = $conexion->prepare($sql);
        return $stmt->execute($params);
    }


    public static function eliminarProducto($conexion, $idProducto, $idVendedor)
    {
        $sql = "DELETE FROM producto WHERE id_producto = ? AND id_vendedor = ?";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute([$idProducto, $idVendedor]);
    }
}
