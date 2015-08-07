<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	

	$pro_codigo = fnc_varGetPost('inputCod');
	$datstock = fnc_stockprecio($pro_id);
	

	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($id_user);
	$nombre_usuario = $persona['per_nombre'];
	$idsucursal = fnc_datSuc($empleado['suc_id']);

?>


<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title><?php echo $accion; ?> Stock</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <div class="container">
		<div class="page-header"><h3><?php echo strtoupper($accion); ?> STOCK</h3></div>
		<div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="<?php echo $RUTAm; ?>administrador/stock/index.php"> Stock</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $accion; ?> Stock</li>
                </ul>
			</div>
            
		</div>
		<div class="row-fluid">
			<div class="tabbable">
            	<ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Datos</a></li>
                </ul>

                <div class="row-fluid">
    				<div class="span4">
    					<strong>Stock</strong>

    						<div class="control-group">
	     						<input type="text"  id="stk_cantidad" name="stk_cantidad" placeholder="Producto" value="<?php echo $datstock['pro_nombre'];?>" readonly>
	     						<Br>	  
							</div>
    						<br>
    						
    						<br>
    							
    						</div>

							<div class="span4">
	    						<strong>PRECIO DE COMPRA</strong>
							    <br>
							    <input type="number" min="0" max="10000" id="det_pro_costo" name="det_pro_costo"placeholder="Costo del Producto" style="width:235px" value="<?php echo $datstock['det_pro_costo'];?>" required>
							    <br>
							    <div>
							    	Aplica IVA
							    </div>
						    	<select style="width:235px" id="est_iva" name="est_iva">
						         	
						         	<option value="s">SI</option>
						         	<option value="n">NO</option>
						        </select>
    						</div>

						    <input type="hidden" value="<?php echo $sucursal['suc_id'];?>" id="suc_id">
						    

							<div class="span4">
    							
    							Escoja el Metodo de Ganacia
						    	<select style="width:235px" id="met_gan_pvp" name="met_gan_pvp">
						          
						          <option value="f">Fijo</option>
						          <option value="p">Porcentual</option>
						        </select>
        						
        						<input type="number" min="0" max="10000" id="val_gan_pvp" name="val_gan_pvp" placeholder="Ingrese un Valor" style="width:200px" onkeypress= "CalcularPVP(event)" value="<?php echo $datstock['val_gan_pvp'];?>" >
        						<div>
						    		<strong>PRECIO DE VENTA</strong>
						    		<br>
						    		<input type="number" min="0" max="10000" id="input_prec_vent" name="input_prec_vent" placeholder="P.V.P" style="width:235px" value="<?php echo $datstock['pvp'];?>" readonly>
						    	</div>
   							</div>
					</div>
									
					<div class="form-actions">

						<div>
    						<a class="btn btn-warning" onClick="Actualizarstock()" id="btnactualizar" type ="hide">Actualizar</a> 
    					
							<?php echo $button; ?>
							<a href="<?php echo $RUTAm; ?>administrador/stock/index.php" type="button" class="btn">CANCELAR</a>
						</div>
						
						<input type="hidden" id="input_ganancia" name="input_ganancia" value="<?php echo $datstock['met_gan_pvp'];?>">

						<input type="hidden" id="input_iva" name="input_iva" value="<?php echo $datstock['est_iva'];?>">


                        
                        <input type="hidden" id="input_sucursal" name="input_sucursal" value="<?php echo $idsucursal['suc_id']; ?>">
                        <input type="hidden" id="empleado" name="empleado" value="<?php echo $empleado; ?>">
                        
                        <input type="hidden" id="nombre_usuario" name="nombre_usuario" value="<?php echo $nombre_usuario; ?>">
                        
                        <input type="hidden" name="pro_id" id="pro_id" value="<?php echo $pro_id; ?>">
					
					</div>
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

<script type="text/javascript">

$("select[name$='met_gan_pvp']").val($('#input_ganancia').val());
$("select[name$='est_iva']").val($('#input_iva').val());


function Actualizarstock()

{		
	suc_id=$("#suc_id").val();
    pro_id=$("#pro_id").val();
			
	det_pro_costo=$("#det_pro_costo").val();
	val_gan_pvp=$("#val_gan_pvp").val();
	met_gan_pvp=$("#met_gan_pvp").val().replace(",", ".");
	est_iva=$("#est_iva").val();
	input_prec_vent=$("#input_prec_vent").val();
			
	parametros="&suc_id="+suc_id+"&pro_id="+pro_id+"&det_pro_costo="+det_pro_costo+"&val_gan_pvp="+val_gan_pvp+"&met_gan_pvp="+met_gan_pvp+"&est_iva="+est_iva+"&accion=Actualizar"+"&input_prec_vent="+input_prec_vent;
			
    $.ajax
    ({
		type: "POST",
		url: "funciones/funciones.php",
		data: parametros,
		success : function (resultado)
		{ 
			vex.dialog.alert ('Actualización con Éxito');

		}
	});
}



function CalcularPVP(e)
		{
			if (e.keyCode == 13)
			{
				det_pro_costo=$("#det_pro_costo").val();
				val_gan_pvp=$("#val_gan_pvp").val();
				met_gan_pvp=$("#met_gan_pvp").val();
				est_iva=$("#est_iva").val();
				var pvp = 0;
				var iva = 0;
				var porce =0;
			
				if((est_iva=='n') && (met_gan_pvp=='f'))
				{	
					pvp=parseFloat(det_pro_costo)+parseFloat(val_gan_pvp);
				
			 	}
		 		if((est_iva=='n') && (met_gan_pvp=='p'))
		 		{
					porce=(parseFloat(det_pro_costo)*parseFloat(val_gan_pvp))/100;
					pvp=parseFloat(det_pro_costo)+porce;
				}
		 		if((est_iva=='s') && (met_gan_pvp=='f'))
		 		{
					aux=parseFloat(det_pro_costo)+parseFloat(val_gan_pvp);
					iva=(parseFloat(aux)*12)/100;
					pvp=parseFloat(aux)+parseFloat(iva);
				}
			
				if((est_iva=='s') && (met_gan_pvp=='p'))
				{					
					porce=(parseFloat(det_pro_costo)*parseFloat(val_gan_pvp))/100;
					aux=parseFloat(det_pro_costo)+parseFloat(porce);
					iva=(parseFloat(aux)*12)/100;					
					pvp=parseFloat(aux)+parseFloat(iva);
				}
				var importe = document.getElementById('input_prec_vent');
   				importe.value = pvp.toFixed(2);
   			}
		}



</script>




