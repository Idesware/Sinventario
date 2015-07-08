<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$id_user = $_SESSION['id_usuario'];
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
	if((basename($url_autorizado['men_link'],"/"))==$URL_Visita_Ult){
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_usuario($id_user);
	$vendedor=$persona['usr_nombre'];
	
	$sucursal = fnc_datSuc($empleado['suc_id']);
	date_default_timezone_set('America/Guayaquil');
	$fecha_actual=date('Y-m-d H:i:s');
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Compras</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>      
</head>
<body>
     
	<?php include(RUTAcom.'menu-principal.php');
	?>
    
    <!-- Crear el Formulario Compras -->

    <div class="container"><!-- Contenedor Toda la Compra -->

    	<div class="control-group well span11"><!-- Contenedor Cabecera Compra -->

    		<!-- Cabecera de Compra -->

			<div class="page-header"><h3>Registar Factura de Compra</h3>
        
        		<div class="row-fluid"><!-- Primera Fila Ruc/Proveedor/Fecha_Com-->
       		
					<div class="span4">
         				<div class="control-group">
            				<label class="control-label">Ruc</label>
                				<div class="controls">
                    				<input type="text" id="inputruc" name="inputruc" placeholder="Ingrese el Ruc de su Proveedor" required>
                    			</div>      
            			</div>
        			</div>

        			<div class="span4">
        				<div class="control-group">
							<label class="control-label">Proveedor</label>
								<div class="controls">
									<input type="text" id="inputprove" name="inputprove" readonly>
                    			</div>      
						</div>
					</div>

					<div class="span2">
                		<div class="control-group">
							<label class="control-label">Fecha de Emision</label>
								<div class="controls">
									<input type="text" id="inputfecha_com" name="inputfecha_com" placeholder="yy-mm-dd" required>
								</div>
						</div>
					</div>
				</div>

				<div class="row-fluid"><!-- Segunda Linea Direccion/Telefono/numero de Factura -->
         
         			<div class="span4">
         				<div class="control-group">
							<label class="control-label">Direccion</label>
								<div class="controls">
									<input type="text" id="inputdireccion" name="inputdireccion" readonly>
                   				</div>      
						</div>
         			</div>  
         
         			<div class="span4">
         				<div class="control-group">
         					<label class="control-label">Telefono</label>
								<div class="controls">
									<input type="text" id="inputtelefono" name="inputtelefono" readonly>
                    			</div>      
						</div>
         			</div>

					<div class="span2">
         				<div class="control-group">
         					<label class="control-label">Numero de Factura</label>
								<div class="controls">
									<input type="text" id="inputfact_pro" name="inputfact_pro"  required>
                    			</div>      
						</div>
         			</div>
				</div>

				<div class="row-fluid"><!-- Tercera Linea Producto/Detalle -->
			
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Producto</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputproducto" name="inputproducto" placeholder="Codigo Producto" required>
								</div>
						</div>
    				</div>  
			
    				<div class="span6">
    					<div class="control-group">
        					<label class="control-label">Detalle</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputdetalle" name="inputdetalle" placeholder="Buscar" required>
								</div>
						</div>
					</div>

					<div class="span2">
						<div class="control-group">          
							<label class="control-label">Cantidad</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputcantidad" name="inputcantidad" enable value=0 required onkeypress="return solonumeros(event);" onKeyPress="enter">
								</div>
						</div>
    				</div>
				</div>

			</div>		
			
			<div class="control-group well span10"> <!-- Contenedor Detalle Compra -->
		
        		<div class="row-fluid"><!-- Grilla Detalle Compra -->

					<div class="span8">
						<table id="list"><tr><td></td></tr></table> 
    						<div id="pager"></div> 
					</div>

					<form class="form-horizontal">

						<!-- Columna Subtotal/IVA/Descuento/Total -->
						
						<div class="span4">
							
							<div class="control-group">	
                        		<label class="control-label">Subtotal</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputsubt" name="inputsubt" disabled>
									</div>
							</div>
                   		
                   			<div class="control-group">
								<label class="control-label">IVA 12%</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputiva" name="inputiva" disabled>
									</div>
							</div>
                            
                            <div class="control-group">
								<label class="control-label">IVA 0%</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputivacero" name="inputivacero" disabled>
									</div>
							</div>

							<div class="control-group">
								<label class="control-label">Descuento %</label>
									<div class="controls">
										<input type="text" id="inputdescu" name="inputdescu" enable value=0  enable style="width:115px;" onkeypress="return solonumeros(event);">
									</div>
							</div>
                  		
                  			<div class="control-group">
								<label class="control-label">Total</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputtot" name="inputtot" disabled>
									</div>
							</div>
						</div>
    				</form>
            	</div>
        	</div>

        	<div class="row-fluid"> <!-- Pie de Compra Botones -->

        		<input type="button" class="btn btn-primary" name="guardar_Compra" id="guardar_Compra" value="GUARDAR COMPRA" onclick="guardarCompra()">
        		<input type="button" class="btn btn-primary" name="nueva_Compra" id="nueva_venta" value="NUEVA COMPRA" onclick="nuevaCompra()">
                <a href="<?php echo $RUTAm."administrador/proveedores/"; ?>" class="btn btn-primary" role="button">CREAR PROVEEDOR</a>
			</div>	
		</div>
    </div>
    	
    <input type="hidden" id="Url" value="<?php echo $RUTAm."transacciones/compras/funciones/funciones.php"; ?>">
    <input type="hidden" id="id_suc" value="<?php echo $sucursal['suc_id']; ?>">
    <input type="hidden" id="usuario" value="<?php echo $vendedor; ?>">
    <input type="hidden" id="url_autocomplete" value="<?php echo $RUTAm."transacciones/compras/funciones/autocomplete_compras.php"; ?>">
    <input type="hidden" id="url_datos_proveedor" value="<?php echo $RUTAm."transacciones/compras/funciones/fnc_datos_proveedor.php"; ?>">
    <input type="hidden" id="url_autocomplete_proveedor" value="<?php echo $RUTAm."transacciones/compras/funciones/autocomplete_proveedor.php"; ?>">
    
	<br>
    
</body>
<footer>	
</footer>
</html>

<script type="text/javascript">
var u = 0;
grilla();


$("#inputfecha_com").datepicker({
            dateFormat: 'dd/mm/yy',
            defaultDate: "+1d",
            maxDate: "+D",
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true
        });

        function grilla() {
            jQuery('#list').jqGrid({
                datatype: "clientSide",
                width: 700,
                async: false,
                colNames: ['IdPro', 'Producto', 'Nombre Producto', 'Cantidad', 'Precio', 'Subtotal', 'IVA', 'Total' ],
                colModel: [
				{ name: 'idpro', index: 'idpro', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true  },
                        { name: 'producto', index: 'producto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
						{ name: 'nombreproducto', index: 'nombreproducto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'cantidad', index: 'cantidad', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, editable: true, editrules: { number: true }, editoptions: { dataInit: function (elem) { $(elem).bind("keypress", function (e) { return soloLetrasB(e) }) } } },
						{ name: 'precio', index: 'precio', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, editable: true, editrules: { number: true }, editoptions: { dataInit: function (elem) { $(elem).bind("keypress", function (e) { return soloLetrasB(e) }) } } },
						{ name: 'subtotal', index: 'subtotal', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'iva', index: 'iva', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, editable: true, editoptions: { dataInit: function (elem) { $(elem).bind("keypress", function (e) { return soloLetrasB(e) }) } } },
						{ name: 'total', index: 'total', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                    ],
                rowNum: 100,
                rowList: [100,200,300],
                pager: '#pager',
                rownumbers: true,
                viewrecords: true,
                multiselect: true,
                sortorder: "desc",
                caption: "Lista Para Compra",
                cellEdit: true,
                cellsubmit: 'clientArray',
                onSelectRow: function (ids) {
                },
				afterEditCell: function (rowid, name, value, iRow, iCol) {
                    saverow = iRow;
                    savecol = iCol;
                },
				afterSaveCell: function (rowid, name, value, iRow, iCol) {
                    //jQuery("#list").jqGrid('setRowData', rowid, { precio: 3 });
					var rowData1 = jQuery("#list").getRowData(rowid);
					var tot;
					var iva;
					var subtotal = (parseFloat(rowData1['precio'])*rowData1['cantidad']).toFixed(2);
					if(rowData1['iva'] != "")
					{
						tot = subtotal;
						iva = parseFloat((rowData1['iva'] / 100) * subtotal).toFixed(2);
						//tot = parseFloat(subtotal + parseFloat(rowData1['iva'])).toFixed(2);
						
					}
					else
					{
						iva = 0;
						tot = subtotal;
					}
					
					tot = (parseFloat(tot) + parseFloat(iva)).toFixed(2);
					
					jQuery("#list").jqGrid('setRowData', rowid, { total: tot });
					jQuery("#list").jqGrid('setRowData', rowid, { subtotal: subtotal });
					jQuery("#list").jqGrid('setRowData', rowid, { iva: iva });
					calculatotales();

                }
            });
        jQuery("#list").jqGrid('navGrid', '#pager', { edit: false, add: false, del: false, search: false },
                            {// parámetros para la edición 
                                closeAfterEdit: true, reloadAfterSubmit: true, viewPagerButtons: false,
                                afterSubmit: function (response, postdata) {

                                    if (response.responseText != "true") {
                                        return [false, response.responseText]
                                    }
                                    else {
                                        return [true]
                                    }
                                }
                            },
                            {     // parámetros para el ingreso 
                                closeAfterAdd: true, width: '100%',
                                afterSubmit: function (response, postdata) {

                                    if (response.responseText != "true") {
                                        return [false, response.responseText]
                                    }
                                    else {
                                        return [true]
                                    }
                                }
                            },
                            {     // parámetros para la eliminación 
                                closeAfterAdd: true, width: '100%',
                                afterSubmit: function (response, postdata) {

                                    if (response.responseText != "true") {
                                        return [false, response.responseText]
                                    }
                                    else {
                                        return [true]
                                    }
                                }
                            },
                            {}
                        )
                        .navButtonAdd('#pager', {
                            caption: "",
                            buttonicon: "ui-icon-trash",
                            onClickButton: function () {
                                var filaSel = jQuery('#list').jqGrid('getGridParam', 'selrow');
                                if (filaSel != null) {
                                    //                                        
                                    //variable para escoger los id del detalle para eliminacion logica
                                            var rowdata = jQuery("#list").jqGrid('getRowData', filaSel);
                                            $("#list").jqGrid('delRowData', filaSel);
                                            $("#list").jqGrid('resetSelection');
                                } else {

                                    $.notifyBar({
                                        html: "Detalle, seleccione una fila",
                                        delay: 3000,
                                        animationSpeed: "normal"
                                    });
                                }
                            },
                            title: "Eliminar Fila",
                            id: "btnEliminar",
                            position: "Second"
                        });
        }
		
        function addProducto() {
			var url = $("#Url").val();
			var accion = 'AGREGAR_PPRODUCTO';
			var datos;
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				pro_cod: $("#inputproducto").val(),
				suc_id: $("#id_suc").val(),
				accion: accion
			},
			success:  function(resultado) {
				if(resultado == "false")
				{
					vex.dialog.alert ('Verifique si el producto tiene precio!');
				}
				else
				{
				datos = JSON.parse(resultado);
				
				var res = verificarepuesto(datos['pro_id']);
				if(res == true)
				{
					vex.dialog.alert ('Ya Esta Agregado El Repuesto!');
				}
				else
				{
				var mydata = [
                     { idpro: datos['pro_id'], producto: $("#inputproducto").val(), cantidad: $("#inputcantidad").val(), precio: 0, total: 0, iva: 0, subtotal: 0, nombreproducto: $("#inputdetalle").val() }
        ];

            for (var i = 0; i <= mydata.length; i++) {
                jQuery("#list").jqGrid('addRowData', u, mydata[i]);
                u++;
            };
			calculatotales();
				}
				}
			},
			});
        }
		
function guardarCompra() {
	//var myIDs = $("#list").jqGrid('getDataIDs');
	//var a = jQuery("#list").getDataIDs().length;
	var num = jQuery("#list").jqGrid('getGridParam', 'records');
	
	if(num > 0)
	{
		var url = $("#Url").val();
		var accion = 'GUARDAR';
		var gridData = jQuery("#list").getRowData();
		var postData = JSON.stringify(gridData);
		$.ajax({
		url: url,
		type: "POST",
		async: false,
		data: {
			jsarr: postData,
			accion: accion,
			sucursal: $("#id_suc").val(),
			usuario: $("#usuario").val(),
			totalcab: $("#inputtot").val(),
			totiva: $("#inputiva").val(),
			rucpro: $("#inputruc").val(),
			numfac: $("#inputfact_pro").val(),
			feccom: $("#inputfecha_com").val(),
			desc: $("#inputdescu").val(),
			nompro: $("#inputprove").val(),
		},
		success:  function(resultado) {
			vex.dialog.alert (resultado);
			$("#guardar_Compra").hide();
			$("#agregar_producto").hide();
			$("#nueva_compra").show();
		},
		});
	}
	else
	{
		vex.dialog.alert ('DEBE AGREGAR PRODUCTOS PARA REALIZAR LA COMPRA!');
	}
}

//Funcion que verifica si ya está agregado el repuesto a la grilla de pedidos
        function verificarepuesto(idpro) {
            var ids = jQuery("#list").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var pro = ids[i];
                var pasar = jQuery("#list").jqGrid('getRowData', pro);
                if ((pasar.idpro == idpro)) {
                    return true;
                }
            }
        }

