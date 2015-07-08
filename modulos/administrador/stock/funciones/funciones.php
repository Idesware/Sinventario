<?php
	include('../../../../start.php');
	fnc_autentificacion();
	
	$suc_id=$_POST['suc_id'];
	$idpro=$_POST['idpro'];
	
	$stk_cantidad=$_POST['stk_cantidad'];
	$stk_minimo=$_POST['stk_minimo'];
	$det_pro_costo=$_POST['det_pro_costo'];
	$est_iva=$_POST['est_iva'];
	$met_gan_pvp=$_POST['met_gan_pvp'];
	$val_gan_pvp = str_replace(',', '.', $_POST['val_gan_pvp']);
	$input_prec_vent= $_POST['input_prec_vent'];
	$pro_id=$_POST['pro_id'];


	$accion = $_POST['accion'];


	
	//detalle_producto
	$sqltabproductos = sprintf("SELECT * FROM detalle_producto WHERE pro_id = '".$idpro."'");
	$queryprod = mysql_query($sqltabproductos, $conexion_mysql) or die(mysql_error());
	$rowproductos = mysql_fetch_assoc($queryprod);
	$tot_rowsproductos = mysql_num_rows($queryprod); 
	
	
	if($tot_rowsproductos==0){
	
	if ($accion=="Insertar"){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		
		//Query
		$sql = sprintf('INSERT INTO detalle_producto (pro_id, det_pro_costo, val_gan_pvp, est_iva,  det_pro_eliminado,met_gan_pvp,pvp) VALUES (%s,%s,%s,%s,%s,%s,%s)',
		
		GetSQLValueString($idpro, 'int'),
		GetSQLValueString($det_pro_costo, 'text'),
		GetSQLValueString($val_gan_pvp, 'text'),
		GetSQLValueString($est_iva, 'text'),
		GetSQLValueString('N', 'text'),
		GetSQLValueString($met_gan_pvp, 'text'),
		GetSQLValueString($input_prec_vent,'text'));
		
		$query = mysql_query($sql, $conexion_mysql); 
		$error = mysql_error();
		$id = mysql_insert_id();

		//Query 2
		$sql2 = sprintf('INSERT INTO stock (det_pro_id, stk_cantidad, stk_minimo, stk_eliminado) VALUES (%s,%s,%s,%s)',
		GetSQLValueString($id, 'int'),
		GetSQLValueString($stk_cantidad, 'int'),
		GetSQLValueString($stk_minimo, 'int'),
		GetSQLValueString('N', 'text'));

		$query2 = mysql_query($sql2, $conexion_mysql); 
		$error = mysql_error();

		$sql3 = sprintf('UPDATE producto SET pro_inv_inic=%s WHERE pro_id=%s',
		GetSQLValueString('C', 'text'),
		GetSQLValueString($idpro, 'int'));
		
		$query3 = mysql_query($sql3, $conexion_mysql); 
		$error = mysql_error();


		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if( $query && $query2 && $query3){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$idpro.'] '.$row["pro_nombre"];
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error.$sql3;
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
	}
	}
	else{
		echo 'alert("Archivo ya registrado en el sistema coloque otro producto");';
		}


	if($accion == "Actualizar")
		{
			
				mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
				mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
				
				$query_update_user = sprintf("UPDATE detalle_producto set det_pro_costo = %s, val_gan_pvp = %s, est_iva = %s, met_gan_pvp = %s, pvp = %s WHERE pro_id = %s",

						   GetSQLValueString($det_pro_costo, "text"),
						   GetSQLValueString($val_gan_pvp, "text"),
						   GetSQLValueString($est_iva, "text"),
						   GetSQLValueString($met_gan_pvp, "text"),
						   GetSQLValueString($input_prec_vent, "text"),
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
		
	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
	header("Location: ".$RUTAm."administrador/stock/index.php");	
?>