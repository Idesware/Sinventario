<?php
	include('../../../../start.php');
	fnc_autentificacion();
	if (!isset($_SESSION)) session_start();
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	$fecha_hora = date("Y-m-d H:i:s");
	
	//datos para ka actualización de ajustes
	$suc_id=$_POST['suc_id'];
	$pro_id=$_POST['pro_id'];
	$aju_can=$_POST['cantidad'];
	$usuario=$persona['per_nombre'];
    $tip_id=$_POST['tipo_ajuste'];
	$Stok_det_pro=fnc_datStock($pro_id);
	$stk_id=$Stok_det_pro['stk_id'];
	$aju_motivo=$_POST['aju_motivo'];
	$accion = 'Insertar';
	
	//datos para el detalle de ajustes
	$tip=$_POST['R1'];
	$sqlStok = sprintf("SELECT * FROM stock inner join detalle_producto on stock.det_pro_id = detalle_producto.det_pro_id inner join producto on producto.pro_id = detalle_producto.pro_id where suc_id='".$sucursal['suc_id']."' and producto.pro_id='".$pro_id."'");
	$queryStok = mysql_query($sqlStok, $conexion_mysql) or die(mysql_error());
	$rowStok = mysql_fetch_assoc($queryStok);
	$tot_rowsStok = mysql_num_rows($queryStok);
	$can_anterior =$rowStok['stk_cantidad'];
	
	if($tip=='+')
	{$can_ajustada=($can_anterior + $aju_can);
			
		}else{
			$can_ajustada=($can_anterior - $aju_can);
			}if(($can_ajustada)>=0)
				{$ban=0;}else{$ban=1;}
	if($ban==0){
	if ($accion=="Insertar"){
		mysql_query("SET AUTOCOMMIT=0;", $conexion_mysql); //Desabilita el autocommit
		mysql_query("BEGIN;", $conexion_mysql); //Inicia la transaccion
		
		//Query
		$sql = sprintf('INSERT INTO ajuste (tip_id, stk_id, aju_motivo, aju_can, aju_usu, aju_fecha, aju_eliminado) VALUES (%s,%s,%s,%s,%s,%s,%s)',
		GetSQLValueString($tip_id, 'int'),
		GetSQLValueString($stk_id, 'int'),
		GetSQLValueString($aju_motivo, 'text'),
		GetSQLValueString($aju_can, 'int'),
		GetSQLValueString($usuario, 'text'),
		GetSQLValueString($fecha_hora, 'text'),
		GetSQLValueString('N', 'text'));
		$query = mysql_query($sql, $conexion_mysql); 
		$error1 = mysql_error();
		$id_det_aju = mysql_insert_id();
		//Query 2
		$sql2 = sprintf('INSERT INTO detalle_ajuste (aju_id, aju_tipo, can_anterior, can_nueva, can_ajustada) VALUES (%s,%s,%s,%s,%s)',
		GetSQLValueString($id_det_aju, 'int'),
		GetSQLValueString($tip, 'text'),
		GetSQLValueString($can_anterior, 'text'),
		GetSQLValueString($aju_can, 'text'),
		GetSQLValueString($can_ajustada, 'text'));
		$query2 = mysql_query($sql2, $conexion_mysql); 
		$error2 = mysql_error();
		$id2 = mysql_insert_id();
		
		//Query 3
		$sql3 = sprintf('UPDATE stock SET stk_cantidad=%s WHERE stk_id=%s',
		GetSQLValueString($can_ajustada, 'text'),
		GetSQLValueString($stk_id, 'int'));
		$query3 = mysql_query($sql3, $conexion_mysql); 
		$error3 = mysql_error();

		//Si no hubo errores COMMIT caso contrario ROLLBACK
		if(($query) && ($query2) && ($query3)){
			mysql_query("COMMIT;", $conexion_mysql);
			$MSG = 'Insertado exitosamente.';
			$MSGdes = '[ID: '.$stk_id.'] '.$rowStok["pro_nombre"];
			$MSGimg = $RUTAi.'ok.png';
		}else{
			mysql_query("ROLLBACK;", $conexion_mysql);
			$MSG = 'Error al insertar.';
			$MSGdes = 'Llene o selecione todos los campos';
			$MSGimg = $RUTAi.'delete.png';
		}
		mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit
	}
	}
	else
	{ 
		$MSG = 'Ajuste no autorizado';
		$MSGdes = 'El ajuste no puede ser menor que el stcok actual';
		$MSGimg = $RUTAi.'delete.png';

	}
	mysql_query("SET AUTOCOMMIT=1;", $conexion_mysql); //Habilita el autocommit	
	$_SESSION['MSG'] = $MSG;
	$_SESSION['MSGdes'] = $MSGdes;
	$_SESSION['MSGimg'] = $MSGimg;
	//echo 'el codigo de producto es: '.$can_ajustada;
	header("Location: ".$RUTAm."administrador/ajustes-stock/index.php");	

?>