<?php
class CategoriaModel
{
    // Crear nueva categoría
    public static function crear($conexion, $nombre, $icono_url)
    {
        $sql = "INSERT INTO categorias (nombre, icono_url) VALUES (?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([$nombre, $icono_url]);
        return $conexion->lastInsertId();
    }

    // Obtener todas las categorías
    public static function obtenerTodas($conexion)
    {
        $sql = "SELECT * FROM categorias ORDER BY id DESC";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Eliminar categoría por ID
    public static function eliminar($conexion, $id)
    {
        $sql = "DELETE FROM categorias WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        return $stmt->execute([$id]);
    }

    // Obtiene las 5 categorías más destacadas según el promedio de reseñas
    public static function obtenerCategoriasDestacadas($conexion)
    {
        $sql = "
        SELECT 
            c.id AS id_categoria,
            c.nombre AS categoria,
            c.icono_url AS icono,
            ROUND(AVG(r.calificacion), 2) AS promedio
        FROM producto p
        JOIN categorias c ON c.id = p.id_categoria
        JOIN resena r ON r.id_producto = p.id_producto
        WHERE p.id_categoria IS NOT NULL
        GROUP BY c.id, c.nombre, c.icono_url
        ORDER BY promedio DESC
        LIMIT 5
    ";

        $stmt = $conexion->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function obtenerPorId($conexion, $id)
    {
        $sql = "SELECT * FROM categorias WHERE id = :id";
        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
