<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	$suc=$_POST['suc'];
	$pro_id=$_POST['id'];
	$sqlstock = sprintf("SELECT * FROM stock inner join detalle_producto on stock.det_pro_id = detalle_producto.det_pro_id where suc_id='".$suc."' and pro_id='".$pro_id."'");
	$queryStock = mysql_query($sqlstock, $conexion_mysql) or die(mysql_error());
	$rowStock  = mysql_fetch_assoc($queryStock);
	$tot_rowsStock = mysql_num_rows($queryStock);
	echo($rowStock);
?>
