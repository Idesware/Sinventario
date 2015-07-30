<?php 
  if (!isset($_SESSION)) session_start();
  include('../../start.php');
  fnc_autentificacion();
  $id_user = $_SESSION['id_usuario'];
  $id_emp = $_SESSION['id_empleado'];
  $empleado = fnc_datEmp($id_emp);
  $persona = fnc_datPer($empleado['per_id']);
  $sucursal = fnc_datSuc($empleado['suc_id']);
  $fac_fec = $_POST["fec_fac"];
  $dir_cli = $_POST["dir_cli"];
  $tel_cli = $_POST["tel_cli"];
  $ced_cli = $_POST["ced_cli"];
  $nomcli = $_POST['NOMCLI'];
?>

<html>
<head><title>FACTURA</title></head>
<link rel="stylesheet" type="text/css" href="formato.css">
<!--<body onload= window.print()>-->
  <?php $referencia = $_POST['REF'];?>
  
 <!--
<h3>**********<?php echo $sucursal ['suc_nombre'] ?>**********</h3>
<label>Dir: <?php echo $sucursal ['suc_direccion'] ?></label>
</div>
<div>
Ticket Referencia N* <?php echo $referencia; ?> 
<label>Telefono: <?php echo $sucursal ['suc_telefono'] ?></label>
</div>
-->
<p class="p1"><strong>Fecha:</strong><?php echo $fac_fec; ?></p>
<p class="p2"><strong>Cliente:</strong> <?php echo $nomcli; ?></p>
<p class="p3"><strong>Direccion:</strong> <?php echo $dir_cli; ?></p>
<p class="p4"><strong>R.U.C./C.I.:</strong> <?php echo $tel_cli; ?></p>
<p class="p5"><strong>Telefono:</strong> <?php echo $ced_cli; ?></p>
<p class="p6">_______________________</p>
<p class="p7">_______________________</p>
<p class="p8">FIRMA AUTORIZADA</p>
<p class="p9">FIRMA CLIENTE</p>



<?php  
  $sql = sprintf("SELECT cab_ven_subt,cab_ven_iva,cab_ven_des,cab_ven_total,cab_ven_fecha,per_nombre,det_ven_can,det_ven_val,pro_nombre,pvp,per_documento 
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
          <table class="detalle">                     
            <thead>
              <tr>
                <th style="width:10%">Unidad</th>
                <th style="width:44%">Cantidad</th>
                <th style="width:21%">valor un</th>
                <th style="width:25%">valor total</th>
              </tr>
            </thead>

            <tfoot>
              <tr>
                <th colspan="4" align="right">TOTAL:<?php echo $row['cab_ven_total']; ?></th>
              </tr>
		<tr>
                <th colspan="4" align="center"></th>
              </tr>
            </tfoot>
           
            <tbody>
              <?php do { ?>
                <tr>
                  <td style="width:10%" align="center"><?php echo $row['det_ven_can']; ?></td>
                  <td style="width:44%" align="center"><?php echo substr($row['pro_nombre'], 0, 10); ?></td>
                  <td style="width:21%" align="center"><?php echo $row['pvp']; ?></td>
                  <td style="width:25%" align="center"><?php echo $row['pvp'] * $row['det_ven_can']; ?></td>
                </tr>
              <?php } while ($row = mysql_fetch_assoc($query)); ?>
            </tbody>
          </table>
        
          <br>     
</body>
</html>

