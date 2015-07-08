<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	$sql = "SELECT * FROM tipo where tip_eliminado='N' ";
	while($rows = mysql_fetch_array($query)){
		$datos[] = array('value' => $rows['tip_id'], 'label' => $rows['tip_des']);
	}	
	echo json_encode($datos);
	return($datos);
?>