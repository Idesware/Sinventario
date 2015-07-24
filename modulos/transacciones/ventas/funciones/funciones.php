<?php
		include ('../../../../start.php');
		fnc_autentificacion();
		$id_user = $_SESSION['id_usuario'];
		$id_emp = $_SESSION['id_empleado'];
		$datEmpleado = fnc_datEmp($id_emp);
		$accion = fnc_varGetPost('accion');
		$persona = fnc_usuario($id_user);
		$suc=$datEmpleado['suc_id'];
		$vendedor=$persona['usr_nombre'];
		
		$accion = $_POST['accion'];
		
		if($accion == 'GUARDAR')
		{
		$test = $_POST['jsarr'];
		$test = stripslashes($test);
		$test = json_decode($test);
		date_default_timezone_set('America/Guayaquil');
		$fecha_actual=date('Y-m-d H:i:s');
		$sucursal = $_POST['sucursal'];
		$totcab = $_POST['total'];
		$condPago=$_POST['condPago'];
		$subt = $_POST['subtotal'];
		$iva = $_POST['iva'];
		$desc = $_POST['descuento'];
		$referencia = $_POST['referencia'];
		$cedula = $_POST['cedula'];
		$vendedor = $_POST['vendedor'];
		
		if($condPago == 0)
		{
			$tipo_pago = "C";
		}
		else
		{
			$tipo_pago = "D";
		}
		
		$query_cedula=sprintf("SELECT cli_id FROM persona INNER JOIN cliente ON persona.per_id = cliente.per_id WHERE per_documento = %s",
    	GetSQLValueString($cedula, "text"));  
  		$RS_cedula = mysql_query($query_cedula, $conexion_mysql) or die(mysql_error());
		$row_RS_cedula = mysql_fetch_assoc($RS_cedula);		
		$cli_id = $row_RS_cedula["cli_id"];
		$query_insert_user = sprintf("INSERT INTO cabecera_ventas (suc_id, cab_ven_total, cab_ven_fecha, cab_ven_usu, cab_ven_ref, cab_ven_subt, cab_ven_iva, cab_ven_des, cab_ven_for_pag, cli_id,emp_id) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($sucursal, "text"),
					   GetSQLValueString($totcab, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($vendedor, "text"),
					   GetSQLValueString($referencia, "text"),
					   GetSQLValueString($subt, "text"),
					   GetSQLValueString($iva, "text"),
					   GetSQLValueString($desc, "text"),
					   GetSQLValueString($tipo_pago, "text"),
					   GetSQLValueString($cli_id, "text"),
					   GetSQLValueString($vendedor, "text"));
					   
			$query = mysql_query($query_insert_user, $conexion_mysql) or die(mysql_error());
			$id_cab_ventas=mysql_insert_id();
		
		foreach($test as $key=>$value)
		{
			$pro = $value->producto;
			$can = $value->cantidad;
			$pre = $value->precio;
			$tot = $value->total;
			$idpro = $value->idpro;
			
			$query=sprintf("SELECT * FROM detalle_producto WHERE pro_id = %s AND det_pro_eliminado = 'N'",
    	GetSQLValueString($idpro, "int"));  
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);		
		$det_pro_id = $row_RS_datos["det_pro_id"];
		
			$query_insert_vent_det = sprintf("INSERT INTO detalle_ventas (cab_ven_id, pro_id, det_ven_val, det_ven_can) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($id_cab_ventas, "text"),
					   GetSQLValueString($idpro, "text"),
					   GetSQLValueString($pre, "text"),
					   GetSQLValueString($can, "text"));
					   
					   //echo $query_insert_vent_det;
					   
			$query1 = mysql_query($query_insert_vent_det, $conexion_mysql) or die(mysql_error());
			
		$query2=sprintf("SELECT * FROM stock WHERE det_pro_id = %s AND stk_eliminado = 'N'",
    	GetSQLValueString($det_pro_id, "text"));  
  		$RS2 = mysql_query($query2, $conexion_mysql) or die(mysql_error());
		$row_RS_datos2 = mysql_fetch_assoc($RS2);		
		$stk_cantidad = $row_RS_datos2["stk_cantidad"];
		$stk_cantidad_final = $stk_cantidad - $can;
		$query_update_stk = sprintf("UPDATE stock set stk_cantidad = %s WHERE det_pro_id = %s AND stk_eliminado = 'N'",
                       GetSQLValueString($stk_cantidad_final, "text"),
					   GetSQLValueString($det_pro_id, "text"));
		$query_3 = mysql_query($query_update_stk, $conexion_mysql) or die(mysql_error());
		
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		if($condPago==0){//VENTA A CONTADO
		$querycaja=sprintf("SELECT caj_valor FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($sucursal, "text")); 
  		$RScaja = mysql_query($querycaja, $conexion_mysql) or die(mysql_error());
		$row_RS_datoscaja = mysql_fetch_assoc($RScaja);		
		$caja_valor = $row_RS_datoscaja["caj_valor"];
		$caja_valor_final = $caja_valor + $totcab;
		
		$caja_valor_final = str_replace(',', '.', $caja_valor_final);
		//echo $caja_valor_final;
		$query_update_caj = sprintf("UPDATE caja set caj_valor = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
                       GetSQLValueString($caja_valor_final, "text"),
					   GetSQLValueString($sucursal, "text"));
		$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
		
		$query_insert_mov = sprintf("INSERT INTO movimientos_caja (caj_id, tip_id, mov_caj_val, mov_caj_fecha, mov_caj_usuario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "text"),
					   GetSQLValueString(4, "text"),
					   GetSQLValueString($totcab, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($vendedor, "text"));
		$querymov = mysql_query($query_insert_mov, $conexion_mysql) or die(mysql_error());
		}
		echo $referencia;
	}
	if($accion == 'OBTENER_PRECIO')
	{
		$pro_cod = $_POST['pro_cod'];
		$suc_id = $_POST['suc_id'];
		$cantidad = $_POST['cantidad'];
		
		$query=sprintf("SELECT pro_id FROM producto WHERE pro_codigo = %s AND suc_id = %s AND pro_eliminado = 'N'",
    	GetSQLValueString($pro_cod, "text"),
		GetSQLValueString($suc_id, "int"));    
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);		
		$pro_id = $row_RS_datos["pro_id"];

		$query1=sprintf("SELECT det_pro_id, detalle_producto.pro_id, pro_serie, det_pro_costo, val_gan_pvp, est_iva, met_gan_pvp FROM detalle_producto INNER JOIN producto ON detalle_producto.pro_id = producto.pro_id WHERE detalle_producto.pro_id = %s AND det_pro_eliminado = 'N'",
		GetSQLValueString($pro_id, "int"));    
  		$RS1 = mysql_query($query1, $conexion_mysql) or die(mysql_error());
		$row_RS_datos1 = mysql_fetch_assoc($RS1);
		
		
		$query2=sprintf("SELECT stk_cantidad FROM stock WHERE det_pro_id = %s",
    	GetSQLValueString($row_RS_datos1['det_pro_id'], "text"));    
  		$RS2 = mysql_query($query2, $conexion_mysql) or die(mysql_error());
		$row_RS_datos2 = mysql_fetch_assoc($RS2);		
		$stock = $row_RS_datos2["stk_cantidad"];
		

		
		if (!isset($pro_id)) {
			echo "false";
		}
		else if($stock >= $cantidad)
		{
			$test = json_encode($row_RS_datos1);
			echo $test;
		}
		else
		{
			echo "stock";
		}
	}
	if($accion == 'Verificar_Cantidad')
	{
		$pro_cod = $_POST['pro_cod'];
		$suc_id = $_POST['suc_id'];
		$cantidad = $_POST['cantidad'];
		
		$query=sprintf("SELECT pro_id FROM producto WHERE pro_codigo = %s AND pro_eliminado = 'N'",
    	GetSQLValueString($pro_cod, "text"));    
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);		
		$pro_id = $row_RS_datos["pro_id"];
		
		$query1=sprintf("SELECT * FROM detalle_producto WHERE suc_id = %s AND pro_id = %s AND det_pro_eliminado = 'N'",
    	GetSQLValueString($suc_id, "text"),
		GetSQLValueString($pro_id, "text"));    
  		$RS1 = mysql_query($query1, $conexion_mysql) or die(mysql_error());
		$row_RS_datos1 = mysql_fetch_assoc($RS1);
		
		$query2=sprintf("SELECT * FROM stock WHERE det_pro_id = %s",
    	GetSQLValueString($row_RS_datos1['det_pro_id'], "text"));    
  		$RS2 = mysql_query($query2, $conexion_mysql) or die(mysql_error());
		$row_RS_datos2 = mysql_fetch_assoc($RS2);		
		$stock = $row_RS_datos2["stk_cantidad"];
		
		if($stock >= $cantidad)
		{
			echo "True";
		}
		else
		{
			echo "False";
		}
	}
	if($accion == 'Verificar_Caja')
	{
		$suc_id = $_POST['sucursal'];
		
		$query=sprintf("SELECT caj_estado FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($suc_id, "text"));
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);		
		$estado = $row_RS_datos["caj_estado"];
		if($estado == "A")
		{
			echo "True";
		}
		else
		{
			echo "False";
		}
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if($condPago==1)
	{
				$doc=$_POST['doc'];
				$abono=$_POST['abono'];
				
				$sqlper = sprintf("SELECT * FROM persona inner join cliente on persona.per_id=cliente.per_id WHERE per_documento=%s",
				GetSQLValueString($doc,'text'));
				$queryperex = mysql_query($sqlper, $conexion_mysql);
				$rowperex = mysql_fetch_assoc($queryperex);
				$tot_rowsperex = mysql_num_rows($queryperex);	
				/// - SUPERADO
				$saldo_pendiente=$totcab-$abono;
				$query_insert_cab_c_cob = sprintf("INSERT INTO pagos (usr_id, cab_ven_id, pag_tot, pag_abo, pag_sal, pag_fec) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($id_user, "int"),
					   GetSQLValueString($id_cab_ventas, "text"),
					   GetSQLValueString(str_replace(',', '.',$totcab), "text"),
					   GetSQLValueString(str_replace(',', '.',$abono), "text"),
					   GetSQLValueString(str_replace(',', '.',$saldo_pendiente), "text"),
					   GetSQLValueString($fecha_actual, "text"));
					   
			$query_insert_cab_c_cob = mysql_query($query_insert_cab_c_cob, $conexion_mysql) or die(mysql_error());
			$error_1 = mysql_error();
			$idcabxcob=mysql_insert_id();
			mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
			
			if($abono > 0)
			{
				$querycaja=sprintf("SELECT caj_valor FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($sucursal, "text")); 
  		$RScaja = mysql_query($querycaja, $conexion_mysql) or die(mysql_error());
		$row_RS_datoscaja = mysql_fetch_assoc($RScaja);		
		$caja_valor = $row_RS_datoscaja["caj_valor"];
		$caja_valor_final = $caja_valor + $abono;
		$caja_valor_final = str_replace(',', '.', $caja_valor_final);
		//echo $caja_valor_final;
		$query_update_caj = sprintf("UPDATE caja set caj_valor = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
                       GetSQLValueString($caja_valor_final, "text"),
					   GetSQLValueString($sucursal, "text"));
		$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
		
		$query_insert_mov = sprintf("INSERT INTO movimientos_caja (caj_id, tip_id, mov_caj_val, mov_caj_fecha, mov_caj_usuario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "text"),
					   GetSQLValueString(5, "text"),
					   GetSQLValueString($abono, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($vendedor, "text"));
		$querymov = mysql_query($query_insert_mov, $conexion_mysql) or die(mysql_error());
			}
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}//fin de condición
?>

