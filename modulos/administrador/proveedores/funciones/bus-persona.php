<?php
	if (!isset($_SESSION)) session_start();
	include('../../../../start.php');
	fnc_autentificacion();
	
	$per_id = $_POST['per_id'];
	$per_documento = $_POST['per_documento'];
	$sql = sprintf("SELECT * FROM persona WHERE per_documento=%s",
	GetSQLValueString($per_documento,'text'));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);
	if($tot_rows!=0){
	//echo ($row);
	echo json_encode($row);
	}
	
?>