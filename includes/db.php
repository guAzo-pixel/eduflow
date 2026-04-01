<?php

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

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

