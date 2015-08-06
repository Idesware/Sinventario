<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT * FROM producto
		inner join detalle_producto on producto.pro_id=detalle_producto.pro_id
	    inner join serie on detalle_producto.det_pro_id=serie.det_pro_id
		WHERE (pro_codigo LIKE"%'.$_REQUEST['term'].'%" OR pro_nombre LIKE"%'.$_REQUEST['term'].'%") AND pro_eliminado = "N"'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['pro_codigo']."-".$row['ser_codigo'],
			'value' => $row['pro_codigo'],
			'label' => $row['pro_nombre']."-".$row['ser_codigo'] //Esto Muestra
			);
		}

		echo json_encode($datos);
?>