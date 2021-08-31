<?php  
session_start();  
  
if(!$_SESSION['email'])  
{  
  
    header("Location: login.php");//redireccionar  
}  
  
?>  
  
<html>  
<head>  
  
    <title>  
        Registrarse 
    </title>  
</head>  
  
<body>  
<h1>Bienvenido</h1><br>  
<?php  
echo $_SESSION['email'];  
?>  
  
  
<h1><a href="logout.php">Cerrar sesion</a> </h1>  
  
  
</body>  
  
</html>