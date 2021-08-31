<?php
	
	
	session_start();
	//comprobar si el id de la seccion esta 
	if(!isset($_SESSION['SESS_MEMBER_ID']) || (trim($_SESSION['SESS_MEMBER_ID']) == '')) {
		header("location: index.php");
		exit();
	}
?>