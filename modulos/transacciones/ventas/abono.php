
<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

    <script type="text/javascript">
	function solonumeros(e)
	{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	 
	return /\d/.test(String.fromCharCode(keynum));
	}
	
</script>
</head>
<body>
<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>transacciones/ventas/funciones/funciones.php">
                	<div class="row-fluid">
                    	<div class="span12 well well-small">
                        	<div class="row-fluid">
                            <div class="span6">
                            NOMBRE: <strong><label id="nomclielabel" ></strong>
                            </div>
                            <div class="span6">
                            Valor:<strong> <label id="salclielabel" ></strong>
                            </div>
                      		</div>
                            <div class="row-fluid">
                            	<div class="span12">
                                <br>
                       			<div class="control-group">
                                <label class="control-label">Abono</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="abono" name="abono" value="<?php echo $datPersona['per_direccion1']; ?>" placeholder="Abono" required>
                                </div>
                                <br>
                               </form>
                            <a class="btn btn-success" onClick="guardarVentaCredito()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px">Aplicar Cr&eacute;dito</a>

                        </div>
                        </div>
                        </div>
					</div>
					</div>
				

</body>

</html>