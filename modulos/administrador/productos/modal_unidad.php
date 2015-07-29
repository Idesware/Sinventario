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
                            	<div >
                                    <div class="control-group">
                                        <label class="control-label">Nombre Unidad</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level" id="input_unidad" name="input_unidad"  placeholder="Nombre Unidad" required maxlength="15">
                                        </div>
                                    </div>
								</div>
							</div>
                               </form>
                               <br>
                            <a id="btn_guardar" class="btn btn-success" onClick="guardarUnidad()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px">Guardar</a>
                        </div>
                        </div>
                        </div>
					</div>
					<div align="center">
                        <input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">

<!-- hidden llamar archivo funcionPer.php -->
                    <input type="hidden" id="Url4" value="<?php echo $RUTAm."administrador/productos/funciones/funciones_productos.php"; ?>">					
                    </div>
</body>
</html>

<script type="text/javascript">
    

function guardarUnidad() {
    
        var url = $("#Url4").val();
        var accion = 'InsertarUnidad';
    
        $.ajax({
        url: url,
        type: "POST",
        async: false,
        data: {
            accion: accion,
            uni: $("#input_unidad").val(), 
        },
        success:  function(resultado) 
            {
                
			}
    });
	
	location.reload(true);  
}


</script>