<?php
require_once 'Session.php';
require_once ROOT . 'app/models/Usuario.php';
class Auth
{
    public static function restringirDashboard($rolPermitido) {
        $conexion = require ROOT . 'core/database.php';
        
        if (!Session::has('usuario_id')) {
            header('Location: ../home');
        }
        
        $id = Session::get(key: 'usuario_id');
        $roleIdentificado = Usuario::verificarRole($conexion, $id);

        if ($roleIdentificado['role'] !== $rolPermitido) {
            header('Location: ../home');
            return "No tienes acceso!!!";
        }
    }

    public static function esAdmin() {
        $conexion = require ROOT . 'core/database.php';

        $id = Session::get(key: 'usuario_id');
        $roleIdentificado = Usuario::verificarRole($conexion, $id);

        if ($roleIdentificado['role'] !== 'admin') {
            return false;
        }

        return true;
    }

    public static function esVendedor() {
        $conexion = require ROOT . 'core/database.php';
        
        if (!Session::has('usuario_id')) {
            return false;
        }
        
        $id = Session::get(key: 'usuario_id');
        $roleIdentificado = Usuario::verificarRole($conexion, $id);

        if ($roleIdentificado['role'] !== 'vendedor') {
            return "Registrate como vendedor";
        } else {
            header('Location: ../vendedor/dashboard' );
        }
    }

    public static function restringirAcceso() {
        if (!Session::has('usuario_id')) {
            return false;
        }

        return true;
    }

    public static function restringirAccesoWeb() {
        if (!Session::has('usuario_id')) {
            header("Location: ../home");
        } else {
            return true;
        }
    }

    public static function usuario() {
        return Session::get(key: 'usuario_nombre') ?? 'Invitado';
    }

    public static function infoUser() {
        $conexion = require ROOT . 'core/database.php';

        $id = Session::get(key: 'usuario_id');
        $infoUser = Usuario::obtenerInfoUser($conexion, $id);

        return $infoUser;
    }
}
