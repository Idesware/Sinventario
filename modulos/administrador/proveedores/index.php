<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();	
	$id_emp = $_SESSION['id_empleado'];
	
	$id_user = $_SESSION['id_usuario'];
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Gestión de Proveedores</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); 
	 
	
	fnc_msgGritter();?>
    
	<div class="container">
		<div class="page-header"><h3>GESTOR DE PROVEEDORES</h3></div>
        <div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon-home"></i> Gestor de Proveedores</li>
                </ul>
			</div>
            <div class="span4">
            	<a href="<?php echo $RUTAm; ?>administrador/proveedores/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVO PROVEEDOR</strong></a>
            </div>
		</div>
		<div class="portlet-body">
			<div class="row-fluid">
				<div class="alert alert-info">
					<h4><i class="icon-list"></i> Lista de Proveedores Calificados<span class="badge"><?php echo $tot_rows; ?></span></h4>
				</div>
			</div>
            <?php 
			$sql = sprintf("SELECT * FROM persona INNER JOIN proveedor on persona.per_id = proveedor.per_id  where proveedor.prov_eliminado='N' ORDER BY persona.per_id DESC");
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
						
						<th>Ruc</th>
						<th>Razón Social</th>
                        <th>Nombre Comercial</th>
                        <th>Email Proveedor</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                     </tr>
				</thead>
				<tbody>
					<?php do {
					$persona = fnc_datPer($row['per_id']);					
					$edad = fnc_calEdad($persona['per_fec_nac']);
					$sucursal = fnc_datSuc($row['suc_id']);  ?>
					<tr>
						<td>
                            <div class="btn-group">
                        			
								<a href="<?php echo $RUTAm; ?>administrador/proveedores/form.php?idPer=<?php echo $row['per_id']; ?>&accion=Actualizar" class="btn btn-primary btn-mini"><i class="icon-edit"></i> Editar</a>


                        		<?php if($row['per_id']){ //Un empleado logeado no se puede eliminar ?>
								<a href="<?php echo $RUTAm; ?>administrador/proveedores/funciones/funciones.php?prov_id=<?php echo $row['prov_id']; ?>&accion=Eliminar" class="btn btn-danger btn-mini" onClick="javascript:return confirm('¿Esta seguro que desea eliminar a <?php echo $persona['per_nombre']; ?>?');"><i class="icon-trash"></i> Eliminar</a>
                                <?php } ?>
                    		</div>
						</td>
						
						<td><?php echo $row['per_documento']; ?></td>
						<td><?php echo $row['per_nombre']; ?></td>
                        <td><?php echo $row['prov_nom_com']; ?></td>
                        <td><?php echo $row['per_mail']; ?></td>
                        <td><?php echo $row['prov_nom_cont']; ?></td>
                        <td><?php echo $row['per_direccion1']; ?></td>
                        <td><?php echo $row['per_telefono']; ?></td>
                        <td><strong><?php echo $row['prov_estado']; ?></strong></td>
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
