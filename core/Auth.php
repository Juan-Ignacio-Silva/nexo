<?php
require_once 'Session.php';

class Auth
{
    public static function restringir() {

        if (!Session::has('usuario_id')) {
            header("Location: /usuario/login");
            exit;
        }
    }

    public static function usuario() {
        return Session::get('usuario_nombre') ?? 'Invitado';
    }
}
