    <?php 
		

	include('../../../connections-db/conexion-mysql.php');
	$pro=$_POST['pro'];
	echo $pro;
	$sqltabp = sprintf("SELECT * FROM detalle_producto inner join stock on stock.det_pro_id=detalle_producto.det_pro_id 
inner join producto on detalle_producto.pro_id = producto.pro_id WHERE det_pro_eliminado ='N' and producto.pro_id='".pro."'");
	$query = mysql_query($sqltabp, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	
	
	?>
    <div align="center">
  <strong style="text-align:center; font-size:24px;color:#006">Stock - Registrado </strong>
  <hr>
<table  border="0"cellspacing="2" width="500px" class="table table-info table-striped">
    <?php if($tot_rows > 0)	{ ?>
				<thead>
					<tr>
						<th>ID</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Costo</th>
                        <th>PVP</th>
                        <th>IVA</th>
                        <th>Minimo</th>
                  </tr>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td><?php echo $row['pro_id']; ?></td>
                        <td><?php echo $row['pro_nombre']; ?></td>
                        <td><?php echo $row['stk_cantidad']; ?></td>
                        <td><?php echo '$ '.$row['det_pro_costo']; ?></td>
                        <td><?php echo '$ '.$row['det_pro_pvp']; ?></td>
                        <td><?php echo '$ '.$row['det_pro_iva']; ?></td>
						<td><?php echo $row['stk_minimo']; ?></td>
                       	</tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
            </div>        			
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>NO TIENE STOCK REGISTRADO.</h4></div>'; } ?>
		</div>     	     