<?php ini_set('display_errors', E_ALL);

// Verificar que sea POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    echo "Acceso denegado.";
    exit;
}

// Incluir funciones auxiliares
include "mysqli_aux.php";
$config = include __DIR__ . '/config.php';

// Credenciales de base de datos
$SRV = $config['DB_SERV'];
$USER = $config['DB_USER'];
$PASS = $config['DB_PASS'];
$DB = $config['DB_NAME'];

// Validar que todos los campos requeridos estén presentes
$campos_requeridos = ['nombre', 'edad', 'nacionalidad', 'telefono', 'email', 'asunto', 'mensaje'];
foreach ($campos_requeridos as $campo) {
    if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
        http_response_code(400);
        echo "El campo '$campo' es requerido.";
        exit;
    }
}

// Sanitizar datos
$nombre = sanitizar_string($_POST["nombre"]);
$edad = filter_var($_POST["edad"], FILTER_VALIDATE_INT);
$nacionalidad = sanitizar_string($_POST["nacionalidad"]);
$telefono = sanitizar_string($_POST["telefono"]);
$email = sanitizar_string($_POST["email"]);
$asunto = sanitizar_string($_POST["asunto"]);
$mensaje = sanitizar_string($_POST["mensaje"]);

// Validaciones
if ($edad === false || $edad < 1 || $edad > 100) {
    http_response_code(400);
    echo "La edad debe ser un número entre 1 y 100.";
    exit;
}

if (!validar_email($email)) {
    http_response_code(400);
    echo "El email no es válido.";
    exit;
}

if (!validar_telefono($telefono)) {
    http_response_code(400);
    echo "El teléfono debe tener 10 dígitos.";
    exit;
}

if (strlen($nombre) < 3) {
    http_response_code(400);
    echo "El nombre debe tener al menos 3 caracteres.";
    exit;
}

if (strlen($mensaje) < 10) {
    http_response_code(400);
    echo "El mensaje debe tener al menos 10 caracteres.";
    exit;
}

// Insertar en la base de datos
$QUERY = "INSERT INTO contactos 
          (nombre, edad, nacionalidad, telefono, email, asunto, mensaje) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";

$params = [$nombre, $edad, $nacionalidad, $telefono, $email, $asunto, $mensaje];
$types = "sisssss"; // s=string, i=integer

$id = insertar([$SRV, $USER, $PASS, $DB], $QUERY, $params, $types);

if ($id > 0) {
    http_response_code(200);   
} else {
    http_response_code(500);
    echo "Error al guardar el mensaje.";
}
?>