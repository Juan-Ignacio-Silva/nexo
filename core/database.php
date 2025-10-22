<?php
$host = getenv('POSTGRES_HOST') ?: 'postgres';
$db   = getenv('POSTGRES_DB') ?: 'nexodb';
$user = getenv('POSTGRES_USER') ?: 'usuario';
$pass = getenv('POSTGRES_PASSWORD') ?: 'clave';
$port = getenv('POSTGRES_PORT') ?: 5432;

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db";
    $conexion = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit("Conexión fallida: " . $e->getMessage());
}

return $conexion;
