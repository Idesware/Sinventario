<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT per_documento, prov_nom_com FROM proveedor INNER JOIN persona ON proveedor.per_id = persona.per_id WHERE (per_documento LIKE"%'.$_REQUEST['term'].'%" OR prov_nom_com LIKE"%'.$_REQUEST['term'].'%")'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['per_documento'],
			'value' => $row['per_documento'],
			'label' => $row['prov_nom_com'] //Esto Muestra
			);
		}

		echo json_encode($datos);
?>