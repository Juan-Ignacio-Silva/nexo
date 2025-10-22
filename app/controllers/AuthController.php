<?php
require_once ROOT . 'core/Session.php';
class AuthController {
    public function verificarSession() {
        header('Content-Type: application/json');

        if (!Session::has('usuario_id')) {
            echo json_encode(["ok" => false]);
        } else {
            echo json_encode(["ok" => true]);
        }
        exit;
    }
}
