<?php
// Declaramos las variables con los valores correspondintes.
$host = "localhost";
$usuario = "root";
$password = "";
$db = "nexodb";

// Instancaimos una nueva conexion, le pasamos las variables con los valores ya declarados.
$conexion = new mysqli(hostname: $host, username: $usuario, password: $password, database: $db);

if ($conexion->connect_error) {
    exit("Conexión fallida: " . $conexion->connect_error); // Si hay algun error manda un mensaje con el error.
} else {
    return 'OK DB'; // Si no hay error manda un mensaje de estado OK
}
