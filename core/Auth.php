<?php
require_once 'Session.php';
require_once ROOT . 'app/models/Usuario.php';
class Auth
{
    public static function restringirDashboard() {
        $conexion = require ROOT . 'app/config/database.php';
        
        if (!Session::has('usuario_id')) {
            header('Location: ../home');
        }
        
        $id = Session::get(key: 'usuario_id');
        $roleIdentificado = Usuario::verificarRole($conexion, $id);

        if ($roleIdentificado['role'] !== 'admin') {
            header('Location: ../home');
            return "No es admin";
        }
    }

    public static function esAdmin() {
        $conexion = require ROOT . 'app/config/database.php';

        $id = Session::get(key: 'usuario_id');
        $roleIdentificado = Usuario::verificarRole($conexion, $id);

        if ($roleIdentificado['role'] !== 'admin') {
            return false;
        }

        return true;
    }

    public static function restringirAcceso() {
        if (!Session::has('usuario_id')) {
            return false;
        }

        return true;
    }



    public static function usuario() {
        return Session::get(key: 'usuario_nombre') ?? 'Invitado';
    }
}
