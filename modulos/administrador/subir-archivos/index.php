<?php
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();	
	$id_emp = $_SESSION['id_empleado'];
	$id_user = $_SESSION['id_usuario'];
	$cb_accion= $_POST["accion"];
	$persona = fnc_usuario($id_user);
	$nombre_usuario = $persona['per_nombre'];
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
<?php include(RUTAcom.'menu-principal.php');?>
	<div class="container">
		
<form class="form-horizontal" method="post"  enctype="multipart/form-data" 
id="subirArchivo">	    
		<div class="container">
			<div class="page-header"><h3>SUBIR ARCHIVO</h3></div>           
            <div class="row">
            	<select id="cb_accion" name="cb_accion" onchange="cargar_accion()";> 
				<option value="0">Seleccione una opción</option>
                <option value="1">Cliente</option> 
				<option value="2">Proveedor</option> 
                <option value="3">Producto</option>
				</select> 
            </div>
            <div class="row">
                 Archivo
                 <input name="archivo" type="file" id="archivo">
            </div>
            <div class="row">
                <button type="submit" class="btn btn-sm btn-success">Subir</button>
            </div>
            <input type="hidden" id="nombre_usuario" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
        	</form>
		</div>
	</div>
</body>
</html>
<script>
function cargar_accion()
{	
	 var accion= $("#cb_accion").val();
	 var elemento = document.querySelector('#subirArchivo');
	 //alert(accion);
	 if(accion=="0")
	 {
		 alert("Tiene que escoger una Opción");
	 }

	 if(accion=="1")
	 {
		 elemento.setAttribute("action","cliente.php");
	 }
	 if(accion=="2")
	 {
		 elemento.setAttribute("action","proveedor.php");
	 }
	 if(accion=="3")
	 {
		 elemento.setAttribute("action","producto.php");
	 }
}

</script>