<?php
session_start();


if(isset($_POST['submit']))
{
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$query = "SELECT username, password FROM admin WHERE username='$username' 
AND passcode='$password'";

$result = mysqli_query($mysqli,$query)or die(mysqli_error());
$num_row = mysqli_num_rows($result);
$row=mysqli_fetch_array($result);
if( $num_row ==1 )
     {
 $_SESSION['userid']=$row['userid'];
 header("Location: admin.php");
 echo 'bienvenido';
 exit;
  }
  else
     {
 echo 'ups, ocurrio un error';
  }
 }
?>

<h2><br> Administracion<br>
</h2> <p>inicie acontinuación</p> 
<form   action="login.php" method="post"> 
<b>User Name:</b> 
<br> <input type="text" size="20" name="username">
<br> <br> <b>Password:</b> 
<br><input type="password" size="20" name="password"> <br>
<br> <input type="submit" value="login"> </form>