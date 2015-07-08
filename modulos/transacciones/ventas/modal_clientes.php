
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
    
</head>
<body>
<form class="form-horizontal">
                	<div class="row-fluid">
                    	<div class="span12 well well-small">
                        	<div class="row-fluid">
                            	<div class="span12">
                                <br>
                                    <div class="control-group">
                                        <label class="control-label">Documento</label>
                                        <div >
                                            <input type="text" style="width:100px" id="ced" name="doc" maxlength="13" value="<?php echo $datPersona['per_documento']; ?>" placeholder="Cédula/Ruc" required onkeypress="return solonumeros(event);">         
                                            <a class="btn btn-primary" onClick="Validar()"><i class="icon-eye-open"></i></a>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                        <label class="control-label">Nombres</label>
                                        <div >
                                            <input type="text" style="width:150px" id="nombres" name="nom" value="<?php echo $datPersona['per_nombre']; ?>" placeholder="Nombres" required  onBlur="Validar()">
                                        </div>
                                    </div>
                               </div>               
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12 well well-small">
                        	<div class="row-fluid">
                            	<div >
                                    <div class="control-group">
                                        <label class="control-label">Teléfono</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level" id="tele" name="tel"  placeholder="Teléfono" required onkeypress="return solonumeros(event);" maxlength="13">
                                        </div>
                                    </div>
								</div>
                                <div >
                                    <div class="control-group">
                                        <label class="control-label">E-mail</label>
                                        <div class="controls">
                                            <input type="email" class="input-block-level" id="email" name="mail" value="<?php echo $datPersona['per_mail']; ?>" placeholder="E-mail">
                                        </div>
                                    </div>
								</div>
							</div>
                        	
                            <div class="control-group">
                                <label class="control-label">Dirección</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="dir" name="dir" value="<?php echo $datPersona['per_direccion']; ?>" placeholder="Dirección" required>
                                </div>
                            </div>
                               </form>
                               <br>
                            <a id="btn_guardar" class="btn btn-success" onClick="guardarCliente()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px">Guardar</a>
							<a id="btn_cargar" class="btn btn-success" onClick="cargarCliente()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px">Cargar</a>
                        </div>
                        </div>
                        </div>
					</div>
					<div align="center">
                        <input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">
                        <input type="hidden" name="idEmp" id="idEmp" value="<?php echo $idEmp; ?>">
                        <input type="hidden" name="existPer" id="existPer" value="0">
                        <input type="hidden" name="perId" id="perId">

<!-- hidden llamar archivo funcionPer.php -->
                        <input type="hidden" id="Url3" value="<?php echo $RUTAm."transacciones/ventas/funciones/funcionesPer.php"; ?>">
					
                    </div>
</body>
</html>

<script type="text/javascript">
    
	$("#btn_cargar").hide();
	$("#btn_guardar").hide();
	
    function solonumeros(e)
    {
    var keynum = window.event ? window.event.keyCode : e.which;
    if ((keynum == 8) || (keynum == 46))
    return true;
     
    return /\d/.test(String.fromCharCode(keynum));
    }
    
    function Validar()
    {
    ced=$("#ced").val();    
    parametros="per_documento="+ced;
            $.ajax({
            type: "POST",
            url: "funciones/bus-persona.php",
            data: parametros,
            success : function (resultado){     
                if(resultado){
                //Convertir el documento JSON a Objeto Javascript
                var objCadena = eval('(' + resultado + ')');
                var per_nom = objCadena.per_nombre; 
                    $("#nombres").val(objCadena.per_nombre);
                    $("#email").val(objCadena.per_mail);
                    $("#dir").val(objCadena.per_direccion1);
                    $("#tele").val(objCadena.per_telefono);
					$("#btn_cargar").show();
					$("#btn_guardar").hide();
                }
				else
				{
					$("#btn_cargar").hide();
					$("#btn_guardar").show();
				}
            }
            }); 
    }

function guardarCliente() {
    
        var url = $("#Url3").val();
        var accion = 'InsertarCliente';
    
        $.ajax({
        url: url,
        type: "POST",
        async: false,
        data: {
            accion: accion,
            nom: $("#nombres").val(),
            doc: $("#ced").val(),
            dir: $("#dir").val(),
            mail: $("#email").val(),
            tel: $("#tele").val() 
        },
        success:  function(resultado) 
            {
                
			}
    });
    
}

function cargarCliente() {
            $("#inputnombre").val($("#nombres").val());
            $("#inputcedula").val($("#ced").val());
            $("#inputdireccion").val($("#dir").val());
            $("#inputtelefono").val($("#tele").val());
}

</script>