<?php

$host = 'smr_db';
$dbname = 'centro_educativo';
$username = 'admin_centro';
$password = 'password_segura';

try {
    /* Le decimos a PDO (embajador) que base de datos utilizamos, chanset es para ñ y tildes */
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    /* Creamos al embajador */
    $pdo = new PDO($dsn, $username, $password);

    /* Evitamos que codigo sql mal escrito se mande a la base de datos (lo interceptara catch) */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e){
    /*die mata el proceso, $e capta el error */
    die("Error de la conexión: " . $e->getMessage());
}

?>

