<?php
session_start();
require_once '../Backend/conexion.php';

// Verificamos si el usuario está logueado
if (!isset($_SESSION['IdUsuario'])) {
    header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/Log_Reg.php");
    exit();
}

$idUsuario = $_SESSION['IdUsuario'];

// Consultamos los datos actuales del usuario
$query = "SELECT Nombres, ApellidoPat, ApellidoMat, Correo, Celular 
          FROM TUsuarios 
          WHERE IdUsuario = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $idUsuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

$stmt->close();
$link->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Datos</title>
  <style>
      body {
          font-family: Arial, sans-serif;
          background: #f5f5f5;
          padding: 20px;
      }
      h1 {
          text-align: center;
          color: #333;
      }
      .form-box {
          max-width: 400px;
          margin: auto;
          background: #fff;
          padding: 20px;
          border-radius: 10px;
          box-shadow: 0 2px 8px rgba(0,0,0,0.2);
      }
      .input-box {
          margin-bottom: 15px;
      }
      .input-box label {
          display: block;
          font-size: 14px;
          margin-bottom: 5px;
          color: #555;
      }
      .input-box input {
          width: 100%;
          padding: 8px;
          border: 1px solid #ccc;
          border-radius: 5px;
      }
      .btn {
          display: block;
          width: 100%;
          padding: 10px;
          background: #007bff;
          border: none;
          color: white;
          border-radius: 5px;
          font-size: 16px;
          cursor: pointer;
      }
      .btn:hover {
          background: #0056b3;
      }
      p {
          text-align: center;
          margin-top: 15px;
      }
      a {
          color: #007bff;
          text-decoration: none;
      }
      a:hover {
          text-decoration: underline;
      }
  </style>
</head>
<body>
  <h1>Actualizar tus datos</h1>
  <div class="form-box">
      <form action="php/procesarEditarDatos.php" method="POST">
          <div class="input-box">
              <label for="nombres">Nombres</label>
              <input type="text" required name="nombres" id="nombres" value="<?php echo htmlspecialchars($usuario['Nombres']); ?>">
          </div>

          <div class="input-box">
              <label for="apellido_pat">Apellido Paterno</label>
              <input type="text" required name="apellido_pat" id="apellido_pat" value="<?php echo htmlspecialchars($usuario['ApellidoPat']); ?>">
          </div>

          <div class="input-box">
              <label for="apellido_mat">Apellido Materno</label>
              <input type="text" name="apellido_mat" id="apellido_mat" value="<?php echo htmlspecialchars($usuario['ApellidoMat']); ?>">
          </div>

          <div class="input-box">
              <label for="correo">Correo Electrónico</label>
              <input type="email" required name="correo" id="correo" value="<?php echo htmlspecialchars($usuario['Correo']); ?>">
          </div>

          <div class="input-box">
              <label for="celular">Celular</label>
              <input type="tel" required name="celular" id="celular" value="<?php echo htmlspecialchars($usuario['Celular']); ?>">
          </div>

          <button type="submit" class="btn">Actualizar</button>
      </form>
  </div>

  <p>¿Deseas volver atrás? <a href="perfilUsuario.php">Haz clic aquí</a></p>
</body>
</html>
