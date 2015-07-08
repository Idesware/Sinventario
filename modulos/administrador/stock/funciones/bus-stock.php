<?php
	if (!isset($_SESSION)) session_start();
	include('../../../../start.php');
	fnc_autentificacion();
	
	$suc_id = $_POST['idSuc'];
	$pro_id = $_POST['idpro'];
	$sql = sprintf("SELECT * FROM stock inner join detalle_producto on detalle_producto.det_pro_id= stock.det_pro_id  where pro_id=%s",
	GetSQLValueString($pro_id,'int'));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);
	$respuesta=$tot_rows;
	mysql_free_result($query);
	
	if($tot_rows!=NULL)
	{
		$MSG = 'Error al insertar.';
		$MSGdes = $error.' El Producto ya Tiene Stock';
		$MSGimg = $RUTAi.'delete.png';
		}
	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
	header("Location: ".$RUTAm."administrador/stock/msg.php");	
	echo $respuesta;
?>