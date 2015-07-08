<?php 
	include('../../../../start.php');
	fnc_autentificacion();
	$per_id = fnc_varGetPost('idPer');
	$datEmpleado = fnc_datEmp($emp_id);
	$accion = fnc_varGetPost('accion');
	
	$ruc=$_POST['per_documento'];
	$razsoc=$_POST['per_nombre'];
	$dir1=$_POST['per_direccion1'];
	$mail=$_POST['per_mail'];
	$tel=$_POST['per_telefono'];

	$nom_comercial=$_POST['prov_nom_com'];
	$estpro=$_POST['prov_estado'];
	$nom_contacto=$_POST['prov_nom_cont'];
	$cel_contacto=$_POST['prov_cel_cont'];
	

	$existPer=$_POST['existPer'];
	$perId=$_POST['perId'];
	
	$sql = sprintf("SELECT * FROM persona WHERE per_documento = %s", 
	GetSQLValueString($ruc, "text"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);
	
	


	if($accion=='Actualizar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		//Query 1
		$sql = sprintf('UPDATE persona SET per_documento=%s, per_nombre=%s, per_direccion1=%s, per_mail=%s, per_telefono=%s WHERE per_id=%s',
		GetSQLValueString($ruc, 'text'),
		GetSQLValueString($razsoc, 'text'),
		GetSQLValueString($dir1, 'text'),
		GetSQLValueString($mail, 'text'),
		GetSQLValueString($tel, 'text'),
		GetSQLValueString($perId, 'int'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error = mysql_error();

		//Query 2
		$sql1 = sprintf('UPDATE proveedor SET prov_nom_com=%s, prov_estado=%s, prov_nom_cont=%s, prov_cel_cont=%s, prov_eliminado =%s WHERE per_id=%s',
		GetSQLValueString($nom_comercial, 'text'),
		GetSQLValueString($estpro, 'text'),
		GetSQLValueString($nom_contacto, 'text'),
		GetSQLValueString($cel_contacto, 'text'),
		GetSQLValueString('N', 'text'),
		GetSQLValueString($perId, 'int'));
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

	if($tot_rows==0){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		if($existPer==NULL){
		
		//Query 2
		$sql1 = sprintf('INSERT INTO persona (per_documento, per_nombre, per_direccion1, per_mail, per_telefono) VALUES (%s, %s, %s, %s, %s)',
		GetSQLValueString($ruc, 'text'),
		GetSQLValueString($razsoc, 'text'),
		GetSQLValueString($dir1, 'text'),
		GetSQLValueString($mail, 'text'),
		GetSQLValueString($tel, 'text'));
		$query_1 = mysql_query($sql1, $conexion_mysql);
		$error = mysql_error();
		$id = mysql_insert_id();
		
		//Query 1
		$sql2 = sprintf('INSERT INTO proveedor (prov_nom_com, prov_estado, prov_nom_cont, prov_cel_cont, prov_eliminado, per_id) VALUES(%s,%s,%s,%s,%s,%s)',
		GetSQLValueString($nom_comercial, 'text'),
		GetSQLValueString($estpro, 'text'),
		GetSQLValueString($nom_contacto, 'text'),
		GetSQLValueString($cel_contacto, 'text'),
		GetSQLValueString('N', 'text'),
		GetSQLValueString($id, 'int'));
		$query_2 = mysql_query($sql2, $conexion_mysql);
		$error = mysql_error();
		
		
		}else{
			$id=$perId;
			$query_1=true; }

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
	}else{
		$MSG = 'Error al insertar';
		$MSGdes = "Proveedor ya Registrado";
		$MSGimg = $RUTAi.'delete.png';
	}
}

	
	


	if($accion=='Eliminar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		$sql = sprintf('UPDATE proveedor SET  prov_eliminado=%s WHERE prov_id=%s', 
		GetSQLValueString('S', 'text'),
		GetSQLValueString($prov_id, 'int'));
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
	header("Location: ".$RUTAm."administrador/proveedores/index.php");
?>