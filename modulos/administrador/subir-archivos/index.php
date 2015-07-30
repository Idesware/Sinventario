<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();	
	$id_emp = $_SESSION['id_empleado'];
	$id_user = $_SESSION['id_usuario'];
	$cb_accion= $_POST["accion"];
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
<form class="form-horizontal" method="post"  enctype="multipart/form-data" 
id="subirArchivo">	    
		<div class="container">
			<div class="page-header"><h3>SUBIR ARCHIVO</h3></div>           
            <div class="row">
            	<select id="cb_accion" name="cb_accion" onchange="cargar_accion()";> 
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
<script>
function cargar_accion()
{	
	 var accion= $("#cb_accion").val();
	 //alert(accion);
	 
	 var elemento = document.querySelector('#subirArchivo');
	 elemento.setAttribute("action='proveedor.php'");
}

</script>