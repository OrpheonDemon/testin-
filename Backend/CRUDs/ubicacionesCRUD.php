<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $a = $_POST['Area'];
        $p = intval($_POST['Piso']);
        $b = $_POST['Bloque'];
        $r = $_POST['Referencia'];

        if(!empty($_POST['IdUbicacion'])) {
            // Editar
            $id = intval($_POST['IdUbicacion']);
            $sql = "UPDATE TUbicaciones SET 
                        Area='$a', 
                        Piso=$p, 
                        Bloque='$b',
                        Referencia='$r'";

            $sql .= " WHERE IdUbicacion=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TUbicaciones (Area, Piso, Bloque, Referencia) 
                    VALUES ('$a',$p,'$b','$r')";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_ubicacion.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TUbicaciones SET Estado = 0 WHERE IdUbicacion = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_ubicacion.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT SELECT IdUbicacion, Area, Piso, Bloque, Referencia, Estado, FechaCreacion FROM TUbicaciones WHERE IdUbicacion=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
