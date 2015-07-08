
<!doctype html>
<html>
<head>

    <script type="text/javascript">
	function solonumeros(e)
	{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	 
	return /\d/.test(String.fromCharCode(keynum));
	}
	function guardarAbonoCredito()
	{
		
		var accion = 'GUARDAR';
		var pag_id = $("#numpag_id").val();
		var abono = $("#abono").val();
		var saldo = $("#saldo").val();
		var cab_ven_id = $("#cab_ven_id").val();
		var url = $("#url").val();
		if(abono <= saldo){
		$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				accion: accion,
				pag_id: pag_id,
				abono: abono,
				cab_ven_id: cab_ven_id
			},
			success:  function(resultado) {
				
					vex.dialog.alert (resultado);
				}
		});
				
		}else{
			vex.dialog.alert ("El abono no debe superar a : $ "+saldo);
			}
	}
</script>
</head>
<body>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>transacciones/ventas/funciones/funciones.php">
                	<div class="row-fluid">
                    	<div class="span12 well well-small">
                        	<div class="row-fluid">
                           
                            <div class="span4"><strong>Referencia:
                            <label id="refcuenxpag" ></label></strong>
                            </div>
                            <div class="span4"><strong>Total:
                            $ <label id="totcuenxpag" ></label></strong>
                            </div>
                            <input type="hidden" id="cab_ven_id" name="cab_ven_id" >
                            <input type="hidden" id="saldo" name="saldo" >
                            <input type="hidden" id="numpag_id" name="numpag_id" >
                             <input type="hidden"  id="url" name="url" value="<?php echo $RUTAm.'transacciones/cuentasxcobrar/funciones/funciones.php';?>">

                            <div class="span4"><strong style="color:#900">SALDO</strong><div>
                            <span class="badge badge-important"><label id="saldocuenxpag" ></label></span></div>
                            </div>
                      		</div>
                            <div class="row-fluid">
                            	<div class="span12">
                                <br>
                       			<div class="control-group">
                                <label class="control-label">Abono</label>
                                <div class="controls">
                                    <input type="number" class="input-block-level" id="abono" name="abono" placeholder="0.00" required onKeyPress="return solonumeros()">
                                </div>
                                <br>
                               </form>
                            <a class="btn btn-success" onClick="guardarAbonoCredito()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px">ABONAR</a>

                        </div>
                        </div>
                        </div>
					</div>
					</div>
				

</body>

</html>