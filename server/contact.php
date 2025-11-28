<?php

// Activar reporte de errores para depuración (quitar en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Verificar que el formulario se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Recibir los datos
    $nombre = htmlspecialchars($_POST['nombre'] ?? 'Anonimo');
    $email = htmlspecialchars($_POST['email'] ?? 'Sin correo');
    $asunto = htmlspecialchars($_POST['asunto'] ?? 'Sin asunto');
    $mensaje = htmlspecialchars($_POST['mensaje'] ?? 'Sin mensaje');

    // 3. Formatear el contenido
    $contenido = "Fecha: " . date('Y-m-d H:i:s') . "\n";
    $contenido .= "De: $nombre ($email)\n";
    $contenido .= "Asunto: $asunto\n";
    $contenido .= "Mensaje: $mensaje\n";
    $contenido .= "-----------------------------------\n\n";

    // 4. Guardar en archivo
    // __DIR__ obtiene la ruta absoluta de la carpeta donde está este archivo PHP.
    // Esto evita problemas si el script se ejecuta desde otro contexto.
    $rutaArchivo = __DIR__ . '/mensajes_recibidos.txt';

    if(file_put_contents($rutaArchivo, $contenido, FILE_APPEND)) {
        // ÉXITO:
        http_response_code(200);
        echo "Mensaje recibido correctamente.";
    } else {
        // ERROR AL GUARDAR:
        http_response_code(500);
        // Capturamos el error específico del sistema para saber por qué falló
        $error = error_get_last();
        echo "Error al guardar en: $rutaArchivo. Detalles: " . $error['message'];
    }

} else {
    // Si entran directo al archivo sin enviar datos
    http_response_code(403); 
    echo "Acceso denegado.";
}
?>