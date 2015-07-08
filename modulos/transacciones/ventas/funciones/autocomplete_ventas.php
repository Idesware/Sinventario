<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT * FROM producto WHERE (pro_codigo LIKE"%'.$_REQUEST['term'].'%" OR pro_nombre LIKE"%'.$_REQUEST['term'].'%") AND pro_eliminado = "N" AND pro_inv_inic = "C"'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['pro_codigo'],
			'value' => $row['pro_codigo'],
			'label' => $row['pro_nombre'] //Esto Muestra
			);
		}

		echo json_encode($datos);
?>