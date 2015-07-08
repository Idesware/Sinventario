<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT * FROM producto INNER JOIN detalle_producto on producto.pro_id = detalle_producto.pro_id WHERE pro_codigo LIKE"%'.$_REQUEST['term'].'%" OR pro_nombre LIKE"%'.$_REQUEST['term'].'%"'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['pro_codigo'],
			'value' => $row['pro_codigo'],
			'label' => $row['pro_nombre'],
			);
		}

		echo json_encode($datos);
?>