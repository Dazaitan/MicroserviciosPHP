<?php
    //Definimos las constantes de conexion.
    define('DB_HOST','localhost');
    define('DB_USER','root');
    define('DB_NAME','restaurante2022');
    define('DB_PASS','');
    define('DB_CHARSET','utf-8');
    // Creo el objeto de la conexion
    $ap = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if($ap->connect_errno) {
      echo "Fallo al conectarse con la base de datos :".$ap->connect_error;
    }else {
      //$ap->set_charset(DB_CHARSET);
    }
 ?>
