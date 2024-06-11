<?php
/*Datos de conexion a la base de datos*/
// $db_host = "localhost";
// $db_user = "root";
// $db_pass = "";
// $db_name = "cajuca_database";

$db_host = "cajuca-server.mysql.database.azure.com";
$db_user = "jiudywohcu";
$db_pass = "C@juca4231";
$db_name = "cajuca_database";

$conexion = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
 
if(mysqli_connect_errno()){
	echo 'No se pudo conectar a la base de datos : '.mysqli_connect_error();
}
?>