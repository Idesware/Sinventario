<?php
	if (!isset($_SESSIOxN)) session_start();
	include('../../start.php');
	fnc_autentificacion();
	ob_start();
  $referencia=$_SESSION["aux"];
  $referencia1=$_POST["referencia"];
	
	echo $_SESSION["aux"];
	
$sql = sprintf("SELECT cab_ven_subt,cab_ven_iva,cab_ven_des,cab_ven_total,cab_ven_fecha,per_nombre,det_ven_can,det_ven_val,pro_nombre,pvp,per_documento,per_direccion1,per_telefono 
FROM `cabecera_ventas` 
INNER JOIN cliente ON cabecera_ventas.cli_id=cliente.cli_id
INNER JOIN persona ON cliente.per_id=persona.per_id
INNER JOIN detalle_ventas ON cabecera_ventas.cab_ven_id=detalle_ventas.cab_ven_id
INNER JOIN producto ON detalle_ventas.pro_id=producto.pro_id
INNER JOIN detalle_producto ON producto.pro_id=detalle_producto.pro_id
WHERE cab_ven_ref = %s",
  
  GetSQLValueString($referencia, 'int'));
  $query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
  $row = mysql_fetch_assoc($query);
  $tot_rows = mysql_num_rows($query);
?>
<page>

<style type="text/css">
div.cabecera {
    position: absolute;    
	top: 235px;
	margin-left: 30px;
}
div.detalle {
    position: absolute;   
	top: 330px;
	margin-left: 30px;
}
div.objetivo {
    position: absolute;  
	top: 800px;
	margin-left: 30px;
}
div.pie {
    position: absolute;   
	top: 920px;
}

</style>
           <!-- cabecera factura -->

<div class="cabecera">
<table style="width: 650px;">
<tbody>
    <tr> 
    <td style="width: 50px;">Fecha:</td> 
    <td style="width: 420px;"><?php echo $row['cab_ven_fecha']; ?></td> 
    <td style="width: 50px;"></td>
    <td style="width: 130px;"></td> 
    </tr>
    <tr> 
    <td>Cliente:</td> 
    <td><?php echo $row['per_nombre']; ?></td> 
    <td>R.U.C./C.I.:</td> 
    <td><?php echo $row['per_documento']; ?></td>
    </tr>
    <tr> 
    <td>Direccion: </td> 
    <td><?php echo $row['per_direccion1']; ?></td> 
    <td>Telefono:</td> 
    <td><?php echo $row['per_telefono']; ?></td> 
    </tr>
</tbody> 
</table>
</div>
<!-- Observaciones factura -->
<div class="objetivo">
<table style="width: 650px;">
<tbody>
    <tr> 
    <td style="width: 550px;"></td> 
    <td style="width: 100px;" align="right"><?php echo $row['cab_ven_subt']; ?></td> 
    </tr>
    <tr> 
    <td></td> 
    <td></td> 
    </tr>
    <tr> 
    <td></td> 
    <td></td> 
    </tr>
    <tr> 
    <td></td> 
    <td></td> 
    </tr>
    <tr> 
    <td></td> 
    <td></td> 
    </tr>
    <tr> 
    <td align="right">12</td> 
    <td align="right"><?php echo $row['cab_ven_iva']; ?></td> 
    </tr>        
    <tr> 
    <td></td> 
    <td align="right"><?php echo $row['cab_ven_total']; ?></td> 
    </tr>
</tbody> 
</table>
</div>
<!-- detalle factura -->

<div class="detalle">    
          <table style="width: 650px;">                    
            <thead>
              <tr>
                <th style="width:80px;"></th>
                <th style="width:430px;"></th>
                <th style="width:70px;"></th>
                <th style="width:70px;";></th>
              </tr>
            </thead>                       
            <tbody>
              <?php do { ?>
                <tr>
                  <td align="center"><?php echo $row['det_ven_can']; ?></td>
                  <td><?php echo $row['pro_nombre']; ?></td>
                  <td align="center"><?php echo $row['pvp']; ?></td>
                  <td align="center"><?php echo $row['pvp'] * $row['det_ven_can']; ?></td>
                </tr>
              <?php } while ($row = mysql_fetch_assoc($query)); ?>
            </tbody>
          </table>
</div>

<!-- pie factura -->

<div class="pie">
<table style="width: 650px;">
<tbody>
    <tr> 
    <td style="width: 55px;"></td> 
    <td style="width: 250px;" align="center">_____________________</td> 
    <td style="width: 90px;"></td>
    <td style="width: 250px;" align="center">_____________________</td> 
    <td style="width: 5px;"></td>
    </tr>
    <tr> 
    <td></td> 
    <td align="center">FIRMA AUTORIZADA</td> 
    <td></td> 
    <td align="center">FIRMA CLIENTE</td>
    <td></td>
    </tr>
</tbody> 
</table>
</div>

</page>
<?php 
$content = ob_get_clean();
require_once(RUTAs.'funciones/html2pdf/html2pdf.class.php');
$pdf = new HTML2PDF('P	','A4','fr','UTF-8');
$pdf->writeHTML($content);
$pdf->pdf->IncludeJS('print(TRUE)');
$pdf->Output('factura.pdf'); 
?>


