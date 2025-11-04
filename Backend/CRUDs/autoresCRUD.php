<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $n = $_POST['Nombre'];

        if(!empty($_POST['IdAutor'])) {
            // Editar
            $id = intval($_POST['IdAutor']);
            $sql = "UPDATE TAutores SET 
                        Nombre='$n'";

            $sql .= " WHERE IdAutor=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TAutores (Nombre) 
                    VALUES ('$n')";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_autor.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TAutores SET Estado = 0 WHERE IdAutor = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_autor.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdAutor, Nombre, Estado, FechaCreacion FROM TAutores WHERE IdAutor=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
