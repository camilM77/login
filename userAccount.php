<?php

session_start();
//cargar e inicializar la clase de usuario
include 'user.php';
$user = new User();
if(isset($_POST['signupSubmit'])){
	//comprobar si los datos del usuario están vacíos
    if(!empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['email']) && !empty($_POST['phone']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])){
		//contraseña y confirmar la comparación de contraseña
        if($_POST['password'] !== $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Confirme que la contraseña debe coincidir con la contraseña.'; 
        }else{
			//comprobar si el usuario existe en la base de datos
            $prevCon['where'] = array('email'=>$_POST['email']);
            $prevCon['return_type'] = 'count';
            $prevUser = $user->getRows($prevCon);
            if($prevUser > 0){
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'El correo electrónico ya existe, utilice otro correo electrónico.';
            }else{
				//insertar datos de usuario en la base de datos
                $userData = array(
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'email' => $_POST['email'],
                    'password' => md5($_POST['password']),
                    'phone' => $_POST['phone']
                );
                $insert = $user->insert($userData);
				// establecer  el estado basado en la inserción de datos
                if($insert){
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'Te has registrado correctamente, inicia sesión con tus credenciales.';
                }else{
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg'] = 'Se produjo algún problema, por favor intente nuevamente.';
                }
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Todos los campos son obligatorios, complete todos los campos.'; 
    }
	// almacenar el estado de registro en la sesión
    $_SESSION['sessData'] = $sessData;
    $redirectURL = ($sessData['status']['type'] == 'success')?'index.php':'registration.php';
	//redirigir a la página de inicio / registro
    header("Location:".$redirectURL);
}elseif(isset($_POST['loginSubmit'])){
	//compruebe si los datos de inicio de sesión están vacíos
    if(!empty($_POST['email']) && !empty($_POST['password'])){
		//obtener datos de usuario de la clase de usuario
        $conditions['where'] = array(
            'email' => $_POST['email'],
            'password' => md5($_POST['password']),
            'status' => '1'
        );
        $conditions['return_type'] = 'single';
        $userData = $user->getRows($conditions);
		//establecer los datos y el estado del usuario en función de las credenciales de inicio de sesión
        if($userData){
            $sessData['userLoggedIn'] = TRUE;
            $sessData['userID'] = $userData['id'];
            $sessData['status']['type'] = 'success';
            $sessData['status']['msg'] = 'Bienvenid@ '.$userData['first_name'].'!';
        }else{
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Correo electrónico o contraseña incorrectos, intente nuevamente.'; 
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Ingrese correo electrónico y contraseña.'; 
    }
	//almacenar el estado de inicio de sesión en la sesión
    $_SESSION['sessData'] = $sessData;
	//redirigir
    header("Location:index.php");
}elseif(isset($_POST['forgotSubmit'])){
	//comprobar si el correo electrónico está vacío
    if(!empty($_POST['email'])){
		//comprobar si el usuario existe en la base de datos
		$prevCon['where'] = array('email'=>$_POST['email']);
		$prevCon['return_type'] = 'count';
		$prevUser = $user->getRows($prevCon);
		if($prevUser > 0){
			//generar cadena unica 
			$uniqidStr = md5(uniqid(mt_rand()));;
			
			//actualizar datos con contraseña olvidada
			$conditions = array(
				'email' => $_POST['email']
			);
			$data = array(
				'forgot_pass_identity' => $uniqidStr
			);
			$update = $user->update($data, $conditions);
			
			if($update){
				$resetPassLink = 'http://localhost/olvidopass/resetPassword.php?fp_code='.$uniqidStr;
				
				// detalles de usuario
				$con['where'] = array('email'=>$_POST['email']);
				$con['return_type'] = 'single';
				$userDetails = $user->getRows($con);
				
				//enviar correo electrónico de restablecimiento de contraseña
				$to = $userDetails['email'];
				$subject = "Solicitud de Cambio de Contraseña";
				$mailContent = 'Estimad@ '.$userDetails['first_name'].', 
				<br/><br/>Recientemente se envió una solicitud para restablecer una contraseña para su cuenta. Si esto fue un error, simplemente ignore este correo electrónico y no pasará nada.
				<br/>Para restablecer su contraseña, visite el siguiente enlace: <a href="'.$resetPassLink.'">'.$resetPassLink.'</a>
				<br/><br/>Saludos,
                
                <br/>';                ;
                
				//establecer encabezado de tipo de contenido para enviar correo electrónico html
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
				//additional headers
				$headers .= 'From: OUTER>' . "\r\n";
				//send email
				mail($to,$subject,$mailContent,$headers);
				
				$sessData['status']['type'] = 'success';
				$sessData['status']['msg'] = 'Verifique su correo electrónico, hemos enviado un enlace para restablecer la contraseña a su correo electrónico registrado.';
			}else{
				$sessData['status']['type'] = 'error';
				$sessData['status']['msg'] = 'Se produjo algún problema, por favor intente nuevamente.';
			}
		}else{
			$sessData['status']['type'] = 'error';
			$sessData['status']['msg'] = 'El correo electrónico dado no coincide con ninguna cuenta.'; 
		}
		
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Ingrese el correo electrónico para crear una nueva contraseña para su cuenta.'; 
    }
	//almacenar el estado de restablecimiento de la contraseña en la sesión
    $_SESSION['sessData'] = $sessData;
	//redirigir a la página de contraseña olvidada
    header("Location:forgotPassword.php");
}elseif(isset($_POST['resetSubmit'])){
	$fp_code = '';
	if(!empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['fp_code'])){
		$fp_code = $_POST['fp_code'];
		//comparacion de contraseñas
        if($_POST['password'] !== $_POST['confirm_password']){
            $sessData['status']['type'] = 'error';
            $sessData['status']['msg'] = 'Confirme que la contraseña debe coincidir con la contraseña.'; 
        }else{
			//comprobar si el código de identidad existe en la base de datos
            $prevCon['where'] = array('forgot_pass_identity' => $fp_code);
            $prevCon['return_type'] = 'single';
            $prevUser = $user->getRows($prevCon);
            if(!empty($prevUser)){
				//actualizar datos con nueva contraseña
				$conditions = array(
					'forgot_pass_identity' => $fp_code
				);
				$data = array(
					'password' => md5($_POST['password'])
				);
				$update = $user->update($data, $conditions);
				if($update){
					$sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'La contraseña de su cuenta se ha restablecido correctamente. Inicia sesión con tu nueva contraseña.';
				}else{
					$sessData['status']['type'] = 'error';
					$sessData['status']['msg'] = 'Se produjo algún problema, por favor intente nuevamente.';
				}
            }else{
                $sessData['status']['type'] = 'error';
                $sessData['status']['msg'] = 'No tiene autorización para restablecer la nueva contraseña de esta cuenta.';
            }
        }
    }else{
        $sessData['status']['type'] = 'error';
        $sessData['status']['msg'] = 'Todos los campos son obligatorios, complete todos los campos.'; 
    }
	//almacenar el estado de restablecimiento de la contraseña en la sesión
    $_SESSION['sessData'] = $sessData;
    $redirectURL = ($sessData['status']['type'] == 'success')?'index.php':'resetPassword.php?fp_code='.$fp_code;
	//rederigir
    header("Location:".$redirectURL);
}elseif(!empty($_REQUEST['logoutSubmit'])){
	//
    unset($_SESSION['sessData']);
    session_destroy();
	//almacenar el estado de cierre de sesión 
    $sessData['status']['type'] = 'success';
    $sessData['status']['msg'] = 'Has cerrado la sesión correctamente desde tu cuenta.';
    $_SESSION['sessData'] = $sessData;
	//rederigir
    header("Location:index.php");
}else{
	
    header("Location:index.php");
}