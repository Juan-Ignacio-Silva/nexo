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
}
