<?php

function conectarDB() : mysqli {
    $db = mysqli_connect('localhost', 'root', 'agregapasswordBD', 'bienesraices_crud');
    if(!$db){
        echo "Conexión exitosa a la base de datos";
        exit;
    }
    return $db;
}
