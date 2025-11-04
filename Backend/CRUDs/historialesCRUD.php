<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch ($accion) {

    case 'guardar':
        $IdLibro = intval($_POST['IdLibro'] ?? 0);
        $IdUsuario = intval($_POST['IdUsuario'] ?? 0);

        if(!empty($_POST['IdHistorial'])) {
            // Editar
            $id = intval($_POST['IdHistorial']);
            $sql = "UPDATE THistorialPrestamos SET 
                        IdLibro=$IdLibro, 
                        IdUsuario=$IdUsuario
                    WHERE IdHistorial=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO THistorialPrestamos (IdLibro, IdUsuario) 
                    VALUES ($IdLibro, $IdUsuario)";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_historial.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE THistorialPrestamos SET Estado = 4 WHERE IdHistorial = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_historial.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdHistorial, IdLibro, IdUsuario, FechaReserva, FechaPrestamo, FechaDevolucion, Estado, Observaciones FROM THistorialPrestamos WHERE IdHistorial=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;
    
    case 'aceptar':
        $id = intval($_GET['id'] ?? 0);

        $res = mysqli_query($link, "SELECT IdLibro, IdUsuario FROM THistorialPrestamos WHERE IdHistorial=$id");
        $row = mysqli_fetch_assoc($res);
        $idLibro = $row['IdLibro'];
        $idUsuario = $row['IdUsuario'];

        mysqli_query($link, "UPDATE TLibros SET Stock = Stock - 1 WHERE IdLibro=$idLibro AND Stock > 0");

        $sql = "UPDATE THistorialPrestamos SET Estado=1, FechaPrestamo=NOW() WHERE IdHistorial=$id";
        mysqli_query($link, $sql);
        
        $mensaje = 'Tu solicitud de prestamo ha sido aceptada';
        $sqlNoti = "INSERT INTO TNotificaciones (IdUsuario, Mensaje, Estado, FechaEnvio)
                    VALUES ($idUsuario, '$mensaje', 0, NOW())";
        mysqli_query($link, $sqlNoti);

        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_historial.php");
        exit;
        break;
    
    case 'devuelto':
        $id = intval($_GET['id'] ?? 0);

        $res = mysqli_query($link, "SELECT IdLibro, IdUsuario FROM THistorialPrestamos WHERE IdHistorial=$id");
        $row = mysqli_fetch_assoc($res);
        $idLibro = $row['IdLibro'];
        $idUsuario = $row['IdUsuario'];

        mysqli_query($link, "UPDATE TLibros SET Stock = Stock + 1 WHERE IdLibro=$idLibro");

        $sql = "UPDATE THistorialPrestamos SET Estado=2, FechaDevolucion=NOW() WHERE IdHistorial=$id";
        mysqli_query($link, $sql);

        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_historial.php");
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>