<?php
	
	session_start();
 
	//detalle base de datos
	require_once('connection.php');
 
	//almacenar errores de validación
	$errmsg_arr = array();
 
	//limite de validacion
	$errflag = false;
 
	//limpiar valores resividos , evitando insert
	function clean($str) {
		echo "str: ".$str;
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysqli_real_escape_string($str);
	}
 
	//devolver valores post
	$username = $_POST['username'];
	$password = $_POST['password'];
 
	//validar imputs
	if($username == '') {
		$errmsg_arr[] = 'Username missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
 
	//despues de validar , a inicio de sesión
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: index.php");
		exit();
	}
 
	//Crear query
	$qry="SELECT * FROM formtbl WHERE username='$username' AND password='$password'";
	$result=mysqli_query($bd, $qry);
 
	//verificacion del select
	if($result) {
		if(mysqli_num_rows($result) > 0) {
			//si inicio es exitoso
			session_regenerate_id();
			$member = mysqli_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $member['mem_id'];
			$_SESSION['SESS_FIRST_NAME'] = $member['username'];
			$_SESSION['SESS_LAST_NAME'] = $member['password'];
			session_write_close();
			header("location: home.php");
			exit();
		}else {
			//si inicio falla
			$errmsg_arr[] = 'nombre de usuario y contraseña no encontrados';
			$errflag = true;
			if($errflag) {
				$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
				session_write_close();
				header("location: index.php");
				exit();
			}
		}
	}else {
		die("La consulta falló");
	}
?>