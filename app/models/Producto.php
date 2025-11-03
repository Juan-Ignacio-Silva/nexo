<?php
class Producto
{
    public static function productosInfo($conexion)
    {
        $sql = "
            SELECT 
                p.id_producto,
                p.nombre,
                p.precio,
                p.categoria,
                p.etiqueta,
                p.descripcion,
                p.imagen,
                ROUND(AVG(r.calificacion)::numeric, 2) AS calificacion_promedio,
                COUNT(r.id_resena) AS total_resenas
            FROM producto p
            LEFT JOIN resena r ON r.id_producto = p.id_producto
            GROUP BY 
                p.id_producto, 
                p.nombre, 
                p.precio, 
                p.categoria, 
                p.etiqueta, 
                p.descripcion, 
                p.imagen
            ORDER BY calificacion_promedio DESC NULLS LAST, total_resenas DESC
            LIMIT 3
        ";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $productos;
    }

    public static function obtenerPorId($conexion, $id)
    {
        $sql = "
            SELECT 
                p.id_producto, 
                p.nombre, 
                p.precio, 
                p.categoria, 
                p.etiqueta, 
                p.descripcion, 
                p.imagen, 
            COALESCE(ROUND(AVG(r.calificacion), 2), 0) AS promedio
            FROM producto p
            LEFT JOIN resena r ON p.id_producto = r.id_producto
            WHERE p.id_producto = :id
            GROUP BY p.id_producto;
        ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Con esto solo obtenemos todos los productos y nada más.
    public static function obtenerTodosProductos($conexion)
    {
        $sql = "SELECT * FROM producto";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function resenasProducto($conexion, $id)
    {
        $sql = "
            SELECT r.id_resena, r.id_usuarios, u.nombre AS nombre_usuario,
                r.id_producto, p.nombre AS nombre_producto,
                r.comentario, r.calificacion
            FROM resena r
            JOIN usuarios u ON r.id_usuarios = u.id_usuarios
            JOIN producto p ON r.id_producto = p.id_producto
            WHERE r.id_producto = :id
            ";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarProductos($conexion, $busqueda)
    {
        $sql = "SELECT * FROM producto 
                WHERE LOWER(nombre) LIKE LOWER(:busqueda) 
                OR LOWER(categoria) LIKE LOWER(:busqueda)
                ";

        $stmt = $conexion->prepare($sql);
        $stmt->execute([':busqueda' => "%" . $busqueda . "%"]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorIds(array $ids, $conexion)
    {
        if (empty($ids)) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "
        SELECT
            p.id_producto,
            p.id_vendedor,
            p.nombre,
            p.precio,
            p.categoria,
            p.etiqueta,
            p.descripcion,
            p.imagen,
            p.cantidad,
            ROUND(AVG(r.calificacion)::numeric, 2) AS calificacion_promedio,
            COUNT(r.id_resena) AS total_resenas
        FROM producto p
        LEFT JOIN resena r ON r.id_producto = p.id_producto
        WHERE p.id_producto IN ($placeholders)
        GROUP BY 
            p.id_producto,
            p.id_vendedor,
            p.nombre,
            p.precio,
            p.categoria,
            p.etiqueta,
            p.descripcion,
            p.imagen,
            p.cantidad
    ";

        $stmt = $conexion->prepare($sql);
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function productosIdVendedor($conexion, $idVendedor)
    {
        $sql = "SELECT * FROM producto WHERE id_vendedor = :id_vendedor";
        $stmt = $conexion->prepare($sql);
        $stmt->execute(['id_vendedor' => $idVendedor]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function registrarProducto($conexion, $nombreProducto, $precio, $stock, $categoria, $etiquetas, $descripcion, $imagen, $idVendedor)
    {
        require_once ROOT . 'core/IdGenerator.php';

        // Generar id único para el producto
        $id = IdGenerator::generarID('producto', $conexion);

        $stmt = $conexion->prepare("INSERT INTO producto(id_producto, id_vendedor, nombre, precio, categoria, etiqueta, descripcion, imagen, cantidad) 
                VALUES (:idProducto, :idVendedor, :nombreProducto, :precio, :categoria, :etiquetas, :descripcion, :imagen, :stock)");

        $resultado = $stmt->execute([
            'idProducto' => $id,
            'idVendedor' => $idVendedor,
            'nombreProducto' => $nombreProducto,
            'precio' => $precio,
            'categoria' => $categoria,
            'etiquetas' => $etiquetas,
            'descripcion' => $descripcion,
            'imagen' => $imagen,
            'stock' => $stock
        ]);

        if ($resultado) {
            return true;
        }

        return false;
    }

    public static function registrarResena($conexion, $calificacion, $comentario, $idProducto, $idUsuario) {
        require_once ROOT . 'core/IdGenerator.php';

        // Generar id único para el producto
        $id = IdGenerator::generarID('resena', $conexion);

        $stmt = $conexion->prepare("INSERT INTO resena(id_resena, id_usuarios, id_producto, comentario, calificacion) 
                VALUES (:id_resena, :id_usuarios, :id_producto, :comentario, :calificacion)");

        $resultado = $stmt->execute([
            'id_resena' => $id,
            'id_usuarios' => $idUsuario,
            'id_producto' => $idProducto,
            'comentario' => $comentario,
            'calificacion' => $calificacion
        ]);

        if ($resultado) {
            return true;
        }

        return false;
    }

    public static function guardarImagen($conexion, $urlImagen) {
        $query = $conexion->prepare("INSERT INTO productos (imagen_url) VALUES (:url)");
        $query->bindParam(':url', $urlImagen);
        $query->execute();
    }
}
