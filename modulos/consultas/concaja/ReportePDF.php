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

<h3 align="center" ><strong >Movimientos de Caja de <?php echo $sucursal['suc_nombre'];?> </strong></h3>
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
				$sql = sprintf("SELECT mov_caj_fecha,mov_caj_usuario, tip_des,mov_caj_val FROM movimientos_caja inner join tipos on movimientos_caja.tip_id=tipos.tip_id  where mov_caj_fecha >= %s AND mov_caj_fecha <= %s AND tip_tabla= 'MOVIMIENTOS_CAJA' order by mov_caj_fecha ASC",
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"));
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$row = mysql_fetch_assoc($query);
			$tot_rows = mysql_num_rows($query); 
			}
			else
			{
				$sql = sprintf("SELECT mov_caj_fecha,mov_caj_usuario, tip_des,mov_caj_val FROM movimientos_caja inner join tipos on movimientos_caja.tip_id=tipos.tip_id  where mov_caj_fecha >= %s AND mov_caj_fecha <= %s AND mov_caj_usuario=%s AND
tip_tabla='MOVIMIENTOS_CAJA' order by mov_caj_fecha ASC", 
			GetSQLValueString($fecha_inicio, "text"),
			GetSQLValueString($fecha_fin, "text"),
			GetSQLValueString($vendedor, "text"));
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$row = mysql_fetch_assoc($query);
			$tot_rows = mysql_num_rows($query); 
			}
				?>
            <?php
          if($tot_rows>0){
		 ?>
            	<table border="1">
                	<tr>
                    	<td style="width:100px; text-align:center">Fecha del Movimiento</td>
                        <td style="width:240px; text-align:center">Empleado</td>
                        <td style="width:90px; text-align:center">Tipo de Transacción</td>
                        <td style="width:90px; text-align:center">Total</td>
                    </tr>
                     <?php do {
						?>
                    <tr>
                        <td style="text-align:center"><?php echo $row['mov_caj_fecha']; ?></td>
                        <td style="text-align:center"><?php echo $row['mov_caj_usuario'];?></td>
                        <td style="text-align:center"><?php echo $row['tip_des']; ?></td>
                        <td style="text-align:center"><?php echo $row['mov_caj_val']; ?></td>
                   </tr>
					<?php } while ($row = mysql_fetch_assoc($query)) ?>
                </table>
             <?php 
			  mysql_free_result($query);
              }else{ echo '<div ><h4>No hay movimientos en caja.</h4></div>'; }
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
$pdf->Output('index.pdf'); 
?>


