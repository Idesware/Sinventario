<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	
	$sql = "SELECT * FROM tra_categorias where tra_cat_elim='N' ";
	while($rows = mysql_fetch_array($query)){
		$datos[] = array('value' => $rows['tra_cat_id'], 'label' => $rows['tra_cat_nom']);
	}	
	
	echo json_encode($datos);
	return($datos);

?>
