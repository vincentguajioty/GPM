<?php
require_once '../config/bdd.php';

writeInLogs("Début de l'installation de la version 1", '1', NULL);
$query = $db->query(file_get_contents ("update1.sql"));
writeInLogs("Fin de l'installation de la version 1", '1', NULL);
echo "<script type='text/javascript'>document.location.replace('../login.php');</script>";


?>