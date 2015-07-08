<?php
	include('../../start.php');
	if (!isset($_SESSION)) session_start();
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	echo $fecha_hora = date("Y-m-d H:i:s");
	$ipvisitante = $_SERVER["REMOTE_ADDR"];
	$ref_accion=$_POST['REFLOG'];
	$sql = sprintf("INSERT INTO log_sistema (log_fecha,log_descripcion, log_usuario, log_ip) VALUES (%s,%s,%s,%s)",
					   GetSQLValueString($fecha_hora, "date"),
					   GetSQLValueString($ref_accion, "text"),
					   GetSQLValueString($persona ['per_nombre'], "text"),
					   GetSQLValueString($ipvisitante, "text"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	//echo $fecha_hora.' / '.$ref_accion.' / '.$persona.' / '.$ipvisitante;
?>
