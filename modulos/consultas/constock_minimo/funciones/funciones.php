<?php
	include ('../../../../start.php');
	fnc_autentificacion();
	
    $page = $_POST['page'];  // Almacena el numero de pagina actual
    $limit = $_POST['rows']; // Almacena el numero de filas que se van a mostrar por pagina
    $sidx = $_POST['sidx'];  // Almacena el indice por el cual se hará la ordenación de los datos
    $sord = $_POST['sord'];  // Almacena el modo de ordenación
    if(!$sidx) $sidx =1;

    // Se crea la conexión a la base de datos
    //$conexion = new mysqli("servidor","usuario","password","basedatos");

    // Se hace una consulta para saber cuantos registros se van a mostrar
    //$result = $conexion->query("SELECT COUNT(*) AS count FROM tblcliente");
	
	$sql = sprintf("SELECT COUNT(*) AS count FROM producto INNER JOIN detalle_producto on producto.pro_id = detalle_producto.pro_id INNER JOIN stock on detalle_producto.det_pro_id = stock.det_pro_id WHERE pro_eliminado = 'N' AND det_pro_eliminado = 'N' AND stk_cantidad <= stk_minimo AND suc_id = %s", GetSQLValueString(1, "int"));
	$result = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	//$result = mysql_fetch_assoc($query);

    // Se obtiene el resultado de la consulta
    //$fila = $result->fetch_array();
    //$count = $fila['count'];
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
	$consulta = sprintf("SELECT * FROM producto INNER JOIN detalle_producto on producto.pro_id = detalle_producto.pro_id INNER JOIN stock on detalle_producto.det_pro_id = stock.det_pro_id WHERE pro_eliminado = 'N' AND det_pro_eliminado = 'N' AND stk_cantidad <= stk_minimo AND suc_id = %s LIMIT $start , $limit;", GetSQLValueString(1, "int"));
	$result = mysql_query($consulta, $conexion_mysql) or die(mysql_error());
    //$result = $conexion->query($consulta);

    // Se agregan los datos de la respuesta del servidor
    $respuesta->page = $page;
    $respuesta->total = $total_pages;
    $respuesta->records = $count;

	$i=0;
   while($fila = mysql_fetch_array($result, MYSQL_ASSOC)) {

      $respuesta->rows[$i]['id']=$fila['pro_id'];
       $respuesta->rows[$i]['cell']=array($fila["pro_id"],$fila["pro_nombre"],$fila["stk_cantidad"],$fila["det_pro_costo"]);
    	$i++;
  } 

    // La respuesta se regresa como json
    echo json_encode($respuesta);
?>