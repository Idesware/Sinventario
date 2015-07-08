<?php 
	include('start.php');
	
	$nom_empr=$_POST['nom_empr'];
	$dir_empr=$_POST['dir_empr'];
	$tel_empr=$_POST['tel_empr'];
	$nom_admin=$_POST['nom_admin'];
	$ced_admin=$_POST['ced_admin'];
	$dir_admin=$_POST['dir_admin'];
	$tel_admin=$_POST['tel_admin'];
	$email_admin=$_POST['email_admin'];
	$nom_usu=$_POST['nom_usu'];
	$con_pass_usu=$_POST['con_pass_usu'];
	$accion = fnc_varGetPost('accion');

	if($accion=='Insertarconfig'){

		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion

		//Query 1
		$sql = sprintf('INSERT INTO sucursal (suc_nombre,suc_direccion,suc_telefono,suc_eliminado) VALUES (%s,%s,%s,%s)',
		GetSQLValueString($nom_empr, 'text'),
		GetSQLValueString($dir_empr, 'text'),
		GetSQLValueString($tel_empr, 'text'),
		GetSQLValueString('N', 'text'));
		$query_1 = mysql_query($sql, $conexion_mysql);
		$error .= mysql_error();
		$id_suc = mysql_insert_id();

		//Query 2
		$sql1 = sprintf('INSERT INTO persona (per_nombre,per_documento,per_direccion1,per_mail,per_telefono) VALUES (%s,%s,%s,%s,%s)',
		GetSQLValueString($nom_admin, 'text'),
		GetSQLValueString($ced_admin, 'text'),
		GetSQLValueString($dir_admin, 'text'),
		GetSQLValueString($email_admin, 'text'),
		GetSQLValueString($tel_admin, 'text'));
		$query_2 = mysql_query($sql1, $conexion_mysql);
		$error .= mysql_error();
		$id_per = mysql_insert_id();

		//Query 3
		$sql2 = sprintf('INSERT INTO empleado (per_id,suc_id,emp_fecha_ingreso,emp_eliminado) VALUES (%s,%s,%s,%s)',
		GetSQLValueString($id_per, 'text'),
		GetSQLValueString($id_suc, 'text'),
		GetSQLValueString($sdate, 'text'),
		GetSQLValueString('N', 'text'));
		$query_3 = mysql_query($sql2, $conexion_mysql);
		$error .= mysql_error();
		$id_emp = mysql_insert_id();

		//Query 4
		$sql3 = sprintf('INSERT INTO usuarios (emp_id,usr_nombre,usr_contrasena,usr_eliminado) VALUES (%s,%s,%s,%s)',
		GetSQLValueString($id_emp, 'text'),
		GetSQLValueString($nom_usu, 'text'),
		GetSQLValueString(md5($con_pass_usu), 'text'),
		GetSQLValueString('N', 'text'));
		$query_4 = mysql_query($sql3, $conexion_mysql);
		$error .= mysql_error();
		$id_usr = mysql_insert_id();

		//Query 5
		$sql4 = sprintf('INSERT INTO menu_usuario (usr_id,men_id) VALUES (%s,%s)',
		GetSQLValueString($id_usr, 'text'),
		GetSQLValueString('2', 'text'));
		$query_5 = mysql_query($sql4, $conexion_mysql);
		$error .= mysql_error();

		//Query 6
		$sql5 = sprintf('INSERT INTO menu_usuario (usr_id,men_id) VALUES (%s,%s)',
		GetSQLValueString($id_usr, 'text'),
		GetSQLValueString('5', 'text'));
		$query_6 = mysql_query($sql5, $conexion_mysql);
		$error .= mysql_error();

		//Query 7
		$sql6 = sprintf('INSERT INTO caja (caj_nombre,suc_id,caj_valor,caj_estado,caj_eliminado) VALUES (%s,%s,%s,%s,%s)',
		GetSQLValueString('Caja 1', 'text'),
		GetSQLValueString($id_suc, 'text'),
		GetSQLValueString('0', 'text'),
		GetSQLValueString('C', 'text'),
		GetSQLValueString('N', 'text'));
		$query_7 = mysql_query($sql6, $conexion_mysql);
		$error .= mysql_error();		

		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1)&&($query_2)&&($query_3)&&($query_4)&&($query_5)&&($query_6)&&($query_7)){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			//$MSGdes = '[ID: '.$id.'] '.$nom;
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = $error;
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
	}	
?>