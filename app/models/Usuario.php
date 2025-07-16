<?php
require_once ROOT . '/vendor/autoload.php';
use Ramsey\Uuid\Uuid;

class Usuario
{
    public static function registrar($conexion, $nombre, $apellido, $email, $password)
    {
        $id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        $password = password_hash(trim($password), PASSWORD_DEFAULT); // Seguridad
        $stmt = $conexion->prepare("INSERT INTO usuarios(id_usuario, nombre, apellido, email, password) VALUES (?, ?, ?, ?, ?)");

        if (!$stmt) return false;

        $stmt->bind_param("sssss", $id, $nombre, $apellido, $email, $password);
        return $stmt->execute();
    }
}
