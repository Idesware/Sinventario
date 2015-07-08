<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	$sql = "SELECT * FROM producto inner join detalle_producto on detalle_producto.pro_id=producto.pro_id inner join stock on detalle_producto.det_pro_id=stock.det_pro_id where producto.pro_eliminado='N' ";
	while($rows = mysql_fetch_array($query)){
		$datos[] = array('value' => $rows['pro_id'], 'label' => $rows['pro_nombre']);
	}	
	echo json_encode($datos);
	return($datos);
?>
