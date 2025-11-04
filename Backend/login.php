<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Correo = mysqli_real_escape_string($link, $_POST['Correo']);
    $password = $_POST['password'];

    $sql = "SELECT IdUsuario, Correo, Password, IdRol, Estado FROM TUsuarios WHERE Correo='$Correo' AND Estado=1 LIMIT 1";
    $res = mysqli_query($link, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $user = mysqli_fetch_assoc($res);

        if (password_verify($password, $user['Password'])) {

            $_SESSION['IdUsuario'] = $user['IdUsuario'];
            $_SESSION['IdRol'] = $user['IdRol'];
            $_SESSION['Correo'] = $user['Correo'];

            if (in_array($user['IdRol'], [1,2])) {
                header('Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/index_admin.php');
                exit;
            } elseif (in_array($user['IdRol'], [3,4])) {
                header('Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/index_user.php');
                exit;
            } else {
                echo "Rol no válido";
            }

        } else {
            echo "Contraseña incorrecta";
        }
    } else {
        echo "Usuario no encontrado o inhabilitado";
    }
}
?>
