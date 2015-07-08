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

		//Query 8
		$sql7 = sprintf('INSERT INTO persona (per_nombre,per_documento,per_direccion1,per_mail,per_telefono) VALUES (%s,%s,%s,%s,%s)',
		GetSQLValueString('Consumidor Final', 'text'),
		GetSQLValueString('001', 'text'),
		GetSQLValueString('Principal', 'text'),
		GetSQLValueString('consumidor@final.com', 'text'),
		GetSQLValueString('000-000', 'text'));
		$query_8 = mysql_query($sql7, $conexion_mysql);
		$error .= mysql_error();
		$id_consumidor = mysql_insert_id();

		//Query 9
		$sql8 = sprintf('INSERT INTO cliente (per_id,cli_est,cli_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString($id_consumidor, 'text'),
		GetSQLValueString('A', 'text'),
		GetSQLValueString('N', 'text'));
		$query_9 = mysql_query($sql8, $conexion_mysql);
		$error .= mysql_error();

		//Query 10
		$sql9 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('Compras', 'text'),
		GetSQLValueString('ajuste', 'text'),
		GetSQLValueString('N', 'text'));
		$query_10 = mysql_query($sql9, $conexion_mysql);
		$error .= mysql_error();
		//Query 10
		$sql10 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('Deposito', 'text'),
		GetSQLValueString('movimientos_caja', 'text'),
		GetSQLValueString('N', 'text'));
		$query_11 = mysql_query($sql10, $conexion_mysql);
		$error .= mysql_error();
		
		$sql11 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('Egreso', 'text'),
		GetSQLValueString('movimientos_caja', 'text'),
		GetSQLValueString('N', 'text'));
		$query_12 = mysql_query($sql11, $conexion_mysql);
		$error .= mysql_error();				

		$sql12 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('Ingreso por venta', 'text'),
		GetSQLValueString('movimientos_caja', 'text'),
		GetSQLValueString('N', 'text'));
		$query_13 = mysql_query($sql12, $conexion_mysql);
		$error .= mysql_error();

		$sql13 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('Abono', 'text'),
		GetSQLValueString('movimientos_caja', 'text'),
		GetSQLValueString('N', 'text'));
		$query_14 = mysql_query($sql13, $conexion_mysql);
		$error .= mysql_error();

		$sql14 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('promocion', 'text'),
		GetSQLValueString('Ajuste', 'text'),
		GetSQLValueString('N', 'text'));
		$query_15 = mysql_query($sql14, $conexion_mysql);
		$error .= mysql_error();

		$sql15 = sprintf('INSERT INTO tipos (tip_des,tip_tabla,tip_eliminado) VALUES (%s,%s,%s)',
		GetSQLValueString('Cambio de Unidad', 'text'),
		GetSQLValueString('Ajuste', 'text'),
		GetSQLValueString('N', 'text'));
		$query_16 = mysql_query($sql15, $conexion_mysql);
		$error .= mysql_error();

		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query_1)&&($query_2)&&($query_3)&&($query_4)&&($query_5)&&($query_6)&&($query_7)&&($query_8)&&($query_9)&&($query_10)&&($query_11)&&($query_12)&&($query_13)&&($query_14)&&($query_15)&&($query_16)){
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