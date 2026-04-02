<?php
session_start();

/*vacia los datos*/
session_unset();

/*destrulle los datos del server*/
session_destroy();

header("Location: index.php");

?>