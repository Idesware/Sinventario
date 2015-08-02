<?php
	include ('../../../../start.php');
	fnc_autentificacion();
	$pro_id = fnc_varGetPost('pro_id');

	
	$nombre = $_POST["inputNom"];
	$input_unidad_codigo = $_POST["inputUnidad"];
	$codigo = $_POST["inputCod"];
	$idsucursal = $_POST["input_sucursal"];
	$empleado = $_POST["empleado"];
	$input_categoria_codigo = $_POST["input_cat_pro"];
	$nombre_usuario = $_POST["nombre_usuario"];
	
	
	date_default_timezone_set('America/Guayaquil');
	$fecha_actual=date('Y-m-d H:i:s');
	
	$accion = fnc_varGetPost('accion');
		
		if($accion == "Insertar")
		{
			$sql = sprintf("SELECT * FROM producto WHERE pro_eliminado = 'N' AND pro_codigo = %s", 
			GetSQLValueString($codigo, "text"));
			
			
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$tot_rows = mysql_num_rows($query); 
			if($tot_rows > 0)
			{
				$MSG = 'Error al insertar.';
				$MSGdes = 'Ya Existe El Codigo Del Producto';
				$MSGimg = $RUTAi.'delete.png';
			}
			else
			{
			mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
			mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
			
        	$query_insert_user = sprintf("INSERT INTO producto (pro_codigo, pro_nombre, pro_fecha_crea, pro_eliminado, suc_id, unidad_id, cat_id, pro_usuario_crea) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($codigo, "text"),
                       GetSQLValueString($nombre, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString('N', "text"),
					   GetSQLValueString($idsucursal, "int"),
					   GetSQLValueString($input_unidad_codigo, "int"),
					   GetSQLValueString($input_categoria_codigo, "int"),
					   GetSQLValueString($nombre_usuario, "text"));
					   
		$query_1 = mysql_query($query_insert_user, $conexion_mysql); $error=mysql_error();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query_1){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$pro_id.'] '.$nombre;
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error;
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
			}
		}
		

		if($accion == "Actualizar")
		{
			$sql = sprintf("SELECT * FROM producto WHERE (pro_eliminado = 'N' AND pro_id <> %s AND pro_codigo = %s)", 
			
			GetSQLValueString($pro_id, "text"),
			GetSQLValueString($codigo, "text"));
			
			
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$tot_rows = mysql_num_rows($query); 
			if($tot_rows > 0)
			{
				$MSG = 'Error al insertar.';
				$MSGdes = 'Ya Existe El Codigo Del Producto';
				$MSGimg = $RUTAi.'delete.png';
			}
			else
			{
				mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
				mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
				
				$query_update_user = sprintf("UPDATE producto set pro_nombre = %s, pro_codigo = %s, unidad_id = %s, cat_id = %s WHERE pro_id = %s",

						   
						   GetSQLValueString($nombre, "text"),
						   GetSQLValueString($codigo, "text"),
						   GetSQLValueString($input_unidad_codigo, "int"),
						   GetSQLValueString($input_categoria_codigo, "int"),
						   GetSQLValueString($pro_id, "int"));
						  
			$query_1 = mysql_query($query_update_user, $conexion_mysql) or die(mysql_error());


			//Si no hubo errores COMMIT caso contrario ROLLBACK
			if($query_1){
				mysql_query("COMMIT;", $conexion_mysql);
				$MSG = 'Actualizado exitosamente.';
				$MSGdes = '[ID: '.$pro_id.'] '.$nombre;
				$MSGimg = $RUTAi.'ok.png';
			}else{
				mysql_query("ROLLBACK;", $conexion_mysql);
				$MSG = 'Error al actualizar.';
				$MSGdes = $error;
				$MSGimg = $RUTAi.'delete.png';
			}
			mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
		}
		}
		

		if($accion == "Eliminar")
		{
			$sqlpro = sprintf("SELECT * FROM stock inner join detalle_producto on detalle_producto.det_pro_id=stock.det_pro_id where detalle_producto.pro_id=%s", 
			GetSQLValueString($pro_id, "text"));
			$querypro = mysql_query($sqlpro, $conexion_mysql) or die(mysql_error());
			$rowpro = mysql_fetch_assoc($querypro);
			$tot_rowspro = mysql_num_rows($querypro); 
			if($rowpro['stk_cantidad']<=0){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		$query_delete_user = sprintf("UPDATE producto set pro_eliminado = %s WHERE pro_id = %s",
					   GetSQLValueString('S', "text"),
					   GetSQLValueString($pro_id, "text"));
		$query_1 = mysql_query($query_delete_user, $conexion_mysql);
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query_1){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Eliminado exitosamente.';
			$MSGdes = 'Registro eliminado';
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al eliminar.';
			$MSGdes = $error;
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
			}else{
				$MSG = 'Error al eliminar.';
				$MSGdes = "El producto cuenta con Stock actual";
				$MSGimg = $RUTAi.'delete.png';
				}
			
		}
		
		
		$_SESSION['MSG'] = $MSG;
		$_SESSION['MSGdes'] = $MSGdes;
		$_SESSION['MSGimg'] = $MSGimg;
		header("Location: ".$RUTAm."administrador/productos/index.php");
?>