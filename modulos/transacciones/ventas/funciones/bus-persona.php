<?php
	if (!isset($_SESSION)) session_start();
	include('../../../../start.php');
	fnc_autentificacion();
	
	$per_documento = $_POST['per_documento'];
	$sql = sprintf("SELECT * FROM persona WHERE per_documento=%s",
	GetSQLValueString($per_documento,'text'));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);
	if($tot_rows!=0)
	{
		$sql1 = sprintf("SELECT * FROM cliente WHERE per_id=%s",
	GetSQLValueString($row["per_id"],'int'));
		$query1 = mysql_query($sql1, $conexion_mysql) or die(mysql_error());
		$tot_rows1 = mysql_num_rows($query1);
		if($tot_rows1==0)
		{
			$query_insert = sprintf("INSERT INTO cliente (per_id, cli_est) VALUES (%s, %s)",
                       GetSQLValueString($row["per_id"], "int"),
					   GetSQLValueString('Activo', "text"));
		$querymov = mysql_query($query_insert, $conexion_mysql) or die(mysql_error());
		
		}
		echo json_encode($row);
	}
?>