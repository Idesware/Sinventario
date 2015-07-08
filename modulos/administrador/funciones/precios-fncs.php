<?php 
	include('../../../start.php');
	fnc_autentificacion();
	$pre_id = fnc_varGetPost('pre_id');
	$tra_id = $_POST['tratamiento'];
	$suc_id = $_POST['suc'];
	$pre_val = $_POST['pre_val'];
	$pre_des = $_POST['pre_des'];

	$accion = fnc_varGetPost('accion');
	
	if($accion=='Actualizar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		//Query
		$sql = sprintf('UPDATE precios SET tra_id=%s, suc_id=%s, pre_val=%s, pre_des=%s WHERE pre_id=%s',
		GetSQLValueString($tra_id, 'int'),
		GetSQLValueString($suc_id, 'int'),
		GetSQLValueString($pre_val, 'text'),
		GetSQLValueString($pre_des, 'text'),
		GetSQLValueString($pre_id, 'int'));
		$query = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Actualizado exitosamente.';
			$MSGdes = '[ID: '.$pre_id.'] '.$pre_val;
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al actualizar.';
			$MSGdes = $error;
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
	}

	if($accion=='Insertar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		
		if(($tra_id!=0)and($suc_id!=0))
		{
		//Query
		$sql = sprintf('INSERT INTO precios (suc_id, pre_val, pre_des, tra_id) VALUES (%s,%s,%s,%s)',
		GetSQLValueString($suc_id, 'int'),
		GetSQLValueString($pre_val, 'double'),
		GetSQLValueString($pre_des, 'double'),
		GetSQLValueString($tra_id, 'int'));
		$query = mysql_query($sql, $conexion_mysql); 
		$error = mysql_error();
		$id = mysql_insert_id();
		}
		
		if($tra_id==0)
		{ $nota='Seleccione un Tratamiento';
		}
		if($suc_id==0)
		{ $nota='Seleccione un Sucursal';
		}
		if(($tra_id==0)and($suc_id==0))
		{ $nota='Sucursal y un Tratamiento';
		}
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$pre_id.'] '.$pre_val;
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error.'['.$nota.'] ';
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
	}
	
	if($accion=='Eliminar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		$sql = sprintf('DELETE FROM precios WHERE pre_id=%s',
		GetSQLValueString($pre_id, 'int'));
		$query = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query){
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
	}	
	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
	header("Location: ".$RUTAm."administrador/precios-index.php");
?>