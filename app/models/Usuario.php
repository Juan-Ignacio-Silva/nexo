<?php
require_once ROOT . '/vendor/autoload.php';

use Ramsey\Uuid\Uuid;

class Usuario
{
    public static function registrar($conexion, $nombre, $apellido, $email, $password)
    {
        // Verificar si el email ya existe
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() === 1) {
            return "El email ya está registrado.";
        }

        // Crear nuevo usuario
        $id = Uuid::uuid4()->toString();
        $passwordHash = password_hash(trim($password), PASSWORD_DEFAULT);

        $stmt = $conexion->prepare("INSERT INTO usuarios(id_usuarios, nombre, apellido, email, password, telefono, ciudad, nombre_calle, numero_casa) 
                                    VALUES (:id, :nombre, :apellido, :email, :password, :telefono, :ciudad, :nombre_calle, :numero_casa)");

        return $stmt->execute([ // Aclaracion, role no esta presente ya que esta definido en la base de datos como valor default = "usuario"
            'id' => $id,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'password' => $passwordHash,
            'telefono' => null,
            'ciudad' => null,
            'nombre_calle' => null,
            'numero_casa' => null
        ]);
    }

    public static function login($conexion, $email, $password)
    {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() === 1) {
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $usuario['password'])) {
                return $usuario;
            }
        }

        return false;
    }

    public static function verificarRole($conexion, $id)
    {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuarios = :id");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return 'No se pudo identificar el id del usuario';
        }
    }

    public static function obtenerInfoUser($conexion, $id)
    {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE id_usuarios = :id");
        $stmt->execute(['id' => $id]);

        if ($stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return 'No se pudo identificar el id del usuario';
        }
    }

    public static function updateInfoUser($conexion, $id, $campos, $parametros)
    {
        $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id_usuarios = :id";
        $parametros[':id'] = $id;
        
        if (isset($parametros[':password'])) {
            $parametros[':password'] = password_hash($parametros[':password'], PASSWORD_DEFAULT);
        }
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute($parametros);

        $filasAfectadas = $stmt->rowCount();
        if ($filasAfectadas > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function todosLosUsuarios($conexion) {
        $sql = "SELECT * FROM usuarios";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function usuariosPorFecha($conexion) {
        $sql = "SELECT * FROM usuarios
                ORDER BY fecha_registro DESC
                LIMIT 5;
                ";
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
