<?php
	include ('../../../../start.php');
	fnc_autentificacion();
	
    $page = $_POST['page'];  // Almacena el numero de pagina actual
    $limit = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
    $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
    $sord = $_POST['sord'];  // Almacena el modo de ordenación
	$codigo = $_POST['codigo'];
    if(!$sidx) $sidx =1;

	
		$sql = sprintf("SELECT COUNT(*) AS count FROM detalle_ventas WHERE det_ven_eliminado = 'N' AND cab_ven_id = %s", 
		GetSQLValueString($codigo, "int"));
		$result = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		$fila = mysql_fetch_array($result);
		$count = $fila["count"];
	
		//En base al numero de registros se obtiene el numero de paginas
		if( $count >0 ) {
		$total_pages = ceil($count/$limit);
		} else {
		$total_pages = 0;
		}
		if ($page > $total_pages)
			$page=$total_pages;
	
		//Almacena numero de registro donde se va a empezar a recuperar los registros para la pagina
		$start = $limit*$page - $limit; 
	
		//Consulta que devuelve los registros de una sola pagina
		//$consulta = "SELECT idcliente, nombre, direccion, telefono, email FROM tblCliente ORDER BY $sidx $sord LIMIT $start , $limit;";
		$consulta = sprintf("SELECT * FROM detalle_ventas 
		INNER JOIN detalle_producto on detalle_ventas.det_pro_id = detalle_producto.det_pro_id
		INNER JOIN producto on detalle_producto.pro_id = producto.pro_id
		WHERE det_ven_eliminado = 'N' AND cab_ven_id = %s", 
		GetSQLValueString($codigo, "int"));
		$result = mysql_query($consulta, $conexion_mysql) or die(mysql_error());
	
		//$result = $conexion->query($consulta);
	
		// Se agregan los datos de la respuesta del servidor
		$respuesta->page = $page;
		$respuesta->total = $total_pages;
		$respuesta->records = $count;
	
		$i=0;
	   while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
		  $respuesta->rows[$i]['id']=$fila['det_ven_id'];
		  $respuesta->rows[$i]['cell']=array($fila["det_ven_id"],$fila["pro_nombre"],$fila["det_ven_val"],$fila["det_ven_can"],$fila["det_ven_can"]*$fila["det_ven_val"]);
			$i++;
	  } 	
    // La respuesta se regresa como json
    echo json_encode($respuesta);
?>