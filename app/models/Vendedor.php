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

    public static function editarProducto(
        $conexion,
        $id_producto,
        $id_vendedor,
        $nombre,
        $precio,
        $cantidad,
        $id_categoria,
        $etiqueta,
        $descripcion,
        $ruta_imagen = null
    ) {
        try {

            // --- Sanitización ---
            $nombre = trim($nombre);
            $descripcion = trim($descripcion ?? '');

            $precio = is_numeric($precio) ? (float)$precio : 0;
            $cantidad = is_numeric($cantidad) ? (int)$cantidad : 0;

            // Categoría
            if ($id_categoria === '' || $id_categoria === 'undefined' || $id_categoria === null) {
                $id_categoria = null;
            } else {
                $id_categoria = (int)$id_categoria;
            }

            // Etiqueta
            if ($etiqueta === '' || $etiqueta === 'undefined') {
                $etiqueta = null;
            }

            // Verificar que el producto pertenece al vendedor
            $check = $conexion->prepare("
            SELECT id_producto 
            FROM producto 
            WHERE id_producto = :id_prod AND id_vendedor = :id_vend
        ");
            $check->execute([
                'id_prod' => $id_producto,
                'id_vend' => $id_vendedor
            ]);

            if ($check->rowCount() === 0) {
                return "Error: No tenés permiso para editar este producto.";
            }

            // Armado dinámico del UPDATE
            $campos = [
                "nombre = :nombre",
                "precio = :precio",
                "cantidad = :cantidad",
                "id_categoria = :id_categoria",
                "etiqueta = :etiqueta",
                "descripcion = :descripcion"
            ];

            $params = [
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad,
                'id_categoria' => $id_categoria,
                'etiqueta' => $etiqueta,
                'descripcion' => $descripcion,
                'id_producto' => $id_producto,
                'id_vendedor' => $id_vendedor
            ];

            // Si viene imagen nueva → agregar
            if (!empty($ruta_imagen) && $ruta_imagen !== "undefined") {
                $campos[] = "imagen = :imagen";
                $params['imagen'] = $ruta_imagen;
            }

            $sql = "
            UPDATE producto
            SET " . implode(", ", $campos) . "
            WHERE id_producto = :id_producto
            AND id_vendedor = :id_vendedor
        ";

            $stmt = $conexion->prepare($sql);
            $stmt->execute($params);

            return true;
        } catch (Exception $e) {
            return "Error al editar producto: " . $e->getMessage();
        }
    }




    public static function eliminarProducto($conexion, $idProducto, $idVendedor)
    {
        $sql = "DELETE FROM producto WHERE id_producto = :id AND id_vendedor = :vendedor";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute([
            'id' => $idProducto,
            'vendedor' => $idVendedor
        ]);
    }
}
