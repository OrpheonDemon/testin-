<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch ($accion) {

    case 'guardar':
        $titulo = $_POST['Titulo'];
        $idAutor = intval($_POST['IdAutor'] ?? 0);
        $idEditorial = intval($_POST['IdEditorial'] ?? 0);
        $idCategoria = intval($_POST['IdCategoria'] ?? 0);
        $tipo = $_POST['TipoLibro'] ?? '';

        $stock = ($tipo === 'Virtual') ? 1 : intval($_POST['Stock'] ?? 1);
        $rutaDoc = null;
        $idUbicacion = null;

        // Manejo según tipo
        if ($tipo === 'Virtual') {
            if (isset($_FILES['RutaDoc']) && $_FILES['RutaDoc']['error'] === 0) {
                $nombreArchivo = time() . '_' . basename($_FILES['RutaDoc']['name']);
                $rutaDestino = "../../CarpetaLibros/" . $nombreArchivo;
                move_uploaded_file($_FILES['RutaDoc']['tmp_name'], $rutaDestino);
                $rutaDoc = "CarpetaLibros/" . $nombreArchivo;
            } elseif(!empty($_POST['RutaDocActual'])) {
                $rutaDoc = $_POST['RutaDocActual'];
            }
        } elseif ($tipo === 'Fisico') {
            $idUbicacion = intval($_POST['IdUbicacion'] ?? 0);
        }

        if (!empty($_POST['IdLibro'])) {
            // Editar
            $id = intval($_POST['IdLibro']);
            $sql = "UPDATE TLibros SET 
                    Titulo='$titulo',
                    IdAutor=$idAutor,
                    IdEditorial=$idEditorial,
                    IdCategoria=$idCategoria,
                    Stock=$stock,
                    RutaDoc=" . ($rutaDoc ? "'$rutaDoc'" : "NULL") . ",
                    IdUbicacion=" . ($idUbicacion ? $idUbicacion : "NULL") . "
                    WHERE IdLibro=$id";
        } else {
            // Insertar
            $sql = "INSERT INTO TLibros (Titulo, IdAutor, IdEditorial, IdCategoria, RutaDoc, IdUbicacion, Stock) 
                VALUES ('$titulo', $idAutor, $idEditorial, $idCategoria, "
                . ($rutaDoc ? "'$rutaDoc'" : "NULL") . ", "
                . ($idUbicacion ? $idUbicacion : "NULL") . ", $stock)";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_libro.php");
        exit;
        break;


    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TLibros SET Estado = 0 WHERE IdLibro = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_libro.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdLibro, Titulo, IdAutor, IdEditorial, IdCategoria, RutaDoc, IdUbicacion, Stock, Estado FROM TLibros WHERE IdLibro=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>