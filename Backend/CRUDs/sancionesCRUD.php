<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion) {
    case 'guardar':
        $IdHistorial = intval($_POST['IdHistorial']);
        $TipoSancion = $_POST['TipoSancion'];
        $Motivo = $_POST['Motivo'];
        $FechaInicio = $_POST['FechaInicio'];
        $FechaFin = $_POST['FechaFin'];

        $sql = "INSERT INTO TSanciones (IdHistorial, TipoSancion, Motivo, FechaInicio, FechaFin)
                VALUES ($IdHistorial, '$TipoSancion', '$Motivo', '$FechaInicio', '$FechaFin')";
        mysqli_query($link, $sql);

        mysqli_query($link, "UPDATE THistorialPrestamos SET Estado=3 WHERE IdHistorial=$IdHistorial");

        $res = mysqli_query($link, "SELECT IdUsuario FROM THistorialPrestamos WHERE IdHistorial=$IdHistorial");
        $row = mysqli_fetch_assoc($res);
        $idUsuario = $row['IdUsuario'];

        $mensaje = "Has recibido una sancion desde $FechaInicio hasta $FechaFin: $TipoSancion. Motivo: $Motivo";
        $sqlNoti = "INSERT INTO TNotificaciones (IdUsuario, Mensaje, Estado, FechaEnvio)
                    VALUES ($idUsuario, '$mensaje', 0, NOW())";
        mysqli_query($link, $sqlNoti);

        header("Location: http://localhost:8080:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_historial.php");
        exit;
        break;

    case 'editar':
        $IdSancion = intval($_POST['IdSancion']);
        $TipoSancion = $_POST['TipoSancion'];
        $Motivo = $_POST['Motivo'];
        $FechaInicio = $_POST['FechaInicio'];
        $FechaFin = $_POST['FechaFin'];

        $sql = "UPDATE TSanciones SET 
                TipoSancion='$TipoSancion', 
                Motivo='$Motivo', 
                FechaInicio='$FechaInicio', 
                FechaFin='$FechaFin' 
                WHERE IdSancion=$IdSancion";

        mysqli_query($link, $sql);

        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_sancion.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TSanciones SET Estado = 0 WHERE IdSancion = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_sancion.php");
        exit;
        break;
    
    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdSancion, IdHistorial, TipoSancion, Motivo, FechaInicio, FechaFin, Estado FROM TSanciones WHERE IdSancion=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
