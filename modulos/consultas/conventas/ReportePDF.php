<?php
	if (!isset($_SESSIOxN)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	ob_start();
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	$fecha_inicio=$_GET['fini'];
	$fecha_fin=$_GET['ffin'];	
	$vendedor=$_GET['vendedor'];	
?>
<page>
            <!-- INICIO -->
<div style="padding:70px 0px 0px 50px; border:0px none #FFF; width:700px;">
<label  style="font-size:18px;"><?php
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
?>
</label>

<h3 align="center" ><strong >Ventas de <?php echo $sucursal['suc_nombre'];?> </strong></h3>
	<!-- INICIO -->
    <table border="1" cellpadding="0" cellspacing="0">
    	<tr>
        	<td style="width:328px"><div style="padding:5px 0px 5px 5px;">Fecha de Inicio:  <strong><?php echo $fecha_inicio; ?></strong></div></td>
            <td style="width:328"><div style="padding:5px 0px 5px 5px;">Fecha de Fin:  <strong><?php echo $fecha_fin;?></strong></div></td>
        </tr>
    </table>
    <strong><h5>DETALLE DE ACTUAL</h5></strong>
    <!-- DETALLE DEL REPORTE -->
                <?php
		   $suc_id= $sucursal['suc_id'];
		   
		   if ($vendedor == "") {
				$sql = sprintf("SELECT cab_ven_fecha, pro_nombre, cab_ven_usu, det_ven_can, det_ven_val FROM cabecera_ventas 
			inner join detalle_ventas on detalle_ventas.cab_ven_id=cabecera_ventas.cab_ven_id 
			inner join producto on detalle_ventas.pro_id=producto.pro_id 
WHERE cabecera_ventas.suc_id = %s AND cab_ven_fecha >= %s AND cab_ven_fecha <= %s", 
			GetSQLValueString($suc_id, "int"),
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"));
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$row = mysql_fetch_assoc($query);
			$tot_rows = mysql_num_rows($query); 
			
			$sql1 = sprintf("SELECT SUM(Total) AS Total FROM 
			(SELECT sum(cab_ven_total) AS Total FROM cabecera_ventas WHERE cab_ven_fecha >= %s AND cab_ven_fecha <= %s) AS Total", 			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"));
			$query1 = mysql_query($sql1, $conexion_mysql) or die(mysql_error());
			$row1 = mysql_fetch_assoc($query1);
			$total_reporte = $row1['Total'];
			}
			else
			{
				$sql = sprintf("SELECT cab_ven_fecha, pro_nombre, cab_ven_usu, det_ven_can, det_ven_val FROM cabecera_ventas 
			inner join detalle_ventas on detalle_ventas.cab_ven_id=cabecera_ventas.cab_ven_id 
			inner join producto on detalle_ventas.pro_id=producto.pro_id 
WHERE cabecera_ventas.suc_id = %s AND cab_ven_fecha >= %s AND cab_ven_fecha <= %s AND cab_ven_usu = %s", 
			GetSQLValueString($suc_id, "int"),
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"),
			GetSQLValueString($vendedor, "text"));
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$row = mysql_fetch_assoc($query);
			$tot_rows = mysql_num_rows($query); 
			 
			$sql1 = sprintf("SELECT SUM(Total) AS Total FROM 
			(SELECT sum(cab_ven_total) AS Total FROM cabecera_ventas WHERE cab_ven_fecha >= %s AND cab_ven_fecha <= %s AND cab_ven_usu = %s ) AS Total", 
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"),
			GetSQLValueString($vendedor, "text"));
			$query1 = mysql_query($sql1, $conexion_mysql) or die(mysql_error());
			$row1 = mysql_fetch_assoc($query1);
			$total_reporte = $row1['Total'];
			}

          if($tot_rows>0){
		 ?>
            	<table border="1">
                	<tr>
                    	<td style="width:100px; text-align:center">Fecha de Venta</td>
                        <td style="width:240px; text-align:center">Producto</td>
                        <td style="width:90px; text-align:center">Vendendor</td>
                        <td style="width:80px; text-align:center">Cantidad</td>
                        <td style="width:90px; text-align:center">Total</td>
                    </tr>
                    
                     <?php do {
						?>
                    <tr>
                        <td style="text-align:center"><?php echo $row['cab_ven_fecha']; ?></td>
                        <td><?php echo $row['pro_nombre']; ?></td>
                        <td style="text-align:center"><?php echo $row['cab_ven_usu'];?></td>
                        <td style="text-align:center"><?php echo $row['det_ven_can']; ?></td>
                   		<?php $totven=$row['det_ven_can']*$row['det_ven_val'];?>
                        <td style="text-align:center"><?php echo $totven;?></td>
                   </tr>
					<?php } while ($row = mysql_fetch_assoc($query)) ?>
                    
                </table>
                <?php echo 'El Total Es: '. $total_reporte; ?>
             <?php mysql_free_result($query);
              }else{ echo '<div ><h4>No hay ventas realizadas.</h4></div>'; }
            ?>
            
<!-- FIN  DEL REPORTE Parte 1-->
<hr>

<label> Recargas y otras ventas</label>
<!-- INICIO  DEL REPORTE Parte 2-->

<?php
		   if ($vendedor == "") {
				$sql2 = sprintf("Select rec_fecha, rec_hora, rec_des, per_nombre, '1', rec_val From recargas
				inner join empleado on recargas.emp_id = empleado.emp_id
				inner join persona on empleado.per_id = persona.per_id
				WHERE recargas.suc_id = %s AND rec_fecha >= %s AND rec_fecha <= %s ORDER BY rec_fecha ASC", 
				GetSQLValueString($suc_id, "int"),
				GetSQLValueString($fecha_inicio, "text"),
				GetSQLValueString($fecha_fin, "text"));
				$query2 = mysql_query($sql2, $conexion_mysql) or die(mysql_error());
				$row2 = mysql_fetch_assoc($query2);
				$tot_rows2 = mysql_num_rows($query2); 
			
				$sql3 = sprintf("SELECT SUM(Total) AS Total FROM 
				(SELECT sum(rec_val) AS Total FROM recargas inner join empleado on recargas.emp_id = empleado.emp_id
				WHERE recargas.suc_id = %s AND rec_fecha >= %s AND rec_fecha <= %s) AS Total", 
				GetSQLValueString($suc_id, "int"),
				GetSQLValueString($fecha_inicio, "text"),
				GetSQLValueString($fecha_fin, "text"));
				$query3 = mysql_query($sql3, $conexion_mysql) or die(mysql_error());
				$row3 = mysql_fetch_assoc($query3);
				$total_reporte_recargas = $row3['Total'];
				}
				else
				{
				$sql2 = sprintf("Select rec_fecha, rec_hora, rec_des, per_nombre, '1', rec_val From recargas
				inner join empleado on recargas.emp_id = empleado.emp_id
				inner join persona on empleado.per_id = persona.per_id
				inner join usuarios on empleado.emp_id = usuarios.emp_id
				WHERE recargas.suc_id = %s AND rec_fecha >= %s AND rec_fecha <= %s AND usr_nombre = %s  ORDER BY rec_fecha ASC", 
			GetSQLValueString($suc_id, "int"),
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"),
			GetSQLValueString($vendedor, "text"));
			$query2 = mysql_query($sql2, $conexion_mysql) or die(mysql_error());
			$row2 = mysql_fetch_assoc($query2);
			$tot_rows2 = mysql_num_rows($query2); 
			 
			$sql3 = sprintf("SELECT sum(rec_val) AS Total FROM recargas inner join empleado on recargas.emp_id = empleado.emp_id
			inner join persona on empleado.per_id = persona.per_id
			inner join usuarios on empleado.emp_id = usuarios.emp_id
			WHERE recargas.suc_id = %s AND rec_fecha >= %s AND rec_fecha <= %s AND usr_nombre = %s", 
			GetSQLValueString($suc_id, "int"),
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"),
			GetSQLValueString($vendedor, "text"));
			$query3 = mysql_query($sql3, $conexion_mysql) or die(mysql_error());
			$row3 = mysql_fetch_assoc($query3);
			$total_reporte_recargas = $row3['Total'];
			}
			
          if($tot_rows2>0){
		 ?>
            	<table border="1">
                	<tr>
                    	<td style="width:100px; text-align:center">Fecha de Venta</td>
                        <td style="width:240px; text-align:center">Producto</td>
                        <td style="width:90px; text-align:center">Vendendor</td>
                        <td style="width:80px; text-align:center">Cantidad</td>
                        <td style="width:90px; text-align:center">Total</td>
                    </tr>
                    
                     <?php do {
						?>
                    <tr>
                        <td style="text-align:center"><?php echo $row2['rec_fecha']." ".$row2['rec_hora']; ?></td>
                        <td><?php echo $row2['rec_des']; ?></td>
                        <td style="text-align:center"><?php echo $row2['per_nombre'];?></td>
                        <td style="text-align:center"><?php echo "1"; ?></td>
                   		<?php $totven=$row2['rec_val'];?>
                        <td style="text-align:center"><?php echo $totven;?></td>
                   </tr>
					<?php } while ($row2 = mysql_fetch_assoc($query2)) ?>
                    
                </table>
                <?php echo 'El Total Es: '. $total_reporte_recargas; ?>
             <?php mysql_free_result($query2);
              }else{ echo '<div ><h4>No hay recargas realizadas.</h4></div>'; }
            ?>
            
<!-- FIN  DEL REPORTE Parte 2-->



</div>

</page>
<?php 
$content = ob_get_clean();
require_once(RUTAs.'funciones/html2pdf/html2pdf.class.php');
$pdf = new HTML2PDF('P	','A4','fr','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->Output('tratamientos.pdf'); 
?>


