<?php 
	include('../../../start.php');
	fnc_autentificacion();
	$emp_id = fnc_varGetPost('idEmp');
	$datEmpleado = fnc_datEmp($emp_id);
	$accion = fnc_varGetPost('accion');

	if($accion=='Actualizar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		//Query 1
		$sql = sprintf('UPDATE persona SET per_nombre=%s, per_documento=%s, per_nacionalidad=%s, per_ciudad_nac=%s, per_direccion1=%s, per_direccion2=%s, per_num_viv=%s, per_postal=%s, per_mail=%s, per_sexo=%s, per_fec_nac=%s, per_foto=%s, per_ext_foto=%s WHERE per_id=%s',
		GetSQLValueString($nom, 'text'),
		GetSQLValueString($doc, 'text'),
		GetSQLValueString($nac, 'text'),
		GetSQLValueString($ciu, 'text'),
		GetSQLValueString($dir1, 'text'),
		GetSQLValueString($dir2, 'text'),
		GetSQLValueString($num_casa, 'text'),
		GetSQLValueString($cod_post, 'text'),
		GetSQLValueString($mail, 'text'),
		GetSQLValueString($sexo, 'text'),
		GetSQLValueString($fec_nac, 'text'),
		GetSQLValueString('', 'text'),
		GetSQLValueString('', 'text'),
		GetSQLValueString($datEmpleado['per_id'], 'int'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		
		//Query 2
		$sql1 = sprintf('UPDATE empleado SET suc_id=%s, emp_profesion=%s, emp_actividad=%s, emp_sueldo=%s, emp_fecha_ingreso=%s WHERE per_id=%s',
		GetSQLValueString($suc, 'int'),
		GetSQLValueString($prof, 'text'),
		GetSQLValueString($act, 'text'),
		GetSQLValueString($sal, 'text'),
		GetSQLValueString($fec_ing, 'text'),
		GetSQLValueString($emp_id, 'int'));
		$query_2 = mysql_query($sql1, $conexion_mysql);
		$error = mysql_error();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1)&&($query_2)){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Actualizado exitosamente.';
			$MSGdes = '[ID: '.$emp_id.'] '.$nom;
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

		//Query 1
		$sql = sprintf('INSERT INTO persona (per_nombre, per_documento, per_nacionalidad, per_ciudad_nac, per_direccion1, per_direccion2, per_num_viv, per_postal, per_mail, per_sexo, per_fec_nac, per_foto, per_ext_foto) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)',
		GetSQLValueString($nom, 'text'),
		GetSQLValueString($doc, 'text'),
		GetSQLValueString($nac, 'text'),
		GetSQLValueString($ciu, 'text'),
		GetSQLValueString($dir1, 'text'),
		GetSQLValueString($dir2, 'text'),
		GetSQLValueString($num_casa, 'text'),
		GetSQLValueString($cod_post, 'text'),
		GetSQLValueString($mail, 'text'),
		GetSQLValueString($sexo, 'text'),
		GetSQLValueString($fec_nac, 'text'),
		GetSQLValueString('', 'text'),
		GetSQLValueString('', 'text'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		$id = mysql_insert_id();
		//Query 2
		$sql1 = sprintf('INSERT INTO empleado (per_id, suc_id, emp_profesion, emp_actividad, emp_sueldo, emp_fecha_ingreso, emp_eliminado) VALUES(%s,%s,%s,%s,%s,%s,%s)',
		GetSQLValueString($id, 'int'),
		GetSQLValueString($suc, 'int'),
		GetSQLValueString($prof, 'text'),
		GetSQLValueString($act, 'text'),
		GetSQLValueString($sal, 'text'),
		GetSQLValueString($fec_ing, 'text'),
		GetSQLValueString('N', 'text'));
		$query_2 = mysql_query($sql1, $conexion_mysql);
		$error = mysql_error();
		$id = mysql_insert_id();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1)&&($query_2)){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$id.'] '.$nom;
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error;
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
	}
	
	if($accion=='Eliminar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		$sql = sprintf('UPDATE empleado SET emp_eliminado=%s WHERE emp_id=%s', 
		GetSQLValueString('S', 'text'),
		GetSQLValueString($emp_id, 'int'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		
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
	}	
	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
	header("Location: ".$RUTAm."administrador/thumano-index.php");
?>