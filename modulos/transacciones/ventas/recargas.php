
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>

    <script type="text/javascript">
	function solonumeros(e)
	{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	 
	return /\d/.test(String.fromCharCode(keynum));
	}
	
	function validarTV()
	{
		producto=$("#producto").val();
		if((producto=='Recarga DirecTV')||(producto=='Copias_Negro')||(producto=='Copias_Color')||(producto=='Internet_Turno'))
		{	
			 document.getElementById("icon").style.visibility = "hidden";
			 document.getElementById("numrecl").style.visibility = "hidden";
			$("#numrec").hide();
			$("#numrec").val("NO APLICA");
		}else
		{$("#numrec").show();
			document.getElementById("icon").style.visibility = "visible";
			document.getElementById("numrecl").style.visibility = "visible";
			$("#numrec").val("");
			}
		
	}
	function derRef()
	{
		
		}
	function regRec()
	{
		des=$("#producto").val();
		val_reg=$("#vrecarga").val();
		rec_num=$("#numrec").val();
		if(val_reg){	
			parametros="des="+des+"&val_reg="+val_reg+"&rec_num="+rec_num+"&accion=GUARDAR";
			$.ajax({
			type: "POST",
			url: "funciones/func_rec_cop.php",
			data: parametros,
			success : function (resultado)
			{ 	
			vex.dialog.alert (resultado);
			}
			});
		}else
		{
			vex.dialog.alert ('INGRESE EL VALOR DE LA RECARGA !');
		}	
	}
</script>
</head>
<body >
<form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>transacciones/ventas/funciones/funciones.php">
                	<div class="row-fluid">
                    	<div class="span12 well well-small">
                        	<div class="row-fluid">
                            	<div class="span12">
                                <br>
                                   <div class="control-group">
                                        <label class="control-label"><strong>Producto</strong></label>
                                        <div >
                                            <select id="producto" name="producto" onChange="validarTV()">
                                              <option value="Recarga Movistar">Recarga Movistar</option>
                                              <option value="Recarga Claro">Recarga Claro</option>
                                              <option value="Recarga CNT o Alegro">Recarga CNT o Alegro</option>
                                              <option value="Recarga DirecTV">Recarga DirecTV</option>
                                              <option value="Copias_Negro">Copias a Negro</option>
                                              <option value="Copias_Color">Copias a Color</option>
                                              <option value="Internet_Turno">Internet del Turno</option>
                                            </select>
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
                                        <label class="control-label" id="numrecl" name="numrecl"><strong>Número de Celular</strong></label>
                                        <div class="controls">
                                        	<div class="input-prepend input-append">
  												<span class="add-on" id="icon" name="icon"><i class="icon-hand-right"></i></span>
                                            	<input type="text" class="input-block-level" id="numrec" name="numrec"  placeholder="099000000" required onkeypress="return solonumeros(event);" maxlength="10">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-group">
                                    	
                                        <label class="control-label"><strong>Valor</strong></label>
                                        <div class="controls">
                                        	<div class="input-prepend input-append">
  												<span class="add-on">$</span>
                                            	<input type="number" class="input-block-level" id="vrecarga" name="vrecarga"  placeholder=" 0.00" min="0" required onkeypress="return solonumeros(event);" maxlength="13">
                                        	</div>
                                        </div>
                                    </div>
								</div>
                                                              
                               </form>
                            <a class="btn btn-success" onClick="regRec()" data-dismiss="modal" aria-hidden="true" style="padding-top:2px;">Grabar Transacción</a>

                        </div>
                        </div>
                        </div>
					</div>
					<div align="center">
                        <input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">
                        <input type="hidden" name="idEmp" id="idEmp" value="<?php echo $idEmp; ?>">
                        <input type="hidden" name="existPer" id="existPer" value="0">
                        <input type="hidden" name="perId" id="perId">
					</div>
				

</body>

</html>