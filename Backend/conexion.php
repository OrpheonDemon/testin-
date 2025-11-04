<?php
    $MyHost = "localhost";
    $MyLogin = "root";
    $MyPassword = "";
    $MyDatabase = "bibliotecahospitalbd";

    global $MyHost, $MyLogin, $MyPassword, $MyDatabase;
    
    if (!($link = mysqli_connect($MyHost,$MyLogin,$MyPassword)))
    {
        echo "Error al conectar a la base de datos.<br>";
    }

    mysqli_set_charset($link, "utf8");

    if(!mysqli_select_db($link, $MyDatabase))
    {
        echo "Error seleccionado la base de datos.<br>";
    }
?>