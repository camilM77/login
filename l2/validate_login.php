<?php

// obtener la info de los imput
$email = $_POST["users_email"];
$pass = $_POST["users_pass"];

//  conexion base de datos
$con = mysqli_connect("localhost","root","","formdb");

if(! $con)
{
    die('Connection Failed'.mysql_error());
}

$query = "SELECT * FROM formtbl WHERE username = '$email' and password = '$pass'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_array($result);


/
if($row["username"]==$email && $row["password"]==$pass)
    echo"tu usuario es valido .";
else
    echo"Lo sentimos, sus credenciales no son válidas. Vuelva a intentarlo.";
?>