<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	
	$suc_id = $_POST['idSuc'];
	$tra_id = $_POST['idTra'];
	$sql = sprintf("SELECT * FROM precios WHERE suc_id=%s and tra_id=%s",
	GetSQLValueString($suc_id,'int'),
	GetSQLValueString($tra_id,'int'));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);

	if(($tot_rows == 0)||(isset($suc_id)&&($suc_id==$row['suc_id']))){		
		$respuesta['estado'] = true;
		$respuesta['mensaje'] = '<span class="label label-success"><i class="icon-ok"></i> Precio disponible</span>';
	}else{
		$respuesta['estado'] = false;
		$respuesta['mensaje'] = '<span class="label label-important"><i class="icon-remove"></i> Precio no disponible</span>';
	}
	mysql_free_result($query);
	echo json_encode($respuesta);
?>