<?php 
	if (!isset($_SESSION)) session_start();
	include('../../start.php');
	fnc_autentificacion();

header ("Location: ".$RUTAm."index.php");
?>