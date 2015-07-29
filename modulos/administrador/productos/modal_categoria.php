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
                                        <label class="control-label">Nombre Categoria</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level" id="categoria" name="categoria"  placeholder="Nombre Categoria" required maxlength="15">
                                        </div>
                                    </div>
								</div>
							</div>
                               </form>
                               <br>
                            <a id="btn_guardar" class="btn btn-success" onClick="guardarCategoria()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px">Guardar</a>
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
    

function guardarCategoria() {
    
        var url = $("#Url4").val();
        var accion = 'InsertarCategoria';
    
        $.ajax({
        url: url,
        type: "POST",
        async: false,
        data: {
            accion: accion,
            cat: $("#categoria").val(), 
        },
        success:  function(resultado) 
            {
               $("#input_cat_pro").load();
			   
			}
    });
    location.reload(true);  
}
</script>