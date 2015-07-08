<?php 
  if (!isset($_SESSION)) session_start();
  include('../../start.php');
  fnc_autentificacion();
  $id_user = $_SESSION['id_usuario'];
  $id_emp = $_SESSION['id_empleado'];
  $empleado = fnc_datEmp($id_emp);
  $persona = fnc_datPer($empleado['per_id']);
  $sucursal = fnc_datSuc($empleado['suc_id']);


  function date_ame2euro($date=NULL){ if(!$date) $datef=date('d-m-Y');
  else $datef=date("d-m-Y",strtotime($date)); 
  return $datef;
   }

?>

<html>
<head><title>Comprobante de Venta</title></head>
<body onload= window.print()>
  <?php $referencia = $_POST['REF'];?>
  <?php $nomcli = $_POST['NOMCLI'];?>
<h3>**********<?php echo $sucursal ['suc_nombre'] ?>**********</h3>
<label>Dir: <?php echo $sucursal ['suc_direccion'] ?></label>
</div>
<div>
Ticket Referencia N* <?php echo $referencia; ?> 
</div>
Cliente: <?php echo $nomcli; ?>
<br>
<label>Telefono: <?php echo $sucursal ['suc_telefono'] ?></label>
<br>
    =======================================

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
    
          <table style="width: 100%">
            <caption>Detalle Venta</caption>
          
            <thead>
              <tr>
                <th style="width:10%">Cantidad</th>
                <th style="width:44%">Producto</th>
                <th style="width:21%">Precio U.</th>
                <th style="width:25%">Total</th>
              </tr>
            </thead>

            <tfoot>
              <tr>
                <th colspan="4" align="right">TOTAL:<?php echo $row['cab_ven_total']; ?></th>
              </tr>
		<tr>
                <th colspan="4" align="center">GRACIAS POR PREFERIRNOS</th>
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
          =======================================
          <br>
         
</body>
</html>

