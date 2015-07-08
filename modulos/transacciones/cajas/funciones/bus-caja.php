<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	
	
	$sql = "SELECT caj_id, caj_nombre FROM caja where caj_eliminado='N' ";
	while($rows = mysql_fetch_array($query)){
		$datos[] = array('value' => $rows['caj_id'], 'label' => $rows['caj_nombre']);
	}	
	
	echo json_encode($datos);
	return($datos);

?>