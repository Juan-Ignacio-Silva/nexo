<?php
// core/headers.php

// Lista de dominios permitidos
$allowed_origins = [
    'https://nexouy.shop',      // Producción
    'http://localhost',         // Desarrollo local
    'http://127.0.0.1'          // Alternativa local
];

// Detectar el dominio de origen
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Si el origen está en la lista, permitirlo
if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// Métodos HTTP permitidos
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Cabeceras permitidas
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Tipo de contenido predeterminado
header("Content-Type: application/json; charset=UTF-8");

// Desactivar cache (útil durante desarrollo)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Asegurar codificación
mb_internal_encoding("UTF-8");

// Responder correctamente a solicitudes OPTIONS (preflight CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
