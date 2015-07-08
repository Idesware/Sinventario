<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$idEmp = fnc_varGetPost('idEmp');
	$datEmpleado = fnc_datEmp($idEmp);
	$datPersona = fnc_datPer($datEmpleado['per_id']);
	$suc_id=$datEmpleado['suc_id'];
	
	if($datEmpleado){
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
	<title><?php echo $accion; ?> talento humano</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
    <script type="text/javascript">
	function solonumeros(e)
	{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	 
	return /\d/.test(String.fromCharCode(keynum));
	}
	function Validar()
	{
	doc=$("#doc").val();	
	per_id="";
	parametros="per_id="+per_id+"&per_documento="+doc;
			$.ajax({
			type: "POST",
			url: "funciones/bus-persona.php",
			data: parametros,
			success : function (resultado){ 	
			
			if(resultado){
			
			//Convertir el documento JSON a Objeto Javascript
			var objCadena = eval('(' + resultado + ')');
			var per_id = objCadena.per_id;
			var per_nom = objCadena.per_nombre;	
			
			
			$("#nom").val(objCadena.per_nombre);
			$("#perId").val(objCadena.per_id);
			$("#mail").val(objCadena.per_mail);
			$("#dir1").val(objCadena.per_direccion1);
			$("#tel").val(objCadena.per_telefono);
			$("#existPer").val(1);
			}
			}
			});	
	}
</script>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <div class="container">
		<div class="page-header"><h3><?php echo strtoupper($accion); ?> TALENTO HUMANO</h3></div>
		<div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="<?php echo $RUTAm; ?>administrador/empleados/index.php"> Administración de talento humano</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $accion; ?> talento humano</li>
                </ul>
			</div>
            <div class="span4">
            	<?php if($datEmpleado){ ?>
            	<a href="<?php echo $RUTAm; ?>administrador/empleados/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVO EMPLEADO</strong></a>
                <?php } ?>
            </div>
		</div>
		<div class="row-fluid">
			<div class="control-group well">
            	<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>administrador/empleados/funciones/funciones.php" onSubmit="return verificarEmp()">
                	<div class="row-fluid">
                    	<div class="span12 well well-small">
                        	<div class="row-fluid">
                            	<div class="span8">
                                <br>
                                    <div class="control-group">
                                        <label class="control-label">Documento</label>
                                        <div class="controls">
                                            <input type="text" class="span8" id="doc" name="doc" maxlength="13" value="<?php echo $datPersona['per_documento']; ?>" placeholder="Cédula de Identidad" required onkeypress="return solonumeros(event);">
                                            <span class="add-on" id="resultEmp"></span>
                                            <a class="btn btn-primary" onClick="Validar()"><i class="icon-eye-open"></i> Validar</a>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Nombres</label>
                                        <div class="controls">
                                            <input type="text" class="span8" id="nom" name="nom" value="<?php echo $datPersona['per_nombre']; ?>" placeholder="Nombres" required  onBlur="Validar()">
                                        </div>
                                    </div>
                               </div>
                               <div class="span2" align="center">
                               <img style="alignment-adjust:central" width="170px" height="170px" src="<?php echo $RUTAi.'sistema/personal.png'; ?>">
                               </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span8 well well-small">
                        	<div class="row-fluid">
                            	<div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Teléfono</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level" id="tel" name="tel" value="<?php echo $datPersona['per_telefono']; ?>" placeholder="Teléfono" required onkeypress="return solonumeros(event);" maxlength="13">
                                        </div>
                                    </div>
								</div>
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">E-mail</label>
                                        <div class="controls">
                                            <input type="email" class="input-block-level" id="mail" name="mail" value="<?php echo $datPersona['per_mail']; ?>" placeholder="E-mail">
                                        </div>
                                    </div>
								</div>
							</div>
                        	
                            <div class="control-group">
                                <label class="control-label">Dirección 1</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="dir1" name="dir1" value="<?php echo $datPersona['per_direccion1']; ?>" placeholder="Dirección 1" required>
                                </div>
                            </div>

                        </div>
                        <div class="span4 well well-small">
                            <div class="control-group">
                                <label class="control-label">Sucursal</label>
                                <div class="controls">
                                    <?php 
									
									fnc_listSuc($suc_id,'input-block-level','suc','required'); ?>
                                </div>
                          </div>
                            <div class="control-group">
                                <label class="control-label">Fecha de ingreso</label>
                                <div class="controls">
                                    <input type="date" class="input-block-level" id="fec_ing" name="fec_ing" value="<?php echo $datEmpleado['emp_fecha_ingreso']; ?>" placeholder="1999/01/25" required>
                                </div>
                            </div>
                        </div>
					</div>
					<div align="center">
                        <input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">
                        <input type="hidden" name="idEmp" id="idEmp" value="<?php echo $idEmp; ?>">
                        <input type="hidden" name="existPer" id="existPer">
                        <input type="hidden" name="perId" id="perId">
                        <?php echo $button; ?>
                        <a href="<?php echo $RUTAm; ?>administrador/empleados/index.php" type="button" class="btn">CANCELAR</a>
					</div>
				</form>
            </div>
		</div>
    </div>
</body>
<footer>
</footer>
</html>