function soloLetrasB(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toLowerCase();
            letras = "0123456789";
            especiales = [8, 9, 39, 127, 37, 46];
            tecla_especial = false
            for (var i in especiales) {
                if (key == especiales[i]) {
                    tecla_especial = true;
                }
            }
            if (letras.indexOf(tecla) == -1 && !tecla_especial)
                return false;
        }
		
$('#inputdescu').bind('keypress', function(e) {
if(e.which == 13) {
       	 var t = $('#inputtot').val() ;
		 if($('#inputdescu').val() == 0)
		 {
			 calculatotales();
		 }
		else
		{
		 var desc = (t / 100) * $('#inputdescu').val();
		 $('#inputtot').val((t - desc).toFixed(2));
		}
   	 }
});

function nuevaCompra() {
            location.reload();
        }
	

function solonumeros(e)
{
var keynum = window.event ? window.event.keyCode : e.which;
if ((keynum == 8) || (keynum == 46) && (keynum == 57))
return true;
 
return /\d/.test(String.fromCharCode(keynum));
}



$('#inputcantidad').bind('keypress', function(e) {
if(e.which == 13) {
       	 addProducto();
		 $("#inputproducto").val("");
		 $("#inputdetalle").val("");
   	 }
});

$( "#inputproducto" ).autocomplete({
source: $("#url_autocomplete").val() ,//availableTags,
select: function( event, ui ) { 
//alert(ui.item.code);
//openDetCli(ui.item.code);
$("#inputproducto").val(ui.item.code);
$("#inputdetalle").val(ui.item.label);
},
focus: function( event, ui ) {
//alert("focus");
//showDetCli(ui.item.code);
}
   }); 
   
   $( "#inputruc" ).autocomplete({
source: $("#url_autocomplete_proveedor").val() ,//availableTags,
select: function( event, ui ) { 
$("#inputruc").val(ui.item.code);
$("#inputprove").val(ui.item.label);
$.ajax({
		url: $("#url_datos_proveedor").val(),
		type: "POST",
		async: false,
		data: {
			codigo: $("#inputruc").val()
		},
		success:  function(resultado) {
			datos = JSON.parse(resultado);
			$("#inputdireccion").val(datos['per_direccion1']);
			$("#inputtelefono").val(datos['per_telefono']);
		},
		});
},
focus: function( event, ui ) {
//alert("focus");
//showDetCli(ui.item.code);
}
   }); 
   
   function calculatotales(){
	sumiva = 0;
	sumtotal = 0;
	sumsubt = 0;
							
		var rows = $('#list tr:gt(0)');
		//sumar el total
		rows.children('td:nth-child(10)').each(function () {
			var y = $(this).text().replace(",", ".");
			sumtotal += parseFloat(y);
		});
		rows.children('td:nth-child(8)').each(function () {
			var y = $(this).text().replace(",", ".");
			sumsubt += parseFloat(y);
		});
		rows.children('td:nth-child(9)').each(function () {
			var y = $(this).text().replace(",", ".");
			sumiva += parseFloat(y);
		});
		$('#inputsubt').val(sumsubt.toFixed(2));
		$('#inputiva').val(sumiva.toFixed(2));
		var s = sumsubt;
		var i = sumiva;
		var t = parseFloat(s) + parseFloat(i);
		$('#inputtot').val(t);
		
		var ids = jQuery("#list").jqGrid('getDataIDs');
		var aux1 = 0;
		var aux2 = 0;
            for (var i = 0; i < ids.length; i++) {
                var pro = ids[i];
                var pasar = jQuery("#list").jqGrid('getRowData', pro);
                //var aux = aux + pasar.subtotal;
				//var aux1 = aux1 + pasar.iva;
				if(pasar.iva == 0)
				{
					aux2 = aux2 + parseFloat(pasar.subtotal);
				}
				else
				{
					aux1 = aux1 + parseFloat(pasar.subtotal);
				}
            }
			$('#inputsubt').val(aux1.toFixed(2));
			$('#inputivacero').val(parseFloat(aux2));
		//$('#inputivacero').val(aux2.toFixed(2));
	}

</script>
<?php }else
		{
			$_SESSION['MSG'] = 'Acceso no Autorizado';
			$_SESSION['MSGdes'] = 'PERMISOS INSUFICIENTES';
			$_SESSION['MSGimg'] = $RUTAi.'noautorizado.png';
			header("Location: ".$RUTAm);
			}?>