<?php
require_once 'conexion.php';

$idUsuario = $_GET['id'];

$sqlUser = "SELECT u.Nombres, u.ApellidoPat, u.ApellidoMat, u.Correo, u.Celular, g.Nombre as Grado
            FROM TUsuarios u
            LEFT JOIN TGrados g ON u.IdGrado = g.IdGrado
            WHERE IdUsuario = $idUsuario";
$resUser = mysqli_query($link, $sqlUser);
$user = mysqli_fetch_assoc($resUser);
$nombreCompleto = $user['Nombres'] . ' ' . $user['ApellidoPat'] . ' ' . $user['ApellidoMat'];

$sqlFiles = "SELECT RutaDocumentoIdentidad, RutaDocumentoSecundario
             FROM TVerificaciones
             WHERE IdUsuario = $idUsuario";
$resFiles = mysqli_query($link, $sqlFiles);

$archivos = [];
$baseURL = "http://localhost:8080/TecnologiaWebII/ProyectoFinal/"; 

while ($row = mysqli_fetch_assoc($resFiles)) {
    if ($row['RutaDocumentoIdentidad']) {
        $rutaPublica = str_replace("../../", $baseURL, $row['RutaDocumentoIdentidad']);
        $archivos[] = [
            'ruta' => $rutaPublica,
            'tipo' => in_array(strtolower(pathinfo($row['RutaDocumentoIdentidad'], PATHINFO_EXTENSION)), ['png','jpg','jpeg','gif']) ? 'imagen' : 'pdf'
        ];
    }

    if ($row['RutaDocumentoSecundario']) {
        $rutaPublica = str_replace("../../", $baseURL, $row['RutaDocumentoSecundario']);
        $archivos[] = [
            'ruta' => $rutaPublica,
            'tipo' => in_array(strtolower(pathinfo($row['RutaDocumentoSecundario'], PATHINFO_EXTENSION)), ['png','jpg','jpeg','gif']) ? 'imagen' : 'pdf'
        ];
    }
}


echo json_encode([
    'nombreCompleto' => $nombreCompleto,
    'correo' => $user['Correo'],
    'celular' => $user['Celular'],
    'grado' => $user['Grado'],
    'archivos' => $archivos
]);
?>