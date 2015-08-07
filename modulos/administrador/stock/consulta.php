
<script>

$('#productos').chosen({
			autoFocus: true
		});
$(document).on('ready', function(){
	Tabla();
			$("#productos").autocomplete({
				source: 'funciones/bus-productos.php',
				minLength: 1,
				autoFocus: true
				
			});
			$("#productos").chosen({});
			$("#productos").change( function (){
			validar();
			});
		});
		function msg(){
            $("#gritter").load("msg.php");
        }
		
		function validar(){
			
			SucId=$("#suc_id").val();
           	CodPro=$("#productos").val();
			parametros="&suc_id="+SucId+"&idpro="+CodPro;
            $.ajax({
			type: "POST",
			url: "funciones/bus-stock.php",
			data: parametros,
			success : function (resultado){
				if (resultado!=0)
				{
					alert('Item registrado en Stock');
				};
        	}
		});
		msg();
		}


		function Cargar(){
			
			suc_id=$("#suc_id").val();
           	idpro=$("#productos").val();
			
			stk_cantidad=$("#stk_cantidad").val();
			stk_minimo=$("#stk_minimo").val();
			det_pro_costo=$("#det_pro_costo").val();
			val_gan_pvp=$("#val_gan_pvp").val();
			met_gan_pvp=$("#met_gan_pvp").val().replace(",", ".");
			est_iva=$("#est_iva").val();
			input_prec_vent=$("#input_prec_vent").val();
			
			parametros="&suc_id="+suc_id+"&idpro="+idpro+"&stk_cantidad="+stk_cantidad+"&stk_minimo="+stk_minimo+"&det_pro_costo="+det_pro_costo+"&val_gan_pvp="+val_gan_pvp+"&met_gan_pvp="+met_gan_pvp+"&est_iva="+est_iva+"&accion=Insertar"+"&input_prec_vent="+input_prec_vent;
			
       		 $.ajax({
			type: "POST",
			url: "funciones/funciones.php",
			data: parametros,
			success : function (resultado){ 
			//alert(resultado);			 
			Tabla();
			$("#suc_id").val("");
           	$("#productos").val("");
			$("#stk_cantidad").val("");
			$("#stk_minimo").val("");
			$("#det_pro_costo").val("");
			//$("#pvp").val("");
			$("#est_iva").val("");
			$("#prov_id").val("");
			}
   			 });
		msg();
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
 
 <?php 
 $sqltabp = sprintf("SELECT * FROM detalle_producto inner join stock on stock.det_pro_id = detalle_producto.det_pro_id inner join producto on detalle_producto.pro_id = producto.pro_id WHERE det_pro_eliminado ='N'");
	$query = mysql_query($sqltabp, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
 ?>      
<div class="row-fluid">
    <div class="span4">
    <strong>Stock</strong>

    <div class="control-group">
	     <div class="controls" id="chosen_cat" style="height:20px; width:250px ">
	           <?php 
					$idSel='';
					fnc_listPro($idSel,'input-block-level', 'productos', 'autofocus required', 'onChange="Validar()"');
				?>
	     </div> 
	   
	</div>
    <br>
    <input type="number" min="0" max="1000" id="stk_cantidad" name="stk_cantidad" placeholder="Cantidad" style="width:115px" required>
    <input type="number" min="0" max="100" id="stk_minimo" name="stk_minimo" placeholder="Cantidad Minima " style="width:115px" required>
    <div>
    	<a class="btn btn-warning" onClick="Cargar()" id="btncargar" type ="hide">Cargar</a> 
    </div>
    </div>

	<div class="span4">
    <strong>PRECIO DE COMPRA</strong>
    <br>
    <input type="number" min="0" max="10000" id="det_pro_costo" name="det_pro_costo"placeholder="Costo del Producto" style="width:235px" required>
    <br>
    <div>
    Aplica IVA
    </div>
    	<select style="width:235px" id="est_iva" name="est_iva">
          <option value="s">SI</option>
          <option value="n" selected>NO</option>
        </select>
    </div>

    <input type="hidden" value="<?php echo $sucursal['suc_id'];?>" id="suc_id">
    
    <input type="hidden" id="prov_id" name="prov_id">

	<div class="span4">
    Escoja el Metodo de Ganacia
    	<select style="width:235px" id="met_gan_pvp" name="met_gan_pvp" >
          <option value="f" selected>Fijo</option>
          <option value="p">Porcentual</option>
        </select>
        <input type="number" min="0" max="10000" id="val_gan_pvp" name="val_gan_pvp" placeholder="Ingrese un Valor" style="width:200px" onkeypress= "CalcularPVP(event)">
        <div>
    		<strong>PRECIO DE VENTA</strong>
    		<br>
    		<input type="number" min="0" max="10000" id="input_prec_vent" name="input_prec_vent" placeholder="P.V.P" style="width:235px" readonly>
    	</div>
    </div>

    <div class="span4">
    <?php if($tot_rows<=40)
 {?>
        <?php } else{
	?>
     <div style="background:#0C9; font-size:18px; text-decoration:inherit; color:#003; padding:50px; text-align:center" >
        	Sistema funcionando <strong style="color:#000">"CORRECTAMENTE"</strong> .
        </div>
      <?php } ?>
    
    </div><strong></strong>
    <br>
    <div  id="gritter"></div>
</div>






