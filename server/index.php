<?php ini_set('display_errors', E_ALL);

session_start();

$SUDO = "";
$PASS = "";

if (isset($_REQUEST["logout"])) {
  session_unset();
  session_destroy();
  setcookie("recordar", "", time() - 1, "/");
  unset($_COOKIE["recordar"]);
  header("Location: ./login.php");
  exit();
}

if (isset($_REQUEST["user"]) && isset($_REQUEST["pass"])) {
  if ($_REQUEST["user"] == $SUDO && $_REQUEST["pass"] == $PASS) {
    $_SESSION["user"] = $SUDO;
    $_SESSION["pass"] = $PASS;

    if (isset($_REQUEST["recordar"]) && $_REQUEST["recordar"] == "on") {
      setcookie("recordar", "on", time() + (60 * 10), "/");
    } else {
      setcookie("recordar", "", time() - 1, "/");
      unset($_COOKIE["recordar"]);
    }
  } else {
    header("Location: ./error.php");
    exit();
  }
}

if (!isset($_SESSION["user"])) {
  if (isset($_COOKIE["recordar"]) && $_COOKIE["recordar"] == "on") {
    $_SESSION["user"] = $SUDO;
    $_SESSION["pass"] = $PASS;
  } else {
    header("Location: ./login.php");
    exit();
  }
}

include "mysqli_aux.php";

$config = include __DIR__ . '/config.php';
$SERVER = $config['DB_SERV'];
$USER = $config['DB_USER'];
$PASSWORD = $config['DB_PASS'];
$DATABASE = $config['DB_NAME'];

$datos = seleccionar([$SERVER, $USER, $PASSWORD, $DATABASE], "SELECT * FROM contactos");
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/dashboard.css">
    <title>Dashboard</title>
  </head>
  <body>
    <div class="container">
      <h1>Registros de Contacto</h1>

      <div id="container-exit">
        <form method="POST" action="./index.php">
          <input type="hidden" name="logout" value="1">
          <button id="logout" type="submit">Cerrar Sesión</button>
        </form>
      </div>

      
      <!-- Modal Actualizar -->
      <div id="actualizar" class="modal">
        <div class="modal-caja">
          <h2>Actualizar Producto</h2>
          <form id="update" action="actualizar.php" method="POST">
            <label>ID del Registro</label>
            <input type="number" name="id" id="id-actualizar" readonly>

            <label>Nombre</label>
            <input type="text" name="nombre" id="nombre-actualizar" required>
            
            <label>Email</label>
            <input type="email" name="email" id="email-actualizar" required>
            
            <label>Teléfono</label>
            <input type="tel" 
              name="telefono" 
              id="telefono-actualizar" 
              pattern="[0-9]{10}"
              maxlength="10"
              title="Ingresa 10 dígitos sin espacios" required>

            <label>Estatus</label>
            <select name="estatus" id="estatus-actualizar" required>
              <option value="no_leido">no_leido</option>
              <option value="leido">leido</option>
              <option value="respondido">respondido</option>
            </select>
          </form>
          <button type="submit" form="update">Actualizar registro</button>
          <a href="#">Cancelar</a>
        </div>
      </div>

      <!-- Modal Eliminar -->
      <div id="eliminar" class="modal">
        <div class="modal-caja">
          <h2 id="nombre-eliminar">¿Está seguro de eliminar el registro?</h2>
          <form id="delete" action="eliminar.php" method="POST">
            <input type="hidden" name="id" id="id-eliminar">
          </form>
          <button type="submit" form="delete">Eliminar registro</button>
          <a href="#">Cancelar</a>
        </div>
      </div>

      <!-- Tabla de inventario -->
      <table id="contactos">
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Edad</th>
          <th>Nacionalidad</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Asunto</th>
          <th>Mensaje</th>
          <th>Fecha</th>
          <th>Estatus</th>
          <th colspan="2">Acciones</th>
        </tr>
        <?php foreach($datos as $dato): ?>
        <tr>
          <td><?php echo $dato[0] ?></td>
          <td><?php echo $dato[1] ?></td>
          <td><?php echo $dato[2] ?></td>
          <td><?php echo $dato[3] ?></td>
          <td><?php echo $dato[4] ?></td>
          <td><?php echo $dato[5] ?></td>
          <td><?php echo $dato[6] ?></td>
          <td><?php echo $dato[7] ?></td>
          <td><?php echo $dato[8] ?></td>
          <td><?php echo $dato[9] ?></td>
          <td>
            <a href="#actualizar" 
               class="btn-actualizar"
               onclick="
               cargarId(<?php echo $dato[0]; ?>); 
               cargarDatos(
               '<?php echo ($dato[1]); ?>', 
               <?php echo ($dato[4]); ?>, 
               '<?php echo $dato[5]; ?>',
               '<?php echo $dato[9]; ?>',);"
            >Actualizar
            </a>
          </td>
          <td>
            <a href="#eliminar" 
               class="btn-eliminar"
               onclick="
               cargarId(<?php echo $dato[0]; ?>); 
               cargarNombre('<?php echo $dato[1]; ?>');"
            >Eliminar
            </a>
          </td>
        </tr>
        <?php endforeach ?>
      </table>


    </div>

    <script>
      function cargarId(idRegistro) {
        const inputActualizar = document.getElementById('id-actualizar');
        if (inputActualizar) inputActualizar.value = idRegistro;
        
        const inputEliminar = document.getElementById('id-eliminar');
        if (inputEliminar) inputEliminar.value = idRegistro;
      }

      function cargarNombre(nombre) {
        document.getElementById('nombre-eliminar').textContent = 
          '¿Está seguro de eliminar el registro del contacto "' + nombre + '"?';
      }

      function cargarDatos(nombre, telefono, email, estatus) {
        document.getElementById('nombre-actualizar').value = nombre;
        document.getElementById('telefono-actualizar').value = telefono;
        document.getElementById('email-actualizar').value = email;
        document.getElementById('estatus-actualizar').value = estatus;
      }
    </script>
  </body>
</html>
