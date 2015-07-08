    <?php 
	include('../../../connections-db/conexion-mysql.php');
	$sqltabp = sprintf("SELECT * 
FROM detalle_producto 
inner join stock on stock.det_pro_id = detalle_producto.det_pro_id 
inner join producto on detalle_producto.pro_id = producto.pro_id
inner join unidad_producto on producto.unidad_id=unidad_producto.unidad_id
WHERE det_pro_eliminado ='N' AND pro_eliminado ='N' ORDER BY pro_nombre ASC");
	
	$query = mysql_query($sqltabp, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	?>
    <div align="center">
  <strong style="text-align:center; font-size:24px;color:#006">Stock - Registrado</strong>
  <hr>
<table  border="0"cellspacing="2" width="500px" class="table table-info table-striped">
    <?php if($tot_rows > 0)	{ ?>
				<thead>
					<tr>
						<th></th>
						<th>ID</th>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Stock</th>
                        <th>Costo Compra</th>    
                        <th>Tiene IVA</th>
                        <th>PVP</th>
                  </tr>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td>
							<div class="btn-group list">
                            
								<a href="<?php echo $RUTAm; ?>actualizarstock.php?pro_id=<?php echo $row['pro_id']; ?>&accion=Actualizar" class="btn btn-primary btn-mini"><i class="icon-edit"></i> Editar</a>

							</div>
						</td>
						<td><?php echo $row['pro_codigo']; ?></td>
                        <td><?php echo $row['pro_nombre']; ?></td>
                        <td><?php echo $row['unidad_nom']; ?></td>
                        <td><?php echo $row['stk_cantidad']; ?></td>
                        <td><?php echo '$ '.$row['det_pro_costo']; ?></td>
                        <td><?php echo $row['est_iva']; ?></td>
                        <td><?php echo '$ '.$row['pvp']; ?></td>

                       	</tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
            </div>        			
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>NO TIENE STOCK REGISTRADO.</h4></div>'; } ?>
		</div>     	     