<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$suc_id = fnc_varGetPost('suc_id');
	$datSuc = fnc_datSuc($suc_id);

	if($datSuc){
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
	<title><?php echo $accion; ?> sucursal</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <div class="container">
		<div class="page-header"><h3><?php echo strtoupper($accion); ?> SUCURSAL</h3></div>
		<div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="<?php echo $RUTAm; ?>administrador/sucursales/index.php"> Administración de sucursales</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $accion; ?> sucursal</li>
                </ul>
			</div>
            <div class="span4">
            	<?php if($datSuc){ ?>
            	<a href="<?php echo $RUTAm; ?>administrador/usuarios/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVA SUCURSAL</strong></a>
                <?php } ?>
            </div> 
			
		</div>
		<div class="row-fluid">
			<div class="control-group well">
				<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>administrador/funciones/sucursales-fncs.php" onSubmit="return verificarSuc()">
					<div class="control-group">
						<label class="control-label">Sucursal</label>
						<div class="controls">
							<div class="input-append span10">
								<input type="text" class="input-block-level" id="suc_nombre" name="suc_nombre" value="<?php echo $datSuc['suc_nombre']; ?>" placeholder="Nombre de la Sucursal" onKeyUp="" required>
								<span class="add-on" id="resultSuc"></span>
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Dirección</label>
						<div class="controls">
							<input type="text" class="input-block-level" id="suc_direccion" name="suc_direccion" value="<?php echo $datSuc['suc_direccion']; ?>" placeholder="Dirección" required>
						</div>
					</div>
                    <div class="control-group">
						<label class="control-label">Teléfono</label>
						<div class="controls">
							<input type="text" class="input-block-level" id="suc_telefono" name="suc_telefono" value="<?php echo $datSuc['suc_telefono']; ?>" placeholder="Número de Teléfono" required>
						</div>
					</div>
					
					<div class="form-actions">
						<input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">
						<input type="hidden" name="suc_id" id="suc_id" value="<?php echo $suc_id; ?>">
						<?php echo $button; ?>
						<a href="<?php echo $RUTAm; ?>administrador/sucursales/index.php" type="button" class="btn">CANCELAR</a>
					</div>
				</form>
			</div>
		</div>
    </div>
</body>
<footer>
</footer>
</html>