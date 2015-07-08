<?php 
	include('../../../start.php');
	fnc_autentificacion();
	$id_usr = fnc_varGetPost('user_id');
	$id_emp = $_POST['emp'];
	$usr_nom = $_POST['usu'];
	$usr_pass = $_POST['pass'];

	$accion = fnc_varGetPost('accion');
	$menus = $_POST['menus'];
	$contMenus = count($menus);

	if($accion=='Actualizar'){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		
		$usuario = fnc_datUsu($id_usr); 
		if(($usr_pass != $usuario['usr_contrasena'])){ //Si la password se ha mdoficado entonces guardara en md5, si no, no.
			$usr_pass = md5($usr_pass);
		}

		//Query 1
		$sql = sprintf('UPDATE usuarios SET usr_nombre=%s, usr_contrasena=%s WHERE usr_id=%s',//***********
		GetSQLValueString($usr_nom, 'text'),
		GetSQLValueString($usr_pass, 'text'),
		GetSQLValueString($id_usr, 'int'));
		$query_1 = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		//Query 2
		$sql1 = sprintf('DELETE FROM menu_usuario WHERE usr_id=%s', GetSQLValueString($id_usr, 'int'));
		$query_2 = mysql_query($sql1, $conexion_mysql) or die(mysql_error());
		//Query 3
		for($x=0; $x<$contMenus; $x++){
			$sql = sprintf('INSERT INTO menu_usuario (usr_id, men_id) VALUES (%s, %s)',
			GetSQLValueString($id_usr,'int'),
			GetSQLValueString($menus[$x],'int'));
			$query_3 = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		}
		
		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1)&&($query_2)&&($query_3)){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Actualizado exitosamente.';
			$MSGdes = '[ID: '.$id_usr.'] '.$usr_nom;
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
		$sql = sprintf('INSERT INTO usuarios (emp_id, usr_nombre, usr_contrasena, usr_eliminado) VALUES (%s,%s,%s,%s)',
		GetSQLValueString($id_emp, 'int'),
		GetSQLValueString($usr_nom, 'text'),
		GetSQLValueString(md5($usr_pass), 'text'),
		GetSQLValueString('N', 'text'));
		$query_1 = mysql_query($sql, $conexion_mysql); $error=mysql_error();
		//Query 2
		$id = mysql_insert_id();
		for($x=0; $x<$contMenus; $x++){
			$sql = sprintf('INSERT INTO menu_usuario (usr_id, men_id) VALUES (%s, %s)',
			GetSQLValueString($id,'int'),
			GetSQLValueString($menus[$x],'int'));
			$query_2 = mysql_query($sql, $conexion_mysql);
		}

		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1)&&($query_2)){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$id.'] '.$usr_nom;
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

		//Query 1
		$sql = sprintf('DELETE FROM menu_usuario WHERE usr_id=%s', GetSQLValueString($id_usr, 'int'));
		$query_1 = mysql_query($sql, $conexion_mysql)or die(mysql_error());
		//Query 2
		$sql2 = sprintf('DELETE FROM usuarios WHERE usr_id=%s', GetSQLValueString($id_usr, 'int'));
		$query_2 = mysql_query($sql2, $conexion_mysql)or die(mysql_error());

		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1) && ($query_2)){
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
	header("Location: ".$RUTAm."administrador/usuarios/index.php");
?>