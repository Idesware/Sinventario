<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	

	$pro_codigo = fnc_varGetPost('inputCod');
	$datPro = fnc_datPro($pro_id);
	

	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_usuario($id_user);
	$nombre_usuario = $persona['per_nombre'];
	$idsucursal = fnc_datSuc($empleado['suc_id']);

	if($datPro){
		$accion = 'Actualizar';
		$button = '<input type="submit" class="btn btn-primary" name="btnGuardar" id="btnGuardar" value="ACTUALIZAR">';
	}else{
		$accion = 'Insertar';
		$button='<input type="submit" class="btn btn-primary" name="btnGuardar" id="btnGuardar" value="INSERTAR">';
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title><?php echo $accion; ?> Crear Producto</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <div class="container">
		<div class="page-header"><h3><?php echo strtoupper($accion); ?> NUEVO PRODUCTO</h3></div>
		<div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="<?php echo $RUTAm; ?>administrador/productos/index.php"> Administración de Productos</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $accion; ?> Producto</li>
                </ul>
			</div>
            <div class="span4">
            	<?php if($datPro){ ?>
            	<a href="<?php echo $RUTAm; ?>administrador/productos/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVO PRODUCTO</strong></a>
                <?php } ?>
            </div> 	
		</div>
		<div class="row-fluid">
			<div class="tabbable">
            	<ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Datos</a></li>
                </ul>

					<!-- Modal Categoria Productos -->
					<div align="center" id="modalcategoria"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalcategoria" aria-hidden="true">
          				<div class="modal-header">
                			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                			<h4 id="modalcategoria"><strong>Crear Categoria</strong></h4>
                			<div align="center">
            				</div>
          				</div>
          		
          				<div class="modal-body">
            				<?php include("modal_categoria.php"); ?>
          				</div>
        			</div>
				<!-- Modal Categoria Productos fin -->

				<!-- Modal Unidad Productos -->
					<div align="center" id="modalunidad"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalunidad" aria-hidden="true">
          				<div class="modal-header">
                			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                			<h4 id="modalunidad"><strong>Crear Unidad</strong></h4>
                			<div align="center">
            				</div>
          				</div>
          		
          				<div class="modal-body">
            				<?php include("modal_unidad.php"); ?>
          				</div>
        			</div>
            <div>
				<!-- Modal Categoria Productos fin -->

					<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>administrador/productos/funciones/productos-fncs.php" enctype="multipart/form-data" onSubmit="return verificarSuc()">
			<div>
            <div>
			<table cellspacing="2" align="center">
            <div>
                          <tr >
                <td style="padding-left:10px">
                	<label class="control-label">Código</label>
                </td>
                <td style="padding-left:10px">
					<input type="text" id="inputCod" name="inputCod" placeholder="Codigo" value="<?php echo $datPro['pro_codigo']; ?>" required>

                </td>
                <td style="padding-left:10px">
                </td>
              </tr>
              <tr >
                <td style="padding-left:10px">
                	<label class="control-label">Nombre</label>
                </td>
                <td style="padding-left:10px">
					<input type="text" id="inputNom" name="inputNom" placeholder="Nombre" value="<?php echo $datPro['pro_nombre']; ?>" required>

                </td>
                <td style="padding-left:10px">
                </td>
              </tr>
              </div>
              <div>
              <tr>
              
              <tr >
                <td style="padding-left:10px">
                	<label class="control-label">Unidad</label>
                </td>
                <td style="padding-left:10px">
					<?php $idSel= $datPro['unidad_id'];
                    fnc_listUnidades($idSel,'unidad_id','unidad_nom', 'unidad_producto', 'input-block-level', 'inputUnidad', 'autofocus required');?>
                </td>
                <td style="padding-left:10px">
                	<input type="button" class="btn btn-success" name="btn_Unidad_Pro" id="btn_Unidad_Pro" value="Crear Unidad" onclick = "$('#modalunidad').modal()";> </td>
              </tr>
              </div>
              <div>
              <tr>
                <td style="padding-left:10px">
                	<label class="control-label">Categoría</label>
                </td>
                <td style="padding-left:10px">
					<?php $idSel= $datPro['cat_id']; 
                    fnc_listCategoria($idSel,'cat_id','cat_nom', 'categoria_producto', 'input-block-level', 'input_cat_pro', 'autofocus required');?>
                </td>
                <td style="padding-left:10px">
                	<input type="button" class="btn btn-success" name="btn_Cat_Pro" id="btn_Cant_Pro" value="Crear Categoría" onclick = "$('#modalcategoria').modal()";> </td>
              </tr>
              </div>
            </table>
					<div class="form-actions" align="center">
						<input style="padding:20px" type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">
						<?php echo $button; ?>
						<a href="<?php echo $RUTAm; ?>administrador/productos/index.php" type="button" class="btn">CANCELAR</a>
						<input type="hidden" id="url_autocomplete" value="<?php echo $RUTAm."administrador/productos/funciones/autocomplete_productos.php"; ?>">
						<input type="hidden" id="url_autocomplete2" value="<?php echo $RUTAm."administrador/productos/funciones/autocomplete_unidades.php"; ?>">
                        
                        <input type="hidden" id="input_unidad_codigo" name="input_unidad_codigo">
                        <input type="hidden" id="input_sucursal" name="input_sucursal" value="<?php echo $idsucursal['suc_id']; ?>">
                        <input type="hidden" id="empleado" name="empleado" value="<?php echo $empleado; ?>">
                        <input type="hidden" id="input_categoria_codigo" name="input_categoria_codigo">
                        <input type="hidden" id="nombre_usuario" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">

                      

                        <input type="hidden" name="pro_id" id="pro_id" value="<?php echo $pro_id; ?>">
					</div>
				</form>
			</div>
		</div>
    </div>
</body>
<footer>
</footer>
</html>

<script type="text/javascript">

$('#input_cat_pro').chosen({
			autoFocus: true
		});
$('#inputUnidad').chosen({
			autoFocus: true
		});		
$(document).on('ready', function(){
			$("#input_cat_pro").autocomplete({
				source: 'funciones/autocomplete_categoria.php',
				minLength: 1,
				autoFocus: true
			});
			$("#inputUnidad").autocomplete({
				source: 'funciones/autocomplete_unidades.php',
				minLength: 1,
				autoFocus: true
			});
		});

</script>