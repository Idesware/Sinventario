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
	<title>Gestión de Sucursales</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); 
	$sql = sprintf("SELECT * FROM sucursal WHERE suc_eliminado = 'N' ORDER BY suc_id DESC");
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query);

	$empleado = fnc_datEmp($id_emp);
	
	fnc_msgGritter();?>
    
	<div class="container">
		<div class="page-header"><h3>ADMINISTRADOR DE SUCURSALES</h3></div>
        <div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon-home"></i> Administración de Sucursales</li>
                </ul>
			</div>
            <div class="span4">
            	<a href="<?php echo $RUTAm; ?>administrador/sucursales/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVA SUCURSAL</strong></a>
            </div>
		</div>
		<div class="portlet-body">
			<div class="row-fluid">
				<div class="alert alert-info">
					<h4><i class="icon-list"></i> Lista de sucursales registrados <span class="badge"><?php echo $tot_rows; ?></span></h4>
				</div>
			</div>
            <?php if($tot_rows > 0)	{ ?>
            <div class="row-fluid">
			<table class="table table-bordered table-condensed table-striped" id="tab_usr">
				<thead>
					<tr>
						<th width="125px"></th>
						<th>Id</th>
						<th>Sucursal</th>
                        <th>Dirección</th>
						<th>Teléfono</th>
						
					</tr>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td>
                            <div class="btn-group">
                        		<a href="<?php echo $RUTAm; ?>administrador/sucursales/form.php?suc_id=<?php echo $row['suc_id']; ?>" class="btn btn-primary btn-mini"><i class="icon-edit"></i> Editar</a>
                        		






                        		<?php if($row['suc_id']!=$empleado['suc_id']){ //Un sucursal logeado no se puede eliminar ?>
								<a href="<?php echo $RUTAm; ?>administrador/funciones/sucursales-fncs.php?suc_id=<?php echo $row['suc_id']; ?>&accion=Eliminar" class="btn btn-danger btn-mini" onClick="javascript:return confirm('¿Esta seguro que desea eliminar a <?php echo $persona['suc_nombre']; ?>?');"><i class="icon-trash"></i> Eliminar</a>
                                <?php } ?>
                    		</div>
						</td>
						<td><?php echo $row['suc_id']; ?></td>
						<td><?php echo $row['suc_nombre']; ?></td>
                        <td><?php echo $row['suc_direccion']; ?></td>
						<td><?php echo $row['suc_telefono']; ?></td> 						                           
					</tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
            </div>        			
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>No existen sucursales registradas.</h4></div>'; } ?>
		</div>     	     
	</div>
</body>
<footer>	
</footer>