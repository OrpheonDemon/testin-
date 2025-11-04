<?php

    session_start();
    require_once '../Backend/conexion.php';

    // Aseguramos que el usuario esté logueado
    if (!isset($_SESSION['IdUsuario'])) {
        die("Error: Usuario no autenticado.");
    }

    $idUsuario = intval($_SESSION['IdUsuario']);

    // Consulta con JOIN para obtener el nombre del libro
    $query = "SELECT l.Titulo AS NombreLibro, h.FechaReserva, h.FechaPrestamo, h.FechaDevolucion
            FROM THistorialPrestamos h
            INNER JOIN TLibros l ON h.IdLibro = l.IdLibro
            WHERE h.IdUsuario = ?
            ORDER BY h.FechaPrestamo DESC";

    $stmt = mysqli_prepare($link, $query);

    if (!$stmt) {
        die("Error en la preparación de la consulta: " . mysqli_error($link));
    }

    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Préstamos</title>
  <style>
    table {
      border-collapse: collapse;
      width: 80%;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #333;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #f2f2f2;
    }
    h1 {
      text-align: center;
    }
    a {
      display: block;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <h1>Historial de Préstamos</h1>
  <a href="index_user.php">Volver a la página principal</a>
  <table>
    <thead>
      <tr>
        <th>Libro</th>
        <th>Fecha de Reserva</th>
        <th>Fecha de Préstamo</th>
        <th>Fecha de Devolución</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if ($resultado && mysqli_num_rows($resultado) > 0) {
          while ($fila = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($fila['NombreLibro']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['FechaReserva']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['FechaPrestamo']) . "</td>";
            echo "<td>" . htmlspecialchars($fila['FechaDevolucion']) . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='4'>No tienes historial de préstamos.</td></tr>";
        }

        mysqli_stmt_close($stmt);
        mysqli_close($link);
      ?>
    </tbody>
  </table>
</body>
</html>
