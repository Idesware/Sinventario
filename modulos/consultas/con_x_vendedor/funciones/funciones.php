<?php
	include ('../../../../start.php');
	fnc_autentificacion();
	
    $page = $_POST['page'];  // Almacena el numero de pagina actual
    $limit = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
    $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
    $sord = $_POST['sord'];  // Almacena el modo de ordenación
	$sucursal = $_POST['sucursal'];
	$fechainicio = $_POST['fechainicio'];
	$fechafin = $_POST['fechafin'];
    if(!$sidx) $sidx =1;

	
		$sql = sprintf("SELECT COUNT(*) AS count FROM cabecera_ventas WHERE cab_ven_eliminado = 'N' AND suc_id = %s AND cab_ven_fecha >= %s AND cab_ven_fecha <= %s", 
		GetSQLValueString($sucursal, "int"),
		GetSQLValueString($fechainicio, "date"),
		GetSQLValueString($fechafin, "date"));
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
		$consulta = sprintf("SELECT * FROM cabecera_ventas WHERE cab_ven_eliminado = 'N' AND suc_id = %s AND cab_ven_fecha >= %s AND cab_ven_fecha <= %s", 
		GetSQLValueString($sucursal, "int"),
		GetSQLValueString($fechainicio, "date"),
		GetSQLValueString($fechafin, "date"));
		$result = mysql_query($consulta, $conexion_mysql) or die(mysql_error());
	
		//$result = $conexion->query($consulta);
	
		// Se agregan los datos de la respuesta del servidor
		$respuesta->page = $page;
		$respuesta->total = $total_pages;
		$respuesta->records = $count;
	
		$i=0;
	   while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {
	
		  $respuesta->rows[$i]['id']=$fila['cab_ven_id'];
		  $respuesta->rows[$i]['cell']=array($fila["cab_ven_id"],$fila["cab_ven_fecha"],$fila["cab_ven_usu"],$fila["cab_ven_total"]);
			$i++;
	  } 	
    // La respuesta se regresa como json
    echo json_encode($respuesta);
?>