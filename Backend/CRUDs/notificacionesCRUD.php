<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $usuario = intval($_POST['IdUsuario'] ?? 0);
        $m = $_POST['Mensaje'];

        if(!empty($_POST['IdNotificacion'])) {
            // Editar
            $id = intval($_POST['IdNotificacion']);
            $sql = "UPDATE TNotificaciones SET 
                        IdUsuario=$usuario, 
                        Mensaje='$m'";

            $sql .= " WHERE IdNotificacion=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TNotificaciones (IdUsuario, Mensaje) 
                    VALUES ($usuario,'$m')";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_notificacion.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TNotificaciones SET Estado = 2 WHERE IdNotificacion = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_notificacion.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdNotificacion, IdUsuario, Mensaje, Estado, FechaEnvio FROM TNotificaciones WHERE IdNotificacion=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
