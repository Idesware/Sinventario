<?php
	include('../../../start.php');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>
<div class="container">
	<div class="row">
    	<div class="col-md-2 col-md-offset-5">
        	<img id="loader" src="../../../images/loading.gif"/>
            Cargando...
        </div> 
    </div>
</div>
<?php
/**
 *
 * @author   Esteban Vintimilla
 * @date   Julio 13, 2015
 * 
 */
 
//COMO EL INPUT FILE FUE LLAMADO archivo ENTONCES ACCESAMOS A TRAVÉS DE $_FILES["archivo"]
$extension = substr($_FILES["archivo"]["name"],-4,4);
if($extension == ".csv")
{
	
//SI EL ARCHIVO SE ENVIÃ" Y ADEMÃS SE SUBIO CORRECTAMENTE
if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name'])) {

 //SE ABRE EL ARCHIVO EN MODO LECTURA
 $fp = fopen($_FILES['archivo']['tmp_name'], "r");
 //SE RECORRE
 while (!feof($fp)){ //LEE EL ARCHIVO A DATA, LO VECTORIZA A DATA
  
  //SI SE QUIERE LEER SEPARADO POR TABULADORES
  //$data  = explode(" ", fgets($fp));
  //SI SE LEE SEPARADO POR COMAS
  $data  = explode(";", fgets($fp));
  
  	date_default_timezone_set('America/Guayaquil');
	$fecha_actual=date('Y-m-d H:i:s');
	$nombre_usuario = $_POST["nombre_usuario"];
  	////////////////////////////////////////////////////////////////////////////////////////////////
	$unidad = utf8_encode($data[0]);
	////////////////////////////////////////////////////////////////////////////////////////////////
	$categoria = utf8_encode($data[1]);
	////////////////////////////////////////////////////////////////////////////////////////////////
	$codigo = $data[2];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$serie = $data[3];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$nombre = $data[4];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$costo = $data[5];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$valor = $data[6];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$metodo = $data[7];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$pvp = $data[8];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$stock_cantidad = $data[9];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$stock_minimo = $data[10];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$paga_iva = $data[11];
	
	if($unidad != "" || $categoria != "" || $codigo != "" || $serie != "" || $nombre != "" || $costo != "" || $valor != "" || $metodo != "" || $pvp != "" || $stock_cantidad != "" || $stock_minimo != "")
	{
			
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sqlValidarProducto = "Select pro_id From producto Where pro_codigo ='$codigo'" ;
	$queryValidarProducto = mysql_query($sqlValidarProducto, $conexion_mysql) or die(mysql_error());
	$tot_rows_sqlValidarProducto = mysql_num_rows($queryValidarProducto);
	$row = mysql_fetch_assoc($queryValidarProducto);
	$id_pro = $row['pro_id'];	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sqlValidarCategoria = "Select cat_id From categoria_producto Where cat_nom ='$categoria' and cat_status = 'N'" ;
	$queryValidarCategoria = mysql_query($sqlValidarCategoria, $conexion_mysql) or die(mysql_error());
	$tot_rows_sqlValidarCategoria = mysql_num_rows($queryValidarCategoria);
			
			
	$sqlValidarUnidad = "Select unidad_id From unidad_producto Where unidad_nom ='$unidad' and unidad_status = 'N'" ;
	$queryValidarUnidad = mysql_query($sqlValidarUnidad, $conexion_mysql) or die(mysql_error());
	$tot_rows_sqlValidarUnidad = mysql_num_rows($queryValidarUnidad);
			
		if($tot_rows_sqlValidarProducto == 0)
		{	
			if($tot_rows_sqlValidarCategoria > 0 && $tot_rows_sqlValidarUnidad > 0)
			{
				$row = mysql_fetch_assoc($queryValidarCategoria);
				$id_cat = $row['cat_id'];
				$row = mysql_fetch_assoc($queryValidarUnidad);
				$id_uni = $row['unidad_id'];
				
				$sqlProducto = "insert into producto(suc_id,unidad_id,cat_id,pro_codigo,pro_nombre,pro_fecha_crea,pro_usuario_crea) values('1','$id_uni','$id_cat','$codigo','$nombre','$fecha_actual','$nombre_usuario')";
				mysql_query($sqlProducto, $conexion_mysql) or die(mysql_error());
				$id_producto=mysql_insert_id();
				$sqlDetalle = "insert into detalle_producto
	   (pro_id,det_pro_costo,val_gan_pvp,est_iva,met_gan_pvp,pvp)       		    
 values('$id_producto','$costo','$valor','$paga_iva','$metodo','$pvp')";
				mysql_query($sqlDetalle, $conexion_mysql) or die(mysql_error());
				$id_detalle=mysql_insert_id();
				$sqlStock = "insert into stock(det_pro_id,stk_cantidad,stk_minimo) values
				('$id_detalle','$stock_cantidad','$stock_minimo')";
				mysql_query($sqlStock, $conexion_mysql) or die(mysql_error());
				$sqlSerie = "insert into serie(det_pro_id,ser_codigo,ser_cantidad) values
				('$id_detalle','$serie','$stock_cantidad')";
				mysql_query($sqlSerie, $conexion_mysql) or die(mysql_error());
			}
		}
		else
		{
			if($tot_rows_sqlValidarCategoria > 0 && $tot_rows_sqlValidarUnidad > 0)
			{	
				$query_det=sprintf("SELECT det_pro_id FROM detalle_producto Where pro_id = %s",
		GetSQLValueString($id_pro, "int"));
  				$RSDet = mysql_query($query_det, $conexion_mysql) or die(mysql_error());
				$rowDet = mysql_fetch_assoc($RSDet);
				$id_detalle = $rowDet['det_pro_id'];	
				
				$row = mysql_fetch_assoc($queryValidarCategoria);
				$id_cat = $row['cat_id'];
				$row = mysql_fetch_assoc($queryValidarUnidad);
				$id_uni = $row['unidad_id'];
				$sqlObtenerStock = "Select * From stock Where det_pro_id ='$id_detalle'" ;
				$queryObtenerStock = mysql_query($sqlObtenerStock, $conexion_mysql) or die(mysql_error());
				$rowStock = mysql_fetch_assoc($queryObtenerStock);
				$stkfinal = $rowStock['stk_cantidad'] + $stock_cantidad;
				$sqlStock = "update stock set stk_cantidad = $stkfinal, stk_minimo = $stock_minimo Where det_pro_id ='$id_detalle'";
				mysql_query($sqlStock, $conexion_mysql) or die(mysql_error());
				
				$query4=sprintf("SELECT ser_id FROM serie INNER JOIN detalle_producto on serie.det_pro_id = detalle_producto.det_pro_id WHERE ser_codigo = %s AND ser_eliminado = 'N' AND detalle_producto.det_pro_id = %s",
		GetSQLValueString($serie, "text"),
    	GetSQLValueString($id_detalle, "int"));
  		$RS4 = mysql_query($query4, $conexion_mysql) or die(mysql_error());
		$num_ser = mysql_num_rows($RS4);
		if($num_ser > 0)
		{
			$query_select_serie=sprintf("SELECT * FROM serie WHERE det_pro_id = %s AND ser_eliminado = %s AND ser_codigo = %s",
    	GetSQLValueString($id_detalle, "int"),
		GetSQLValueString('N', "text"),
		GetSQLValueString($serie, "text"));
  		$RS_serie = mysql_query($query_select_serie, $conexion_mysql) or die(mysql_error());
		$row_RS_datos_serie = mysql_fetch_assoc($RS_serie);
		$ser_cantidad = $row_RS_datos_serie["ser_cantidad"];
		$ser_cantidad_final = $ser_cantidad + $stock_cantidad;
		$query_update_serie = sprintf("UPDATE serie set ser_cantidad = %s WHERE ser_codigo = %s AND ser_eliminado = 'N' AND det_pro_id = %s",
                       GetSQLValueString($ser_cantidad_final, "text"),
					   GetSQLValueString($serie, "text"),
					   GetSQLValueString($id_detalle, "text"));
		mysql_query($query_update_serie, $conexion_mysql) or die(mysql_error());
		}
		else
		{
			$query_insert_serie = sprintf("INSERT INTO serie(det_pro_id, ser_codigo, ser_cantidad, ser_eliminado) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($id_detalle, "int"),
					   GetSQLValueString($serie, "text"),
					   GetSQLValueString($stock_cantidad, "text"),
					   GetSQLValueString('N', "text"));
			mysql_query($query_insert_serie, $conexion_mysql) or die(mysql_error());
		}		
				
				
			}
		}
	}
 }
   $mensaje = "Archivo subido correctamente";
} else 
	$mensaje = "Error al subir el archivo";
}
else{
	$mensaje = "El tipo de archivo debe ser .csv";
}
?>
</html>
<script type="text/javascript">
	setTimeout(recargar_pagina, 2000)
	
	function recargar_pagina() {
		alert("<?php echo $mensaje; ?>");
  		window.location="index.php";
	}
	
	function submit()
	{
		$('#loader').show();
		$('#loader').hide();
	}
</script>