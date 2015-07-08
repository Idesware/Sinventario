<?php 
	include('../../../../start.php');
	fnc_autentificacion();
	$emp_id = fnc_varGetPost('idEmp');
	$datEmpleado = fnc_datEmp($emp_id);
	$accion = fnc_varGetPost('accion');
	$suc=$_POST['suc'];
	$doc=$_POST['doc'];
	$nom=$_POST['nom'];
	$dir1=$_POST['dir1'];
	$mail=$_POST['mail'];
	$tel=$_POST['tel'];
	$fec_ing=$_POST['fec_ing'];
	$existPer=$_POST['existPer'];
	$perId=$_POST['perId'];

		
	
	$sql = sprintf("SELECT * FROM persona INNER JOIN empleado on persona.per_id = empleado.per_id where persona.per_documento=%s and empleado.suc_id=%s", 
	GetSQLValueString($doc, "text"),
	GetSQLValueString($suc, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 

	mysql_free_result($query);
	
		
	if($accion=='Actualizar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		//Query 1
		$sql = sprintf('UPDATE persona SET per_nombre=%s, per_documento=%s, per_direccion1=%s, per_mail=%s, per_telefono=%s WHERE per_id=%s',
		GetSQLValueString($nom, 'text'),
		GetSQLValueString($doc, 'text'),
		GetSQLValueString($dir1, 'text'),
		GetSQLValueString($mail, 'text'),
		GetSQLValueString($tel, 'text'),
		GetSQLValueString($row['per_id'], 'int'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		
		//Query 2
		$sql1 = sprintf('UPDATE empleado SET suc_id=%s, emp_fecha_ingreso=%s WHERE emp_id=%s',
		GetSQLValueString($suc, 'int'),
		GetSQLValueString($fec_ing, 'text'),
		GetSQLValueString($emp_id, 'int'));
		$query_2 = mysql_query($sql1, $conexion_mysql);
		$error = mysql_error();
			echo $row['per_id'];
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

		if($existPer==NULL){
		//Query 1
		$sql = sprintf('INSERT INTO persona (per_nombre, per_documento, per_direccion1, per_mail,per_telefono) VALUES (%s, %s, %s, %s, %s)',
		GetSQLValueString($nom, 'text'),
		GetSQLValueString($doc, 'text'),
		GetSQLValueString($dir1, 'text'),
		GetSQLValueString($mail, 'text'),
		GetSQLValueString($tel, 'text'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();
		$id = mysql_insert_id();
		}else{
			$id=$perId;
			$query_1=true; }
		
		//Query 2
		$sql1 = sprintf('INSERT INTO empleado (per_id, suc_id, emp_fecha_ingreso, emp_eliminado) VALUES(%s,%s,%s,%s)',
		GetSQLValueString($id, 'int'),
		GetSQLValueString($suc, 'int'),
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
	header("Location: ".$RUTAm."administrador/empleados/index.php");

?>