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

<h3 align="center" ><strong >Compras de <?php echo $sucursal['suc_nombre'];?> </strong></h3>
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

		    $sql = sprintf("SELECT * FROM cabecera_compras 
			inner join proveedor on proveedor.prov_id=cabecera_compras.prov_id
			WHERE cabecera_compras.suc_id = %s AND cab_com_fecha >= %s AND cab_com_fecha <= %s",
			GetSQLValueString($suc_id, "int"),
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"));
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$row = mysql_fetch_assoc($query);
			$tot_rows = mysql_num_rows($query);

            $sql1 = sprintf("SELECT sum(cab_com_total) AS Total FROM cabecera_compras WHERE cab_com_fecha >= %s AND cab_com_fecha <= %s AND
			cabecera_compras.suc_id = %s",
            GetSQLValueString($fecha_inicio, "text"),
            GetSQLValueString($fecha_fin, "text"),
            GetSQLValueString($suc_id, "int"));
            $query1 = mysql_query($sql1, $conexion_mysql) or die(mysql_error());
            $row1 = mysql_fetch_assoc($query1);
            $total_reporte = $row1['Total'];
			?>
		  
            
            <?php
          if($tot_rows>0){
		 ?>
            	<table border="1">
                	<tr>
                    	<td style="width:100px; text-align:center">Fecha de Compra</td>
                        <td style="width:80px; text-align:center">Proveedor</td>
                        <td style="width:80px; text-align:center">Numero Factura</td>
                        <td style="width:80px; text-align:center">IVA</td>
                        <td style="width:80px; text-align:center">Descuento</td>
                        <td style="width:80px; text-align:center">Total</td>
                    </tr>
                    <?php // echo $row ?>
                     <?php do {
						?>
                    <tr>
                        <td style="text-align:center"><?php echo $row['cab_com_fecha']; ?></td>
                        <td style="text-align:center"><?php echo $row['prov_nom_com'];?></td>
                        <td style="text-align:center"><?php echo $row['cab_com_fac']; ?></td>
                        <td style="text-align:center"><?php echo $row['cab_com_iva']; ?></td>
                        <td style="text-align:center"><?php echo $row['cab_com_des']; ?></td>
                        <td style="text-align:center"><?php echo $row['cab_com_total']; ?></td>
                   </tr>
					<?php } while ($row = mysql_fetch_assoc($query)) ?>
                    
                </table>
                <?php // echo 'El Total Es: '. $total_reporte; ?>
             <?php mysql_free_result($query);
              }else{ echo '<div ><h4>No hay compras registradas.</h4></div>'; }
            ?>
            
<!-- FIN  DEL REPORTE-->

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


