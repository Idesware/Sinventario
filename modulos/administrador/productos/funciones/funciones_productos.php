<?php 
	include('../../../../start.php');
	fnc_autentificacion();
	$emp_id = fnc_varGetPost('idEmp');
	$datEmpleado = fnc_datEmp($emp_id);
	$accion = fnc_varGetPost('accion');
	$cat=$_POST['cat'];
	$uni=$_POST['uni'];
	

	if($accion=='InsertarCategoria'){

		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		
		//Query 1
		$sql = sprintf('INSERT INTO categoria_producto (cat_nom,cat_status) VALUES (%s,%s)',
		GetSQLValueString($cat, 'text'),
		GetSQLValueString('N', 'text'));
		
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		$id = mysql_insert_id();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query_1){
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


	if($accion=='InsertarUnidad'){

		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		
		//Query 1
		$sql = sprintf('INSERT INTO unidad_producto (unidad_nom,unidad_status) VALUES (%s,%s)',
		GetSQLValueString($uni, 'text'),
		GetSQLValueString('N', 'text'));
		
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		$id = mysql_insert_id();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query_1){
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



	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
?>