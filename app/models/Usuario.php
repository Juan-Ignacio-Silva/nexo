<?php
require_once ROOT . '/vendor/autoload.php';
use Ramsey\Uuid\Uuid;

class Usuario
{
    public static function registrar($conexion, $nombre, $apellido, $email, $password)
    {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();
        if ($resultado->num_rows === 1) {
            $error = "El email ya esta registrado.";
        } else {
            $id = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $password = password_hash(trim($password), PASSWORD_DEFAULT); // Seguridad
            $stmt = $conexion->prepare("INSERT INTO usuarios(id_usuarios, nombre, apellido, email, password) VALUES (?, ?, ?, ?, ?)");
            
            if (!$stmt) return false;
            
            $stmt->bind_param("sssss", $id, $nombre, $apellido, $email, $password);
            return $stmt->execute();
        }
    }

    public static function login($conexion, $email, $password) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc(); //Aca usamos fetch_assoc para igualar resultado como un array asociativo.

            if (password_verify($password, $usuario['password'])) {
                return $usuario;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function verificarRole ($conexion, $id) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            return $usuario;
        } else { 
            return 'No se pudo identificar el id del usuario';
        }

    }

    public static function obtenerInfoUser ($conexion, $id) {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuarios = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();
            return $usuario;
        } else { 
            return 'No se pudo identificar el id del usuario';
        }

    }
}
