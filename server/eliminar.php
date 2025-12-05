<?php
  include "mysqli_aux.php";
  $config = include __DIR__ . '/config.php';
  $SRV = $config['DB_SERV'];
  $USER = $config['DB_USER'];
  $PASS = $config['DB_PASS'];
  $DB = $config['DB_NAME'];
  $pag = '';

  if (isset($_POST["id"])) {
    $id = $_POST["id"];

    $QUERY = "DELETE FROM contactos WHERE id = $id";

    $id = ejecutar([$SRV, $USER, $PASS, $DB], $QUERY);

    $pag .= "<div class='mensaje-contenedor'>";
    if ($id != 0) {
      $pag .= "<h1 class='exito'>Éxito</h1>";
      $pag .= "<p>Registro eliminado correctamente</p>";
    } else {
      $pag .= "<h1 class='error'>Error</h1>";
      $pag .= "<p>No se pudo eliminar el registro. Contacta con el ADMON</p>";
    }
    $pag .= "<a href='./index.php'>← Regresar al panel de admin</a>";
  } else {
    $pag .= "<div class='mensaje-contenedor'>";
    $pag .=  "<h1 class='error'>Error</h1>";
    $pag .=  "<p>Error en la consistencia de datos, chécalos tilin</p>";
    $pag .=  "<a href='./index.php'>← Regresar al panel de admin</a>";
  }
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Eliminar</title>
  </head>
  <body>
    <?php echo $pag; ?>
  </body>
</html>