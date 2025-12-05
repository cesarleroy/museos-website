<?php
/**
 * Librería auxiliar para operaciones con MySQL usando mysqli
 * Versión de debugging
 */

function conectar($creds) {
    $conn = mysqli_connect($creds[0], $creds[1], $creds[2], $creds[3]);
    
    if (mysqli_connect_errno()) {
        error_log("Error de conexión MySQL: " . mysqli_connect_error());
        echo "ERROR CONECTAR: " . mysqli_connect_error() . "<br>";
        return false;
    }
    
    mysqli_set_charset($conn, "utf8mb4");
    echo "✓ Conexión establecida<br>";
    
    return $conn;
}

function insertar($creds, $query, $params = [], $types = '') {
    echo "<br>--- INICIO FUNCIÓN INSERTAR ---<br>";
    echo "Query: $query<br>";
    echo "Types: $types<br>";
    echo "Params: <pre>"; print_r($params); echo "</pre>";
    
    $conn = conectar($creds);
    if (!$conn) {
        echo "✗ ERROR: No se pudo conectar<br>";
        return 0;
    }
    
    echo "✓ Conectado<br>";
    
    // Si no hay parámetros, ejecutar query directamente
    if (empty($params)) {
        echo "Ejecutando query directa (sin params)<br>";
        $res = mysqli_query($conn, $query);
        if (!$res) {
            echo "✗ ERROR query: " . mysqli_error($conn) . "<br>";
            mysqli_close($conn);
            return 0;
        }
        $id = mysqli_insert_id($conn);
        echo "✓ Query directa OK. ID: $id<br>";
        mysqli_close($conn);
        return $id;
    }
    
    // Preparar statement
    echo "Preparando statement...<br>";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        echo "✗ ERROR preparando: " . mysqli_error($conn) . "<br>";
        mysqli_close($conn);
        return 0;
    }
    
    echo "✓ Statement preparado<br>";
    
    // Bind de parámetros
    echo "Haciendo bind_param con types='$types'...<br>";
    $bind_result = mysqli_stmt_bind_param($stmt, $types, ...$params);
    if (!$bind_result) {
        echo "✗ ERROR en bind_param: " . mysqli_stmt_error($stmt) . "<br>";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return 0;
    }
    
    echo "✓ Bind OK<br>";
    
    // Ejecutar
    echo "Ejecutando statement...<br>";
    $res = mysqli_stmt_execute($stmt);
    if (!$res) {
        echo "✗ ERROR ejecutando: " . mysqli_stmt_error($stmt) . "<br>";
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        return 0;
    }
    
    echo "✓ Execute OK<br>";
    
    $id = mysqli_insert_id($conn);
    echo "✓ ID obtenido: $id<br>";
    
    // Verificar affected rows
    $affected = mysqli_stmt_affected_rows($stmt);
    echo "Filas afectadas: $affected<br>";
    
    // Limpiar
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    echo "--- FIN FUNCIÓN INSERTAR ---<br><br>";
    
    return $id;
}

  function seleccionar($creds, $query) {
    $registros = [];

    # Conectarse y comprobar
    $conn = mysqli_connect($creds[0], $creds[1], $creds[2], $creds[3]);
    if (mysqli_connect_errno()) return false;
    
    # Codificación de carácteres
    mysqli_set_charset($conn, "utf8mb4");

    # Ejecutar la operación
    $res = mysqli_query($conn, $query);

    # Recuperar registros
    while($registro = mysqli_fetch_row($res)) {
      $registros[] = $registro;
    }

    # Liberando objetos de datos empleados
    mysqli_free_result($res);

    # Desconectarse
    mysqli_close($conn);

    return $registros;
  }

function ejecutar($creds, $query) {
    # Conectarse y comprobar
    $conn = mysqli_connect($creds[0], $creds[1], $creds[2], $creds[3]);
    if (mysqli_connect_errno()) return false;
    
    # Codificación de carácteres
    mysqli_set_charset($conn, "utf8mb4");
    
    # Ejecutar la operación
    $res = mysqli_query($conn, $query);

    # Desconectarse
    mysqli_close($conn);

    return $res;
}

function sanitizar_string($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validar_telefono($telefono) {
    $telefono = preg_replace('/[^0-9]/', '', $telefono);
    return strlen($telefono) === 10;
}