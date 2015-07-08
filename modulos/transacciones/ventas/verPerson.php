<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();	
	$id_emp = $_SESSION['id_empleado'];
?>

            <?php 
			$sql = sprintf("SELECT * FROM persona ORDER BY persona.per_nombre DESC");
			$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
			$row = mysql_fetch_assoc($query);
			$tot_rows = mysql_num_rows($query);
			?>
            <div class="row-fluid">
            <?php if($tot_rows > 0)	{ ?>
			<table class="table table-bordered table-condensed table-striped" id="tab_emp">
				<thead>
					<tr>
						<th width="125px"></th>
						<th>Nombres</th>
                        <th>Cédula</th>
                        <th>Dirección de Domicilio</th>
                        <th>Teléfono</th>
                     </tr>
				</thead>
				<tbody>
					<?php do { ?>

					<tr>
						<td>
                            <div class="btn-group">
                        		<a href="<?php echo $RUTAm; ?>administrador/empleados/form.php?idEmp=<?php echo $row['per_id']; ?>" class="btn btn-primary btn-mini"><i class="icon-edit"></i> Seleccionar</a>
                    		</div>
						</td>
						<td><?php echo $row['per_nombre']; ?></td>
                        <td><?php echo $row['per_documento']; ?></td>
                        <td><?php echo $row['per_direccion1']; ?></td>
                        <td><?php echo $row['per_telefono']; ?></td>
					</tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>No existen empleados registrados.</h4></div>'; } ?>
            </div>
		</div>
	</div>
</body>
<footer>	
</footer>
</html>