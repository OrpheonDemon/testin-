<?php
require_once '../conexion.php';

$accion = $_REQUEST['accion'] ?? '';

switch($accion){

    // INSERTAR o EDITAR
    case 'guardar':
        $n = $_POST['Nombres'];
        $ap = $_POST['ApellidoPat'];
        $am = $_POST['ApellidoMat'];
        $c = $_POST['Correo'];
        $cel = $_POST['Celular'];
        $rol = intval($_POST['IdRol'] ?? 0);
        $grado = intval($_POST['IdGrado'] ?? 0);

        $pass = !empty($_POST['Password']) ? password_hash($_POST['Password'], PASSWORD_DEFAULT) : '';

        if(!empty($_POST['IdUsuario'])) {
            // Editar
            $id = intval($_POST['IdUsuario']);
            $sql = "UPDATE TUsuarios SET 
                        Nombres='$n', 
                        ApellidoPat='$ap', 
                        ApellidoMat='$am',
                        Correo='$c', 
                        Celular='$cel', 
                        IdRol=$rol, 
                        IdGrado=$grado";

            if($pass){
                $sql .= ", Password='$pass'";
            }

            $sql .= " WHERE IdUsuario=$id";

        } else {
            // Insertar
            $sql = "INSERT INTO TUsuarios (Nombres, ApellidoPat, ApellidoMat, Correo, Celular, Password, IdRol, IdGrado) 
                    VALUES ('$n','$ap','$am','$c','$cel','$pass',$rol,$grado)";
        }

        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_usuario.php");
        exit;
        break;

    case 'borrar':
        $id = intval($_GET['id'] ?? 0);
        $sql = "UPDATE TUsuarios SET Estado = 2 WHERE IdUsuario = $id";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/crud_usuario.php");
        exit;
        break;

    case 'get':
        $id = intval($_GET['id'] ?? 0);
        $res = mysqli_query($link, "SELECT IdUsuario, Nombres, ApellidoPat, ApellidoMat, Correo, Celular, IdRol, IdGrado FROM TUsuarios WHERE IdUsuario=$id");
        echo json_encode(mysqli_fetch_assoc($res));
        exit;
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        exit;
        break;
}
?>
