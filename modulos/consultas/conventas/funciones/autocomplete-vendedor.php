<?php
		require_once('../../../../start.php');
		
		$query_RSjson='SELECT usr_id, usr_nombre FROM usuarios WHERE (usr_nombre LIKE"%'.$_REQUEST['term'].'%") AND usr_eliminado = "N"'; 
  		$RSjson = mysql_query($query_RSjson) or die(mysql_error());
		
		while($row = mysql_fetch_array($RSjson)){
			$datos[] = array(
			'code' => $row['usr_id'],
			'label' => $row['usr_nombre'] //Esto Muestra
			);
		}

		echo json_encode($datos);
?>