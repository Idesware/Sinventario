<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();	
	$id_emp = $_SESSION['id_empleado'];
	$id_user = $_SESSION['id_usuario'];
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Gestión de Usuarios</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<div class="container">
		<?php include(RUTAcom.'menu-principal.php');?>
	    
		<div class="container">
			<div class="page-header"><h3>SUBIR ARCHIVO</h3></div>
            <form class="form-horizontal" method="post" action="proveedor.php" enctype="multipart/form-data" id="subirArchivo">
            <div class="row">
            	<select name="mi_combobox"> 
				<option value="proveedor.php">Cliente</option> 
				<option value="proveedor.php">Proveedor</option> 
                <option value="producto.php">Producto</option>
				</select> 
            </div>
            <div class="row">
                 Archivo
                 <input name="archivo" type="file" id="archivo">
            </div>
            <div class="row">
                <button type="submit" class="btn btn-sm btn-success">Subir</button>
            </div>
        	</form>
		</div>
	</div>
</body>
</html>