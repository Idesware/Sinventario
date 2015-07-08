<?php
	include ('../../../../start.php');
	fnc_autentificacion();
		
	$usuario = $_POST['usuario'];
	$accion = $_POST['accion'];
	date_default_timezone_set('America/Guayaquil');
	$fecha_actual=date('Y-m-d H:i:s');
	if($accion == 'VALOR_CAJA')
	{
		$id_caja = $_POST['id_caja'];
		
		$sql = sprintf("SELECT * FROM caja WHERE caj_id = %s AND caj_eliminado='N'", GetSQLValueString($id_caja, "int"));
		$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		$row = mysql_fetch_assoc($query);
		$val = $row["caj_valor"];
		echo $val;
	}
	if($accion == 'DEPOSITO')
	{
		$id_caja = $_POST['id_caja'];
		$ingreso = $_POST['ingreso'];
		$sucursal = $_POST['sucursal'];
		
		$querycaja=sprintf("SELECT * FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($sucursal, "text"));  
  		$RScaja = mysql_query($querycaja, $conexion_mysql) or die(mysql_error());
		$row_RS_datoscaja = mysql_fetch_assoc($RScaja);		
		$caja_valor = $row_RS_datoscaja["caj_valor"];
		
		$caja_valor_final = str_replace(',', '.', $caja_valor + $ingreso);
		
		$query_insert_mov = sprintf("INSERT INTO movimientos_caja (caj_id, tip_id, mov_caj_val, mov_caj_fecha, mov_caj_usuario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "text"),
					   GetSQLValueString(2, "text"),
					   GetSQLValueString($ingreso, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($usuario, "text"));
		$querymov = mysql_query($query_insert_mov, $conexion_mysql) or die(mysql_error());
					   
		
		$query_update_caj = sprintf("UPDATE caja set caj_valor = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
                       GetSQLValueString($caja_valor_final, "text"),
					   GetSQLValueString($sucursal, "text"));
		$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
		echo $caja_valor_final;
	}




	if($accion == 'EGRESO')
	{
		$id_caja = $_POST['id_caja'];
		$egreso = $_POST['egreso'];
		$sucursal = $_POST['sucursal'];
		
		$querycaja=sprintf("SELECT * FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($sucursal, "text"));  
  		$RScaja = mysql_query($querycaja, $conexion_mysql) or die(mysql_error());
		$row_RS_datoscaja = mysql_fetch_assoc($RScaja);		
		$caja_valor = $row_RS_datoscaja["caj_valor"];
		if($caja_valor < $egreso)
		{
			echo "False";
		}
		else
		{
			$caja_valor_final = str_replace(',', '.', $caja_valor - $egreso);
			
			$query_insert_mov = sprintf("INSERT INTO movimientos_caja (caj_id, tip_id, mov_caj_val, mov_caj_fecha, mov_caj_usuario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "text"),
					   GetSQLValueString(3, "text"),
					   GetSQLValueString($egreso, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($usuario, "text"));
		$querymov = mysql_query($query_insert_mov, $conexion_mysql) or die(mysql_error());
			
			$query_update_caj = sprintf("UPDATE caja set caj_valor = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
						   GetSQLValueString($caja_valor_final, "text"),
						   GetSQLValueString($sucursal, "text"));
			$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
			echo $caja_valor_final;
		}
	}
	if($accion == 'ABRIR_CAJA')
	{
		$sucursal = $_POST['sucursal'];
		
		$query_update_caj = sprintf("UPDATE caja set caj_estado = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
					   GetSQLValueString("A", "text"),
					   GetSQLValueString($sucursal, "text"));
		$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
		echo "True";
	}
	if($accion == 'CERRAR_CAJA')
	{
		$sucursal = $_POST['sucursal'];
		
		$query_update_caj = sprintf("UPDATE caja set caj_estado = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
					   GetSQLValueString("C", "text"),
					   GetSQLValueString($sucursal, "text"));
		$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
		echo "True";
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
?>