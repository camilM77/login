<?php
  include('dbconfig.php');
  
   $fullName='';
   $pass ='';
   $cpass ='';
   $nic ='';
   $email ='';
   
   if(isset($_POST['fullName'])){
        $fullName=$_POST['fullName'];
   }
   
   if(isset($_POST['email'])){
        $email=$_POST['email'];
   }
   
   if(isset($_POST['nicNumber'])){
        $nic=$_POST['nicNumber'];
   }
   
   if(isset($_POST['password'])){
        $pass=$_POST['password'];
   }
   
   if(isset($_POST['conPassword'])){
        $cpass=$_POST['conPassword'];
   }
   
   $enc_pass = md5($pass);
   if(isset($_POST['submit'])){
   if($fullName != ""){
      if($email != ""){
        if($nic != ""){
          if($pass != "" && $cpass != ""){
            if($pass == $cpass){
                $token = md5(rand(59999, 99999));
                              
                $sql = "Insert Into user(Full_Name,NIC_Number,Email,Password,email_verifyed,Token) VALUES ('$fullName','$nic','$email','$enc_pass',0,'$token')";
                mysql_query($sql) or die(mysql_error());

                //send verification email
                $link =$my_url.'/verify.php?id=user&&token='.$token.'&&mail='.$email;
                
                $message =  '<p>Welcome </p>
                                <p> Click here <br> <a href="'.$link.'" >'.$link.'</a> <br> verificar correo y cuenta activa</p>
                                
                                <p> Thank you,<br><br>
                                    Administrador <br> .</p>              
                
                            ';
                
                echo $message;
                
                //echo 'Successfuly saved data.';
              
            }else{
              echo 'las contraseñas no coinciden.';
            }
          }else{
            echo 'porfavor ingrese las contraseñas.';
          }
        }else{
          echo 'Porfavor ingrese su numero.';
        }
      }else{
        echo 'Porfavor ingrese su correo.';
      }
   }else{
      echo 'Please ingrese su nombre.';
   }
   }
?>


<div class="reg-container">
	<h1>Register New User</h1>
        <form action="" method="post" id="regUser">
		<label for="fullName">Full Name</label>
		<input type="text" name="fullName" class="form-control" placeholder="Enter Your Name" required value="<?php echo $fullName ?>"/>
		
		<label for="email">Email Address</label>
		<input type="email" name="email" class="form-control" placeholder="Email Address" required value="<?php echo $email ?>"/>

		<label for="nicNumber">NIC Number</label>
		<input type="text" name="nicNumber" class="form-control" placeholder="XXXXXXXXXV or XXXXXXXXXXXX" required value="<?php echo $nic ?>"/>

		<label for="password">Password</label>
		<input type="password" name="password" class="form-control" required/>

		<label for="conPassword">Confirm Password</label>
		<input type="password" name="conPassword" class="form-control" required/>
		<br>
		<input type="submit" name="submit" value="Register" class="btn btn-primary" />
	</form>
</div>