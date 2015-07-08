

<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
    
</head>
<body>
<form class="form-horizontal">
                	<div class="row-fluid">
                    	
                    <div class="row-fluid">
                        <div class="span12 well well-small">
                        	<div class="row-fluid">
                            	<div >
                                    <div class="control-group">
                                        <label class="control-label">TOTAL</label>
                                        <div class="controls">
                                            <input type="text" class="input-block-level" id="tot_cobrar" name="tot_cobrar" required  maxlength="13" readonly>
                                        </div>
                                    </div>
								</div>
                                <div >
                                    <div class="control-group">
                                        <label class="control-label">INGRESE EL PAGO</label>
                                        <div class="controls">
                                            <input type="email" class="input-block-level" id="pago" name="pago"onkeypress= "CalcularCambio(event)">
                                        </div>
                                    </div>
								</div>
							</div>
                        	
                            <div class="control-group">
                                <label class="control-label">El CAMBIO ES</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" id="cambio" name="cambio" readonly>
                                </div>
                            </div>
                               </form>
                               <br>
                        </div>
                        </div>
                        </div>
					</div>
					
</body>
</html>

<script type="text/javascript">

function CalcularCambio(e)
        {
            if (e.keyCode == 13)
            {
                tot_cobrar=$("#tot_cobrar").val();
                pago=$("#pago").val();
                
                var cambio = 0;
                                
                cambio=parseFloat(pago)-parseFloat(tot_cobrar);

                var importe = document.getElementById('cambio');
                importe.value = parseFloat(cambio).toFixed(2);
            }
        }
            
</script>