<?php
    require_once 'conexion.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'C:/xampp/htdocs/TecnologiaWebII/ProyectoFinal/vendor/autoload.php';

    $idUsuario = $_POST['IdUsuario'];

    if(isset($_POST['aprobar'])){
        if (!empty($_POST['rol_usuario'])) {
            $rol = intval($_POST['rol_usuario']);
            $sql = "UPDATE TUsuarios SET Estado = 1, IdRol = $rol WHERE IdUsuario = $idUsuario";
            mysqli_query($link, $sql);

            $res = mysqli_query($link, "SELECT Correo, Nombres FROM TUsuarios WHERE IdUsuario = $idUsuario");
            $user = mysqli_fetch_assoc($res);

            $to = $user['Correo'];
            $nombre = $user['Nombres'];

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'bibliohospi1999@gmail.com';
                $mail->Password = 'pgfk gzzw izxr borc'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('bibliohospi1999@gmail.com', 'Biblioteca del Hsopital');
                $mail->addAddress($to, $nombre);

                $mail->isHTML(true);
                $mail->Subject = 'Usuario Habilitado en la Biblioteca del Hospital';
                $mail->Body = "Hola <b>$nombre</b>,<br><br>
                          Tu cuenta ha sido <b>habilitada</b> por el administrador. 
                          Ahora puedes iniciar sesión y usar el sistema.<br><br>
                          Saludos,<br>Equipo de Biblioteca del Hospital";
                $mail->AltBody="Hola $nombre,\n\nTu cuenta ha sido habilitada por el administrador. Ahora puedes iniciar sesión.\n\nSaludos,\nEquipo de Biblioteca del Hospital";

                $mail->send();

            } catch (Exception $e){
                echo "No se pudo enviar el correo. Error: {$mail->ErrorInfo}";
            }

            header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/comprobar_user.php?msg=aprobado");
            exit;
        } else {
            header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/comprobar_user.php?error=rol"); 
            exit;
        }
    } elseif(isset($_POST['cancelar'])){
        $sql = "UPDATE TUsuarios SET Estado = 2 WHERE IdUsuario = $idUsuario";
        mysqli_query($link, $sql);
        header("Location: http://localhost:8080/TecnologiaWebII/ProyectoFinal/Fronted/comprobar_user.php?msg=cancelado");
        exit;
    }
?>
