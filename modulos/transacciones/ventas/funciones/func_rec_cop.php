<?php
		include ('../../../../start.php');
		fnc_autentificacion();
		$id_user = $_SESSION['id_usuario'];
		$id_emp = $_SESSION['id_empleado'];
		$datEmpleado = fnc_datEmp($id_emp);
		$accion = fnc_varGetPost('accion');
		$suc=$datEmpleado['suc_id'];
		$persona = fnc_usuario($id_user);
		$vendedor=$persona['per_nombre'];
		date_default_timezone_set('America/Guayaquil');
		$fecha_actual=date('Y-m-d H:i:s');
		
		$accion = $_POST['accion'];
		
		if($accion == 'GUARDAR')
		{
		date_default_timezone_set('America/Guayaquil');
		 $rec_des=$_POST['des'];
		 $rec_fecha=date('Y-m-d');
		 $rec_hora=date("G:H:s");
		 $recval=$_POST['val_reg'];
		 $rec_val = str_replace(',', '.', $recval); 
		 $rec_num=$_POST['rec_num']; 
		 $emp_id=$datEmpleado['emp_id']; 
		 $suc_id=$suc;
//		echo "  ".$rec_val."  ";
		$query = sprintf("INSERT INTO recargas (rec_des, rec_fecha, rec_hora, rec_val, emp_id, suc_id, rec_num) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($rec_des, "text"),
					   GetSQLValueString($rec_fecha, "text"),
					   GetSQLValueString($rec_hora, "text"),
					   GetSQLValueString($rec_val, "text"),
					   GetSQLValueString($emp_id, "int"),
					   GetSQLValueString($suc_id, "int"),
					   GetSQLValueString($rec_num, "text"));
					   
			$query = mysql_query($query, $conexion_mysql) or die(mysql_error());
			$error_2 = mysql_error();
			if($query){
			mysql_query("COMMIT;", $conexion_mysql);
			$accionC="Actualizarcaja";
			
			//echo "registro correcto";
			
			}else{
				mysql_query("ROLLBACK;", $conexion_mysql);
				echo "No se pudo registrar la transacción";
			}
			mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql);
			$id=mysql_insert_id();
		}
		/*echo $rec_des.$rec_fecha.$rec_hora.$rec_num.$rec_val.$suc_id.$accion."   ".$error_2;	*/
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($accionC=="Actualizarcaja"){
		$querycaja=sprintf("SELECT * FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($suc_id, "text"));  
  		$RScaja = mysql_query($querycaja, $conexion_mysql) or die(mysql_error());
		$row_RS_datoscaja = mysql_fetch_assoc($RScaja);		
		$caja_valor = $row_RS_datoscaja["caj_valor"];
		$caja_valor_final =  str_replace(',', '.', ($caja_valor + $rec_val));
		 
		$query_update_caj = sprintf("UPDATE caja set caj_valor = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
                       GetSQLValueString($caja_valor_final, "text"),
					   GetSQLValueString($suc_id, "text"));
		$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
		
		$query_insert_mov = sprintf("INSERT INTO movimientos_caja (caj_id, tip_id, mov_caj_val, mov_caj_fecha, mov_caj_usuario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "text"),
					   GetSQLValueString(4, "text"),
					   GetSQLValueString($rec_val, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($vendedor, "text"));
		$querymov = mysql_query($query_insert_mov, $conexion_mysql) or die(mysql_error());
		
		if($query_upd_caja){
			mysql_query("COMMIT;", $conexion_mysql);
			echo "Registro Correcto: ".$rec_des;
			}else{
				mysql_query("ROLLBACK;", $conexion_mysql);
			echo "No se pudo registrar la transacción de ".$rec_des;	
			}
			mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql);
			$id=mysql_insert_id();
		}
	if($accion == 'Verificar_Caja')
	{
		
		$query=sprintf("SELECT caj_estado FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($suc_id, "text"));
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);		
		$estado = $row_RS_datos["caj_estado"];
		if($estado == "A")
		{
			echo "True";
		}
		if($estado == "C")
		{
			echo "False";
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////

?>

