<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	
	$sql = "SELECT * FROM persona INNER JOIN empleado ON persona.per_id = empleado.per_id WHERE empleado.emp_id NOT IN (SELECT emp_id FROM usuarios) AND empleado.emp_eliminado = 'N' AND persona.per_nombre LIKE '%".$_REQUEST['term']."%'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		
	while($rows = mysql_fetch_array($query)){
		$datos[] = array('value' => $rows['per_id'], 'label' => $rows['per_nombre']);
	}	
	
	echo json_encode($datos);
?>