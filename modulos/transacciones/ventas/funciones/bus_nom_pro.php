<?php
	
	if (!isset($_SESSION)) session_start();
	include('../../../../start.php');
	fnc_autentificacion();
	
	$pro_codigo = $_POST['pro_codigo'];
	
	$sql = sprintf("SELECT pro_nombre FROM producto WHERE pro_codigo=%s",
	GetSQLValueString($pro_codigo,'text'));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$pro_nombre = $row["pro_nombre"]; 
	echo $pro_nombre;
	
?>






