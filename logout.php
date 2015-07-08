<?php 
	include('start.php');
	$_SESSION['nombre_usr'] = NULL;
	session_destroy();
	header('Location: '.$RUTA.'index.php');
?>



