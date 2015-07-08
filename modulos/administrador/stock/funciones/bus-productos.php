<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	
	
	$sql = "SELECT * FROM producto where pro_eliminado='N'";
	while($rows = mysql_fetch_array($query)){
		$datos[] = array('value' => $rows['pro_id'], 'label' => $rows['pro_nombre']);
	}	
	
	echo json_encode($datos);
	return($datos);

?>
