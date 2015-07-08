<?php
		include ('../../../../start.php');
		fnc_autentificacion();

		$prov_cod = $_POST['codigo'];
		
		$query=sprintf("SELECT per_direccion1, per_telefono FROM persona INNER JOIN proveedor on persona.per_id = proveedor.per_id WHERE per_documento = %s AND prov_eliminado = 'N'",
    	GetSQLValueString($prov_cod, "text"));    
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);
		
		$res = json_encode($row_RS_datos);
		echo $res;
?>