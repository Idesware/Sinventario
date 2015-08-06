<?php
include('../../../start.php');
$det_pro_id=$_GET["det_pro"];
    
    $query_hay_suc = "select producto.pro_id,ser_id,pro_nombre,ser_codigo,ser_cantidad
	from producto
	inner join detalle_producto on producto.pro_id=detalle_producto.pro_id
	inner join serie on detalle_producto.det_pro_id=serie.det_pro_id
	where serie.det_pro_id=".$det_pro_id;
	$RS_suc = mysql_query($query_hay_suc, $conexion_mysql) or die(mysql_error());
	$row_RS_cta_pend = mysql_fetch_assoc($RS_suc);
	$totalRows_RS_suc = mysql_num_rows($RS_suc);
	
?>

<style type="text/css">
.normal {
  width: 250px;
  border: 1px solid #000;
}
.normal th, .normal td {
  border: 1px solid #000;
}
</style>

<table id="normal" class="normal">
<thead>
	<tr>
    	<th></th>    	
        <th>id</th>
        <th>Producto</th>
		<th>Serie</th>
	</tr>
</thead>
<tbody> 
	<?php do { ?>
    <tr>
    	<td align="center">
        
        </td>		
        <td><?php echo $row_RS_cta_pend['ser_id']; ?></td>
		<td><?php echo $row_RS_cta_pend['pro_nombre']; ?></td>
		<td><?php echo $row_RS_cta_pend['ser_codigo']; ?></td>
    </tr>
    <?php } while ($row_RS_cta_pend = mysql_fetch_assoc($RS_suc)); ?>   
</tbody>
</table>
<?php mysql_free_result($RS_suc);?>

	