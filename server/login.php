<?php
session_start();

$USER = "";
$PASS = "";

if (isset($_SESSION["user"])) {
  header("Location: ./index.php");
  exit();
}

if (isset($_COOKIE["recordar"]) && $_COOKIE["recordar"] == "on") {
  $_SESSION["user"] = $USER;
  $_SESSION["pass"] = $PASS;
  header("Location: ./index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <style>
      body {
        height: 100vh;
        width: 100%;
        margin: 0;
        padding: 0;
        font-family: system-ui;
        background-color: #f5f5f5;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .formulario {
        width: 320px;
        padding: 40px;
        background-color: white;
        border: 1px solid black;
        text-align: center;
        border-radius: 16px;
      }

      h1 {
        margin: 0 0 30px 0;
        font-size: 24px;
      }

      .entrada {
        width: 90%;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin: 0 6px 15px 6px;
      }

      label {
        margin-right: 6px;
        font-size: 16px;
        color: #333;
      }

      input {
        padding: 6px 8px;
        border: 1px solid #999;
        font-size: 13px;
        width: 160px;
        border-radius: 8px;
      }

      .recordar {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px 0;
      }

      #input-recordar {
        width: auto;
        margin-right: 6px;
        cursor: pointer;
      }

      #label-recordar {
        font-size: 14px;
      }

      button {
        margin-top: 20px;
        padding: 8px 20px;
        background-color: #e0e0e0;
        border: 1px solid #999;
        cursor: pointer;
        font-size: 13px;
        border-radius: 8px;
      }

      button:hover {
        background-color: #d0d0d0;
      }
    </style>
  </head>
  <body>
    <div class="formulario">
      <h1>Iniciar Sesión</h1>
      <form method="POST" action="./index.php">
        <div class="entrada">
          <label>Usuario:</label>
          <input type="text" name="user" placeholder="user" required/>
        </div>
        <div class="entrada">
          <label>Contraseña:</label>
          <input type="password" name="pass" placeholder="********" required/>
        </div>
        <div class="recordar">
          <input type="checkbox" name="recordar" id="input-recordar" value="on" checked/>
          <label id="label-recordar">Recuérdame</label>
        </div>
        <button type="submit">Ingresar</button>
      </form>
    </div>
  </body>
</html>
