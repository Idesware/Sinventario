<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$idPer= $_GET['idPer'];
	$datPersona = fnc_Perprov($idPer);
	

	if($datPersona){
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
	<title>Proveedores</title>
       
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
		<div class="page-header"><h3><?php echo strtoupper($accion);?> PROVEEDOR</h3></div>
		<div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="<?php echo $RUTAm; ?>administrador/proveedores/index.php"> Administración de Proveedores</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $accion; ?> Proveedores</li>
                </ul>
			</div>
            <div class="span4">
            	<?php if($datPersona){ ?>
            	<a href="<?php echo $RUTAm; ?>administrador/proveedores/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVO EMPLEADO</strong></a>
                <?php } ?>
            </div>
		</div>
		<div class="row-fluid">
			<div class="control-group well">
            	<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>administrador/proveedores/funciones/funciones.php" onSubmit="return verificarEmp()">
                	<div class="row-fluid">
                        <div class="span12 well well-small" align="center">
                        <strong style="font-size:16px; color:#009">Datos de la Empresa</strong>
                        </div>
                    <div class="row-fluid">
                        <div class="span6 well well-small">
                            <br>
                          <div class="control-group">
                                <label class="control-label">Razón Social</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="per_nombre" name="per_nombre" value="<?php echo $datPersona['per_nombre']; ?>" placeholder="Razón Social" required>
                                </div>
                            </div>
                            <div class="control-group">
                              <label class="control-label">RUC</label>
                              <div class="controls">
                                  <input type="text" class="input-block-level" maxlength="13" id="per_documento" name="per_documento" value="<?php echo $datPersona['per_documento']; ?>" placeholder="RUC" required onkeypress="return solonumeros(event);">
                               </div>
                           </div> 

                            <div class="control-group">
                                <label class="control-label">Telefono</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="per_telefono" name="per_telefono" maxlength="13" value="<?php echo $datPersona['per_telefono']; ?>" placeholder="Telefono" required onkeypress="return solonumeros(event);">
                                </div>
                            </div>
            
                           <div class="control-group">
                                <label class="control-label">Dirección</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="per_direccion1" name="per_direccion1" value="<?php echo $datPersona['per_direccion1']; ?>" placeholder="Dirección" required>
                                </div>
                            </div>  
                        </div>
                        <div class="span6 well well-small">
                            <div class="control-group">
                              <label class="control-label">Nombre Comercial</label>
                              <div class="controls">
                              <input type="text" class="input-block-level" maxlength="25" id="prov_nom_com" name="prov_nom_com" value="<?php echo $datPersona['prov_nom_com']; ?>" placeholder="Nombre Comercial" required>
                              </div>
                           </div>
                            <div class="control-group">
                                <label class="control-label">E-mail</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="per_mail" name="per_mail" value="<?php echo $datPersona['per_mail']; ?>" placeholder="email@proveedor.com">
                                </div>
                            </div>
                        <div class="control-group">
                              <label class="control-label">Estado de Proveedor</label>
                              <div class="controls">
                                   <p required >
                                      <label>
                                        <input type="radio" name="prov_estado" value="Activo" <?php echo ($datPersona['prov_estado']=='Activo')?'checked':'' ?> id="prov_estado">Activo</label>
                                        <label>
                                        <input type="radio" name="prov_estado" value="Inactivo" <?php echo ($datPersona['prov_estado']=='Inactivo')?'checked':'' ?> id="prov_estado">Inactivo </label>  
                                  </p>
                              </div>
                           </div>
                       </div>
                    </div>

                    <div class="row-fluid">
                    	<div class="span12 well well-small">
                        	<div class="row-fluid">
                            	<div class="span8">
                                <div align="center"><strong style="font-size:16px; color:#009">Contacto Directo</strong></div>
                                <br> 
                                    <div class="control-group">
                                        <label class="control-label">Nombres</label>
                                        <div class="controls">
                                            <input type="text" class="span8" id="prov_nom_cont" name="prov_nom_cont" value="<?php echo $datPersona['prov_nom_cont']; ?>" placeholder="Nombres del Contacto" required>
                                        </div>
                                    </div>
                                    <div class="row-fluid">
                                <div class="span6">
                                    <div class="control-group">
                                        <label class="control-label">Celular</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level" id="prov_cel_cont" name="prov_cel_cont" maxlength="13" value="<?php echo $datPersona['prov_cel_cont']; ?>" placeholder="Celular del Contacto" required onkeypress="return solonumeros(event);">
                                        </div>
                                    </div>
                                </div>
                            </div>
                               </div>
                               <div class="span2" align="center">
                               <img style="alignment-adjust:central" width="130px" height="130px" src="<?php echo $RUTAi.'sistema/proveedor.png'; ?>">
                               </div>
                        </div>
                    </div>
					<div align="center">
                        

                        <input type="hidden" class="input-block-level" name="accion" id="accion" value="<?php echo $accion; ?>">					 
                        

                        <input type="hidden" name="existPer" id="existPer">
                        

                        <input type="hidden" name="perId" id="perId" value="<?php echo $datPersona['per_id']; ?>" >
                       

                        <?php echo $button; ?>
                        <a href="<?php echo $RUTAm; ?>administrador/proveedores/index.php" type="button" class="btn">CANCELAR</a>
					</div>
				</form>
            </div>
		</div>
        </div>
    </div>
</body>
<footer>
</footer>
</html>