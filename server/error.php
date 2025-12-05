<?php
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Error</title>
  <style>
    body {
      height: 80vh;
      width: auto;
      font-family: system-ui;
      background-color: #f5f5f5;
    }
    
    body, #container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    #container {
      width: 60%;
      height: 50%;
      border: 1px solid black;
      margin-top: 6em;
      background-color: white;
      border-radius: 16px;
      padding: 20px;
    }

    h1 {
      color: #d32f2f;
      margin-bottom: 10px;
    }

    p {
      color: #666;
      margin-bottom: 10px;
      text-align: center;
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
    <div id="container">
      <h1>Error de autenticación</h1>
      <p>Usuario o contraseña incorrectos</p>
      <form method="POST" action="./login.php">
        <button type="submit">Volver al login</button>
      </form>
    </div>
  </body>
</html>
