<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT unidad_nom, unidad_id FROM unidad_producto WHERE (unidad_nom LIKE"%'.$_REQUEST['term'].'%") AND unidad_status = "N"'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['unidad_id'],
			'label' => $row['unidad_nom'] //Esto Muestra
			);
		}
		echo json_encode($datos);
?>