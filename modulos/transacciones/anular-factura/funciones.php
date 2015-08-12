<?php
		include ('../../../start.php');
		fnc_autentificacion();
		$id_user = $_SESSION['id_usuario'];
		$id_emp = $_SESSION['id_empleado'];
		$datEmpleado = fnc_datEmp($id_emp);
		$accion = fnc_varGetPost('accion');
		$persona = fnc_usuario($id_user);
		$suc=$datEmpleado['suc_id'];
		$vendedor=$persona['usr_nombre'];
		
		$num_fac = $_POST['num_fac'];
		$id = $_POST['id'];
		
		date_default_timezone_set('America/Guayaquil');
		$fecha_actual=date('Y-m-d H:i:s');

		$query_cab_ven = sprintf("SELECT * FROM cabecera_ventas INNER JOIN detalle_ventas ON cabecera_ventas.cab_ven_id = detalle_ventas.cab_ven_id WHERE cab_ven_id = %s",
		GetSQLValueString($id, "int"));
		$RS_cab_ven = mysql_query($query_cab_ven, $conexion_mysql) or die(mysql_error());
		$tot_rows = mysql_num_rows($RS_cab_ven);	
		$row_RS_ven = mysql_fetch_assoc($RS_cab_ven);
		if($tot_rows > 0)	
		{
			$query_update_cab = sprintf("UPDATE cabecera_ventas set cab_ven_est = %s WHERE cab_ven_id = %s",
                       GetSQLValueString("A", "text"),
					   GetSQLValueString($id, "int"));
			mysql_query($query_update_cab, $conexion_mysql) or die(mysql_error());
			do
			{
				//Selecciona cantidad actual stock
				$queryStock=sprintf("SELECT * FROM stock WHERE det_pro_id = %s AND stk_eliminado = 'N'",
    			GetSQLValueString($row_RS_ven["det_pro_id"], "int"));
				$RSStock = mysql_query($queryStock, $conexion_mysql) or die(mysql_error());
				$row_RS_Stock = mysql_fetch_assoc($RSStock);		
				$stk_cantidad = $row_RS_Stock["stk_cantidad"];
				//Suma la cantidad actual más la de la factura anulada
				$stk_cantidad_final = $stk_cantidad + $row_RS_ven["det_ven_can"];
				
				//Actualiza a la nueva cantidad el Stock
				$query_update_stk = sprintf("UPDATE stock set stk_cantidad = %s WHERE det_pro_id = %s AND stk_eliminado = %s",
			    GetSQLValueString($stk_cantidad_final, "text"),
			    GetSQLValueString($row_RS_ven["det_ven_id"], "int"),
			    GetSQLValueString('N', "text"));
				mysql_query($query_update_stk, $conexion_mysql) or die(mysql_error());
			}
			while ($row_RS_ven = mysql_fetch_assoc($RS_cab_ven));
		}
?>
