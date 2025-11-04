<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $n = $_POST['Nombre'];

        if(!empty($_POST['IdEditorial'])) {
            // Editar
            $id = intval($_POST['IdEditorial']);
            $sql = "UPDATE TEditoriales SET 
                        Nombre='$n'";

            $sql .= " WHERE IdEditorial=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TEditoriales (Nombre) 
                    VALUES ('$n')";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_editorial.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TEditoriales SET Estado = 0 WHERE IdEditorial = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_editorial.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdEditorial, Nombre, Estado, FechaCreacion FROM TEditoriales WHERE IdEditorial=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
