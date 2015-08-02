<?php
		include ('../../../../start.php');
		fnc_autentificacion();
		
		$accion = $_POST['accion'];
		
		if($accion == 'AGREGAR_PPRODUCTO')
		{
			$pro_cod = $_POST['pro_cod'];
			$suc_id = $_POST['suc_id'];
			$cantidad = $_POST['cantidad'];
			
			$query=sprintf("SELECT pro_id FROM producto WHERE pro_codigo = %s AND suc_id = %s AND pro_eliminado = 'N'",
			GetSQLValueString($pro_cod, "text"),
			GetSQLValueString($suc_id, "int"));
			
			$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
			$row_RS_datos = mysql_fetch_assoc($RS);		
			$pro_id = $row_RS_datos["pro_id"];
	
			$query1=sprintf("SELECT det_pro_id, pro_id, det_pro_costo, val_gan_pvp, est_iva, met_gan_pvp FROM detalle_producto WHERE pro_id = %s AND det_pro_eliminado = 'N'",
			GetSQLValueString($pro_id, "int"));
			$RS1 = mysql_query($query1, $conexion_mysql) or die(mysql_error());
			$row_RS_datos1 = mysql_fetch_assoc($RS1);
			
			
			$query2=sprintf("SELECT stk_cantidad FROM stock WHERE det_pro_id = %s",
			GetSQLValueString($row_RS_datos1['det_pro_id'], "text"));    
			$RS2 = mysql_query($query2, $conexion_mysql) or die(mysql_error());
			$row_RS_datos2 = mysql_fetch_assoc($RS2);		
			$stock = $row_RS_datos2["stk_cantidad"];
			
	
			
			if (!isset($pro_id)) {
				echo "false";
			}
			else
			{
				$test = json_encode($row_RS_datos1);
				echo $test;
			}
		}
		if($accion == 'GUARDAR')
		{
		$test = $_POST['jsarr'];
		$test = stripslashes($test);
		$test = json_decode($test);
		date_default_timezone_set('America/Guayaquil');
		$fecha_actual=date('Y-m-d H:i:s');
		$sucursal = $_POST['sucursal'];
		$usuario = $_POST['usuario'];
		$totcab = $_POST['totalcab'];
		$totiva = $_POST['totiva'];
		$rucpro = $_POST['rucpro'];
		$nompro = $_POST['nompro'];
		$numfac = $_POST['numfac'];
		$feccom = $_POST['feccom'];
		$desc = $_POST['desc'];
		$serie = $_POST['serie'];
		
		
		$queryper=sprintf("SELECT per_id FROM persona WHERE per_documento = %s",
    	GetSQLValueString($rucpro, "text"));  
  		$RSper = mysql_query($queryper, $conexion_mysql) or die(mysql_error());
		$row_RS_datos_per = mysql_fetch_assoc($RSper);		
		$per_id = $row_RS_datos_per["per_id"];
		
		
		$querypro=sprintf("SELECT prov_id FROM proveedor WHERE per_id = %s AND prov_nom_com = %s AND prov_eliminado = 'N'",
    	GetSQLValueString($per_id, "text"),
		GetSQLValueString($nompro, "text"));  
  		$RSpro = mysql_query($querypro, $conexion_mysql) or die(mysql_error());
		$row_RS_datos_pro = mysql_fetch_assoc($RSpro);
		$prov_id = $row_RS_datos_pro["prov_id"];
		
		$query_insert_user = sprintf("INSERT INTO cabecera_compras (suc_id, prov_id, cab_com_iva, cab_com_total, cab_com_fecha, cab_com_usu, cab_com_fac, cab_com_fec_emi, cab_com_des) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($sucursal, "text"),
					   GetSQLValueString($prov_id, "text"),
					   GetSQLValueString($totiva, "text"),
					   GetSQLValueString($totcab, "text"),
					   GetSQLValueString($fecha_actual, "text"),
					   GetSQLValueString($usuario, "text"),
					   GetSQLValueString($numfac, "text"),
					   GetSQLValueString($feccom, "text"),
					   GetSQLValueString($desc, "text"));
					   
			$query = mysql_query($query_insert_user, $conexion_mysql) or die(mysql_error());
			$id=mysql_insert_id();
		
		foreach($test as $key=>$value)
		{
			$pro = $value->producto;
			$can = $value->cantidad;
			$pre = $value->precio;
			$iva = $value->iva;
			$tot = $value->total;
			$idpro = $value->idpro;

			
			$query=sprintf("SELECT * FROM detalle_producto WHERE pro_id = %s AND det_pro_eliminado = 'N'",
    	GetSQLValueString($idpro, "text"));  
  		$RS = mysql_query($query, $conexion_mysql) or die(mysql_error());
		$row_RS_datos = mysql_fetch_assoc($RS);		
		$det_pro_id = $row_RS_datos["det_pro_id"];
		
		
			
			$query_insert_vent_det = sprintf("INSERT INTO detalle_compras (cab_com_id, pro_id, det_com_val_uni, det_com_can, det_com_iva, det_com_tot) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($id, "text"),
					   GetSQLValueString($idpro, "text"),
					   GetSQLValueString($pre, "text"),
					   GetSQLValueString($can, "text"),
					   GetSQLValueString($iva, "text"),
					   GetSQLValueString($tot, "text"));
					   
			$query1 = mysql_query($query_insert_vent_det, $conexion_mysql) or die(mysql_error());
			
			$query2=sprintf("SELECT * FROM stock WHERE det_pro_id = %s AND stk_eliminado = 'N'",
    	GetSQLValueString($det_pro_id, "text"));  
  		$RS2 = mysql_query($query2, $conexion_mysql) or die(mysql_error());
		$row_RS_datos2 = mysql_fetch_assoc($RS2);		
		$stk_cantidad = $row_RS_datos2["stk_cantidad"];
		
		$stk_cantidad_final = $stk_cantidad + $can;
		
		$query_update_stk = sprintf("UPDATE stock set stk_cantidad = %s WHERE det_pro_id = %s AND stk_eliminado = 'N'",
                       GetSQLValueString($stk_cantidad_final, "text"),
					   GetSQLValueString($det_pro_id, "text"));
		$query_3 = mysql_query($query_update_stk, $conexion_mysql) or die(mysql_error());
		
		
		$query4=sprintf("SELECT ser_id FROM serie INNER JOIN detalle_producto on serie.det_pro_id = detalle_producto.det_pro_id WHERE ser_codigo = %s AND ser_eliminado = 'N' AND detalle_producto.det_pro_id = %s",
		GetSQLValueString($serie, "text"),
    	GetSQLValueString($det_pro_id, "text"));
  		$RS4 = mysql_query($query4, $conexion_mysql) or die(mysql_error());
		$num_ser = mysql_num_rows($RS4);
		if($num_ser > 0)
		{
			$query_select_serie=sprintf("SELECT * FROM serie WHERE det_pro_id = %s AND ser_eliminado = %s AND ser_codigo = %s",
    	GetSQLValueString($det_pro_id, "int"),
		GetSQLValueString('N', "text"),
		GetSQLValueString($serie, "text"));
  		$RS_serie = mysql_query($query_select_serie, $conexion_mysql) or die(mysql_error());
		$row_RS_datos_serie = mysql_fetch_assoc($RS_serie);
		$ser_cantidad = $row_RS_datos_serie["ser_cantidad"];
		echo $query_select_serie;
		$ser_cantidad_final = $ser_cantidad + $can;
		echo $ser_cantidad_final;
			$query_update_serie = sprintf("UPDATE serie set ser_cantidad = %s WHERE ser_codigo = %s AND ser_eliminado = 'N' AND det_pro_id = %s",
                       GetSQLValueString($ser_cantidad_final, "text"),
					   GetSQLValueString($serie, "text"),
					   GetSQLValueString($det_pro_id, "text"));
		mysql_query($query_update_serie, $conexion_mysql) or die(mysql_error());
		}
		else
		{
			$query_insert_serie = sprintf("INSERT INTO serie(det_pro_id, ser_codigo, ser_cantidad, ser_eliminado) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($det_pro_id, "int"),
					   GetSQLValueString($serie, "text"),
					   GetSQLValueString($can, "text"),
					   GetSQLValueString('N', "text"));
			mysql_query($query_insert_serie, $conexion_mysql) or die(mysql_error());
		}
		
		//Calcula nuevo costo
		$det_pro_costo=$pre;
		$val_gan_pvp=$row_RS_datos["val_gan_pvp"];
		$met_gan_pvp=$row_RS_datos["met_gan_pvp"];
		$est_iva=$row_RS_datos["est_iva"];
		$pvp = 0;
		$iva = 0;
		$porce =0;
			
		if(($est_iva=='n') && ($met_gan_pvp=='f'))
		{	
			$pvp=$det_pro_costo+$val_gan_pvp;
		}
		if(($est_iva=='n') && ($met_gan_pvp=='p'))
		{
			$porce=($det_pro_costo*$val_gan_pvp)/100;
			$pvp=$det_pro_costo+$porce;
		}
		if(($est_iva=='s') && ($met_gan_pvp=='f'))
		{
			$iva=($det_pro_costo*12)/100;
			$pvp=($det_pro_costo)+($val_gan_pvp)+($iva);
		}
		if(($est_iva=='s') && ($met_gan_pvp=='p'))
		{
			$iva=($det_pro_costo)*12/100;
			$porce=($det_pro_costo*$val_gan_pvp)/100;
			$pvp=($det_pro_costo)+($porce)+($iva);
		}
		
		$pvpfin = str_replace(',', '.', $pvp);
		$costfin = str_replace(',', '.', $det_pro_costo);
		
			if($costfin > $row_RS_datos["det_pro_costo"])
			{
				$query_update_costo = sprintf("UPDATE detalle_producto set pvp = %s, det_pro_costo = %s WHERE det_pro_id = %s",
							   GetSQLValueString($pvpfin, "text"),
							   GetSQLValueString($costfin, "text"),
							   GetSQLValueString($det_pro_id, "text"));
				$querycosto = mysql_query($query_update_costo, $conexion_mysql) or die(mysql_error());
			}
		}
		echo "Compra Realizada Correctamente";
	}
	
	
?>