<?php
include ('../../../../start.php');
		fnc_autentificacion();
		$id_user = $_SESSION['id_usuario'];
		$id_emp = $_SESSION['id_empleado'];
		$datEmpleado = fnc_datEmp($id_emp);
		$accion = fnc_varGetPost('accion');
		$persona = fnc_usuario($id_user);
		$sucursal=$datEmpleado['suc_id'];
		$vendedor=$persona['per_nombre'];
		$pag_id=$_POST['pag_id'];
		$abono=$_POST['abono'];
		$accion = $_POST['accion'];
		$cab_ven_id = $_POST['cab_ven_id'];
		date_default_timezone_set('America/Guayaquil');
		$fecha_actual=date('Y-m-d H:i:s');
		
	
if($accion=='GUARDAR')	{
	mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
	mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
	$sqlpag = sprintf("SELECT * FROM pagos where pag_id='".$pag_id."'");
	$querypag = mysql_query($sqlpag, $conexion_mysql) or die(mysql_error());
	$rowpag = mysql_fetch_assoc($querypag);
	$tot_rowspag = mysql_num_rows($querypag);
	if($rowpag ['pag_sal']>0.00){
		$saldoanterior=str_replace(',', '.', $rowpag ['pag_sal']);
		$sumadeabono=str_replace(',', '.', $rowpag ['pag_abo']+$abono);
		$saldopendiente=str_replace(',', '.', $saldoanterior-$abono);
		
		$query_update_cab_ven_id = sprintf("SELECT * FROM pagos where pag_id =%s",
						   GetSQLValueString($pag_id, "int"));
						  
		$query_update_cab_ven_id = mysql_query($query_update_cab_ven_id, $conexion_mysql) or die(mysql_error());
		$row_cab_ven_id = mysql_fetch_assoc($query_update_cab_ven_id);
		$tot_rows_cab_ven_id = mysql_num_rows($query_update_cab_ven_id); 
		$idVen=$row_cab_ven_id['cab_ven_id'];
		$Newsal=$row_cab_ven_id['pag_tot'];
		
		$query_update_cxc = sprintf("INSERT INTO pagos (usr_id, pag_tot, pag_abo, pag_sal, pag_fec, cab_ven_id) VALUES (%s, %s, %s, %s, %s, %s)",
							GetSQLValueString($id_user, "int"),
							GetSQLValueString($saldoanterior, "text"),
						   GetSQLValueString(str_replace(',', '.',$sumadeabono), "text"),
						   GetSQLValueString(str_replace(',', '.',$saldopendiente), "text"),
						   GetSQLValueString($fecha_actual, "text"),
						   GetSQLValueString($idVen, "int"));
						  
			$query_update_cxc = mysql_query($query_update_cxc, $conexion_mysql) or die(mysql_error());
		
		
		
		
		$query_update_cxc = sprintf("UPDATE pagos SET pag_estado=%s WHERE pag_id=%s",
						   GetSQLValueString('A', "text"),
						   GetSQLValueString($pag_id, "int"));
						  
			$query = mysql_query($query_update_cxc, $conexion_mysql) or die(mysql_error());
			
			if($querypag)
			{
			
				$querycaja=sprintf("SELECT * FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
				GetSQLValueString($sucursal, "text"));  
				$RScaja = mysql_query($querycaja, $conexion_mysql) or die(mysql_error());
				$row_RS_datoscaja = mysql_fetch_assoc($RScaja);		
				$caja_valor = $row_RS_datoscaja["caj_valor"];
				$abonof=str_replace(',', '.', $abono);
				$caja_valor_final = str_replace(',', '.', $caja_valor + $abonof);
				$query_update_caj = sprintf("UPDATE caja set caj_valor = %s WHERE suc_id = %s AND caj_eliminado = 'N'",
							   GetSQLValueString($caja_valor_final, "text"),
							   GetSQLValueString($sucursal, "text"));
				$query_upd_caja = mysql_query($query_update_caj, $conexion_mysql) or die(mysql_error());
				
				$query_insert_mov = sprintf("INSERT INTO movimientos_caja (caj_id, tip_id, mov_caj_val, mov_caj_fecha, mov_caj_usuario) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString(1, "text"),
					   GetSQLValueString(5, "text"),
					   GetSQLValueString($abonof, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($vendedor, "text"));
		$querymov = mysql_query($query_insert_mov, $conexion_mysql) or die(mysql_error());
				
			}
			if(($querypag)&&($query_update_caj))
			{	mysql_query("COMMIT;", $conexion_mysql);
				echo "COBRO REALIZADO CORRECTAMENTE, SALDO PENDIETE: ".$saldopendiente;
				} else{
				mysql_query("ROLLBACK;", $conexion_mysql);
					echo "NO SE PUDO COBRAR FAVOR INTENTE EN UNOS MINUTOS";
					}
			

		
		}else{
			echo "CUENTA CANCELADA";
			}
	
}

?>