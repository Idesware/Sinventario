<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT cat_nom, cat_id FROM categoria_producto WHERE (cat_nom LIKE"%'.$_REQUEST['term'].'%") AND cat_status = "N"'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['cat_id'],
			'label' => $row['cat_nom'] //Esto Muestra
			);
		}
		echo json_encode($datos);
?>