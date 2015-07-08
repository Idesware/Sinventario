<?php
	include('../../../../start.php');
	fnc_autentificacion();
	$razon=$_POST['razon'];
	$accion=$_POST['accion'];
	$id=$_POST['id'];

	//Tipo de razon
		$sqltip= sprintf("SELECT * FROM tipos WHERE tip_des = '".$razon."'");
		$querytip= mysql_query($sqltip, $conexion_mysql) or die(mysql_error());
		$row = mysql_fetch_assoc($querytip);
		$tot_rows = mysql_num_rows($querytip); 
	if(($tot_rows==0)&&($accion=="Insertar")){
	if ($accion=="Insertar"){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		
		//Query
		$sql = sprintf('INSERT INTO tipos (tip_des, tip_tabla, tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString($razon, 'text'),
		GetSQLValueString('ajuste', 'text'),
		GetSQLValueString('N', 'text'));
		$query = mysql_query($sql, $conexion_mysql); 
		$error = mysql_error();
		$id = mysql_insert_id();
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$id.'] '.$razon;
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error.' Razón Duplicada ';
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
		}
	}
	else{
		echo 'Error;';
		}

	if ($accion=="Eliminar"){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		
		//Query
		$sql = sprintf('DELETE FROM tipos WHERE tip_id=%s',
		GetSQLValueString($id, 'int'));
		$query = mysql_query($sql, $conexion_mysql); 
		$error = mysql_error();
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if($query){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Eliminado exitosamente.';
			$MSGdes = '[ID: '.$id.'] ';
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error.' Error con registro';
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
		}

	echo 'ok';
	mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit	
	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
	header("Location: ".$RUTAm."administrador/tipo-stock/msg.php");	
?>