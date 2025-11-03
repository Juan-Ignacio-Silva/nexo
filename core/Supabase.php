<?php
require ROOT . '/vendor/autoload.php';

use Dotenv\Dotenv;

class SupabaseStorage
{
    public function subirArchivo($rutaDestino, $archivoLocal)
    {
        $dotenv = Dotenv::createMutable(__DIR__ . '/../../');
        $dotenv->safeLoad();

        $supabaseUrl = $_ENV("SUPABASE_URL");
        $supabaseKey = $_ENV("SUPABASE_API_KEY");
        $bucket = $_ENV("SUPABASE_NOMBRE_BUCKET");

        $url = "{$supabaseUrl}/storage/v1/object/{$bucket}/{$rutaDestino}";
        $archivo = file_get_contents($archivoLocal);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$supabaseKey}",
            "Content-Type: application/octet-stream"
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $archivo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 || $httpCode === 201) {
            return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$rutaDestino}";
        } else {
            error_log("Error al subir archivo: " . $response);
            return false;
        }
    }
}
