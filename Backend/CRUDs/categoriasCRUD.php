<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $n = $_POST['Nombre'];
        $d = $_POST['Descripcion'];

        if(!empty($_POST['IdCategoria'])) {
            // Editar
            $id = intval($_POST['IdCategoria']);
            $sql = "UPDATE TCategorias SET 
                        Nombre='$n', 
                        Descripcion='$d'";

            $sql .= " WHERE IdCategoria=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TCategorias (Nombre, Descripcion) 
                    VALUES ('$n','$d')";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_categoria.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TCategorias SET Estado = 0 WHERE IdCategoria = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_categoria.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdCategoria, Nombre, Descripcion, Estado, FechaCreacion FROM TCategorias WHERE IdCategoria=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
