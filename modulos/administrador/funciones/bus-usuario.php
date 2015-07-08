<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	
	$idUser = $_POST['idUser'];
	$user = $_POST['usuario'];
	$sql = sprintf("SELECT * FROM usuarios WHERE usr_nombre=%s",
	GetSQLValueString($user,'text'));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);

	if(($tot_rows == 0)||(isset($idUser)&&($idUser==$row['usr_id']))){		
		$respuesta['estado'] = true;
		$respuesta['mensaje'] = '<span class="label label-success"><i class="icon-ok"></i> Usuario disponible</span>';
	}else{
		$respuesta['estado'] = false;
		$respuesta['mensaje'] = '<span class="label label-important"><i class="icon-remove"></i> Usuario no disponible</span>';
	}
	mysql_free_result($query);
	echo json_encode($respuesta);
?>