<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $n = $_POST['Nombre'];
        $d = $_POST['Descripcion'];

        if(!empty($_POST['IdGrado'])) {
            // Editar
            $id = intval($_POST['IdGrado']);
            $sql = "UPDATE TGrados SET 
                        Nombre='$n', 
                        Descripcion='$d'";

            $sql .= " WHERE IdGrado=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TGrados (Nombre, Descripcion) 
                    VALUES ('$n','$d')";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_grado.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TGrados SET Estado = 0 WHERE IdGrado = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_grado.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdGrado, Nombre, Descripcion, Estado, FechaCreacion FROM TGrados WHERE IdGrado=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
