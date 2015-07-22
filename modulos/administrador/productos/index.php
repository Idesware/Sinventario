<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$id_user = $_SESSION['id_usuario'];
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
	
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Gestión Productos</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>    
    
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php');
	$sql = sprintf("SELECT pro_id ,pro_codigo,pro_serie,pro_nombre,unidad_nom,cat_nom,pro_usuario_crea FROM producto  INNER JOIN unidad_producto ON producto.unidad_id=unidad_producto.unidad_id
		INNER JOIN categoria_producto ON producto.cat_id=categoria_producto.cat_id
        WHERE pro_eliminado = 'N' ORDER BY pro_nombre ASC");

	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	fnc_msgGritter();?>
    
	<div class="container">
		<div class="page-header"><h3>ADMINISTRACIÓN DE PRODUCTOS</h3></div>
        <div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon-home"></i> Administración de Productos</li>
                </ul>
			</div>
            <div class="span4">
            	<a href="<?php echo $RUTAm; ?>administrador/productos/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVO PRODUCTO</strong></a>	
            </div>
        </div>
		<div class="portlet-body">			
			<div class="row-fluid">
            	<div class="span5">
                	<div class="alert alert-info">
                        <h4><i class="icon-list"></i> Lista de productos creados <span class="badge"><?php echo $tot_rows; ?></span></h4>
					</div>
				</div>			
			</div>
            <?php if($tot_rows > 0)	{ ?> 
            <div class="row-fluid">           	          		
			<table class="table table-bordered table-condensed table-striped" id="tab_usr">
				<thead>
					<tr>
						<th></th>
						<th>Codigo</th>
                        <th>Serie</th>
						<th>Descripcion</th>
						<th>Unidad</th>
						<th>Categoria</th>
						<th>Usuario</th>
					</tr>
				</thead>
				<tbody>
					   <?php do { 
					   ?>                             
					<tr>
						<td>
							<div class="btn-group list">
                            
								<a href="<?php echo $RUTAm; ?>administrador/productos/form.php?pro_id=<?php echo $row['pro_id']; ?>&accion=Actualizar" class="btn btn-primary btn-mini"><i class="icon-edit"></i> Editar</a>

                                
								<a href="<?php echo $RUTAm; ?>administrador/productos/funciones/productos-fncs.php?pro_id=<?php echo $row['pro_id']; ?>&accion=Eliminar" class="btn btn-danger btn-mini" onClick="javascript:return confirm('¿Esta seguro que desea eliminar a <?php echo $row['pro_nombre']; ?>?');"><i class="icon-trash"></i> 
								Eliminar</a>
							

							</div>
						</td>
						<td><?php echo $row['pro_codigo']; ?></td>
                        <td><?php echo $row['pro_serie']; ?></td>
						<td><?php echo $row['pro_nombre']; ?></td>
						<td><?php echo $row['unidad_nom']; ?></td>
						<td><?php echo $row['cat_nom']; ?></td>
						<td><?php echo $row['pro_usuario_crea']; ?></td>
						</tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
            </div>
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>No existen productos registrados.</h4></div>'; } ?>
		</div>     	     
	</div>
</body>
<footer>	
</footer>
</html>