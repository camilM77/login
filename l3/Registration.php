<html>  
<head lang="en">  
    <meta charset="UTF-8">  
    <link type="text/css" rel="stylesheet" href="bootstrap-3.2.0-dist\css\bootstrap.css">  
    <title>Registration</title>  
</head>  
<style>  
    .login-panel {  
        margin-top: 150px;  
  
</style>  
<body>  
  
<div class="container"><!>  
    <div class="row"><!>  
        <div class="col-md-4 col-md-offset-4"><!>  
            <div class="login-panel panel panel-success">  
                <div class="panel-heading">  
                    <h3 class="panel-title">Registration</h3>  
                </div>  
                <div class="panel-body">  
                    <form role="form" method="post" action="registration.php">  
                        <fieldset>  
                            <div class="form-group">  
                                <input class="form-control" placeholder="Username" name="name" type="text" autofocus>  
                            </div>  
  
                            <div class="form-group">  
                                <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus>  
                            </div>  
                            <div class="form-group">  
                                <input class="form-control" placeholder="Password" name="pass" type="password" value="">  
                            </div>  
  
  
                            <input class="btn btn-lg btn-success btn-block" type="submit" value="register" name="register" >  
  
                        </fieldset>  
                    </form>  
                    <center><b>Already registered ?</b> <br></b><a href="login.php">Login here</a></center><!--for centered text-->  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  
  
</body>  
  
</html>  
  
<?php  
  
include("database/db_conection.php");//usar el archivo de la conexion  
if(isset($_POST['register']))  
{  
    $user_name=$_POST['name'];//obtener imputs
    $user_pass=$_POST['pass'];//
    $user_email=$_POST['email'];//
  
  
    if($user_name=='')  
    {  
        //verificar entrada con  alertas de javascrip 
        echo"<script>alert('Porfavor ingrese su nombre')</script>";  
exit();// si este no se usa se sale 
    }  
  
    if($user_pass=='')  
    {  
        echo"<script>alert('Porfavor ingrese su contraseña')</script>";  
exit();  
    }  
  
    if($user_email=='')  
    {  
        echo"<script>alert('Porfavor ingrese su correo')</script>";  
    exit();  
    }  
//verifica si el usuario ya existe  
    $check_email_query="select * from users WHERE user_email='$user_email'";  
    $run_query=mysqli_query($dbcon,$check_email_query);  
  
    if(mysqli_num_rows($run_query)>0)  
    {  
echo "<script>alert('El correo $user_email ya existe en nuestra base de datos, por favor use uno nuevo !')</script>";  
exit();  
    }  
//hacer los insert  
    $insert_user="insert into users (user_name,user_pass,user_email) VALUE ('$user_name','$user_pass','$user_email')";  
    if(mysqli_query($dbcon,$insert_user))  
    {  
        echo"<script>window.open('welcome.php','_self')</script>";  
    }  
  
  
  
}  
  
?>  