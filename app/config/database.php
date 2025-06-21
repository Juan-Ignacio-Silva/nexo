<?php
$host = "localhost";
$usuario = "root";
$password = "";
$db = "nexodb";

$conexion = new mysqli(hostname: $host, username: $usuario, password: $password, database: $db);

if ($conexion->connect_error) {
    exit("Conexión fallida: " . $conexion->connect_error);
} else {
    echo 'OK DB';
}
