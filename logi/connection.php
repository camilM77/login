<?php
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_database = "formdb";
$prefix = "";
$bd = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password) or die("No se pudo conectar la base de datos");
mysqli_select_db($bd, $mysql_database) or die("No se pudo seleccionar la base de datos");
?>