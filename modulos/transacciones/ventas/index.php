<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();	
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	date_default_timezone_set('America/Guayaquil');
	$fecha_actual=date('Y-m-d H:i:s');
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
	if((basename($url_autorizado['men_link'],"/"))==$URL_Visita_Ult){
?>
<script type="text/javascript">
	//Shadowbox.init();
</script>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
  	<title>Ventas</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?> 
    <link rel="stylesheet" href="../../../system/funciones/shadowbox/shadowbox.css" type="text/css" media="screen" />
	<script type="text/javascript" src="../../../system/funciones/shadowbox/shadowbox.js"></script>
</head>
    
<body >
     
	<?php include(RUTAcom.'menu-principal.php');	
	fnc_msgGritter();
	$num_row = fnc_numproago($sucursal['suc_id']); 
	$sqlref = sprintf("SELECT MAX(cab_ven_id) AS referencia FROM cabecera_ventas");
	$query = mysql_query($sqlref, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	
	if($row['referencia']==null)
	{
		$sqlref1 = sprintf("SELECT num_fac_ini FROM sucursal");
		$query1 = mysql_query($sqlref1, $conexion_mysql) or die(mysql_error());
		$row1 = mysql_fetch_assoc($query1);
		$ref=$row1['num_fac_ini']+1;
	}
	else
	{
		$ref=$row['referencia']+1;
	}					
	?>

	<div class="container">
		<div class="control-group well span11"> 
        <div align="right">  <strong><input type="button" style="font-style:oblique" align="left" class="btn btn-primary"  name="Otras_Ventas" id="Otras_Ventas" value="RECARGAS EN GENERAL" style="text-shadow:#C00" onClick="recargas()"></strong></div>

        
			<div class="page-header"> <h3 style="color:#36F">Factura Número: <span class="label label-success" style="font-size:22px; padding-top:10px; padding-right: 5px; padding-left:5px; padding-bottom:10px"><?php echo ' # '.$ref;?></span> </h3>

    			<div class="row-fluid">
                    <div class="span4">
                        <div class="control-group">
                            <label class="control-label">Cédula/Ruc</label>
                                <div class="controls">
                                    <input type="text" id="inputcedula" name="inputcedula" enable value="001" onclick = "$('#modalcliente').modal()";>
                                </div>      
                        </div>
                    </div>
        			<div class="span4">
        				<div class="control-group">
							<label class="control-label">Nombre</label>
								<div class="controls">
									<input type="text" id="inputnombre" name="inputnombre" enable value="Consumidor Final">
                </div>      
						</div>
					</div>
         			<div class="span2">
         				<div class="control-group">
							<label class="control-label">Fecha</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputfecha" name="inputfecha" value="<?php echo $fecha_actual; ?>" disabled>
								</div>
						</div>
         			</div>
        		</div>
    			<div class="row-fluid">
         			<div class="span8">
         				<div class="control-group">
							<label class="control-label">Dirección</label>
								<div class="controls">
									<input type="text" id="inputdireccion" name="inputdireccion" enable style="width:400px;" enable value="Principal" required>
                </div>      
						</div>
         			</div>  
         			<div class="span3">
         				<div class="control-group">
         					<label class="control-label">Telefono</label>
								<div class="controls">
									<input type="text" id="inputtelefono" name="inputtelefono" enable value="000-000" required>
                    			</div>
						</div>
         			</div>
				</div>
				<div class="row-fluid">
					<div class="span2">
						<div class="control-group">
							<label class="control-label">Producto</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputproducto" name="inputproducto" placeholder="Buscar Producto"  required>
									<a href="../ventas/listar_series.php?det_pro=<?php echo $row_RS_cta_pend['pac_cod']; ?>" rel="shadowbox;width=600;height=400" title="Factura" id="factura"><i class="icon-th-list"></i></a>
								</div>
						</div>
    				</div>
    				<div class="span6">
    					<div class="control-group">
        					<label class="control-label">Detalle</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputdetalle" name="inputdetalle" placeholder="Nombre del Producto"  disabled required>
								</div>
						</div>
					</div>
					<div class="span2">
						<div class="control-group">          
							<label class="control-label">Cantidad</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputcantidad" name="inputcantidad" enable value=1 required onkeypress="return solonumeros(event);" onKeyPress="">
								</div>
						</div>
    				</div>
				</div>
    		</div>
            <div class="row-fluid">
                	<div class="span4">
						<div class="control-group">
							<label class="control-label">Serie</label>
								<div class="controls">
									<input type="text" class="input-block-level" id="inputserie" name="inputserie">
								</div>
						</div>
    				</div>
                </div>
           </div>
    		<div class="control-group well span10">
        		<div class="row-fluid">
    				<div class="span8">
						<table id="list"><tr><td></td></tr></table> 
   							 <div id="pager"></div> 
					</div>
            		<form class="form-horizontal">
           			 	<div class="span4">
                  			<div class="control-group">
                        		<label class="control-label">Subtotal</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputsubt" name="inputsubt" disabled>
									</div>
							</div>
                   			<div class="control-group">
								<label class="control-label">IVA</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputiva" name="inputiva" disabled>
									</div>
							</div>
							<div class="control-group">
								<label class="control-label">Descuento %</label>
									<div class="controls">
										<input type="text" id="inputdescu" name="inputdescu" enable value=0  enable style="width:115px;">
									</div>
							</div>
                  			<div class="control-group">
								<label class="control-label">Total</label>
									<div class="controls">
										<input type="text" class="input-block-level" id="inputtot" name="inputtot" disabled style="height:70px; font-family: Arial; font-size: 28pt; color:red; width:130px;">

									</div>
							</div>
						</div>				
             		</form>
        		</div>
     		</div>
			<div class="row-fluid">
        		<div class="span3">
        			<label class="control-label">Forma de Pago</label>
        				<select name="Pagos" id="Pagos">
        					<option value="0">Contado</option>
							<option value="1">Credito</option>
							<option value="2">Tarjeta de Credito</option>
							<option value="3">Cheque</option>
						</select>
				</div>

				<div class="span2">
					<label class="control-label">Detalle de Pago</label>
            		<input type="text" class="input-block-level" id="banco" name="banco" placeholder="Ingrese el Banco";">
            		<input type="text" class="input-block-level" id="cta" name="cta" placeholder="ingrese el # de cuenta";">	
            		
            	</div>







            	<div class="span3">
            		<input type="button" class="btn btn-primary" name="guardar_Venta" id="guardar_Venta" value="GENERAR FACTURA" onclick="guardarVenta()">                  

        		<input type="button" class="btn btn-primary" name="nueva_venta" id="nueva_venta" value="NUEVA FACTURA" onclick="nuevaVenta()">
        		

            <input type="button" class="btn btn-primary" name="cobrar" id="cobrar" value="COBRAR" onclick="$('#modalcobrar').modal()">
            </div>
            <div class="span2">
        			<label class="control-label">Vendedor</label>
        			<?php 
                    fnc_listEmpleados('emp_id','per_nombre', 'persona', 'input-block-level', 'inputempleado', 'autofocus required');?>
			</div>	







		</div>
	</div>
    <!-- Modal -->
        <div align="center" id="modalcredito"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalcredito" aria-hidden="true">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="modalcredito"><strong>Abono del Crédito </strong></h4>
                <div align="center">
            </div>
          </div>
          <div class="modal-body">
            <?php include("abono.php"); ?>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
          </div>
        </div>
           <!-- Modal -->

        <!-- Modal Cobrar -->
          <div align="center" id="modalcobrar"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalcobrar" aria-hidden="true">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="modalcobrar"><strong>COBRAR</strong></h4>
                <div align="center">
            </div>
          </div>
          <div class="modal-body">
            <?php include("modal_cobrar.php"); ?>
          </div>
          

        <!-- Modal -->


           <!-- Modal Crear Cliente-->
        <div align="center" id="modalcliente"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalcliente" aria-hidden="true">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="modalcliente"><strong>Cliente</strong></h4>
                <div align="center">
            </div>
          </div>
          <div class="modal-body">
            <?php include("modal_clientes.php"); ?>
          </div>
        </div>
           <!-- Modal -->

        <div align="center" id="modalrecarga"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalrecarga" aria-hidden="true">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 id="modalcredito"><strong>RECARGAS</strong></h4>
                <div align="center">
              
            </div>
          </div>
          <div class="modal-body">
            <?php include("recargas.php"); ?>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
          </div>
        </div>


    <input type="hidden" id="Url" value="<?php echo $RUTAm."transacciones/ventas/funciones/funciones.php"; ?>">
    <input type="hidden" id="Url2" value="<?php echo $RUTAm."transacciones/ventas/funciones/funcionesPer.php"; ?>">
    <input type="hidden" id="id_suc" value="<?php echo $sucursal['suc_id']; ?>">
    <input type="hidden" id="rows" value="<?php echo $num_row; ?>">
	<input type="hidden" id="url_autocomplete" value="<?php echo $RUTAm."transacciones/ventas/funciones/autocomplete_ventas.php"; ?>">

    <input type="hidden" id="Urlnompro" value="<?php echo $RUTAm."transacciones/ventas/funciones/bus_nom_pro.php"; ?>">     
  
    <input type="hidden" id="vendedor" name="vendedor" value="<?php echo $persona['per_nombre'] ?>">
    <input type="hidden" id="referencia" name="referencia" value="<?php echo $ref ?>">
    <input type="hidden" id="fec_fac" name="fec_fac" value="<?php echo $fecha_actual ?>">

</body>

</html>
<?php }else
		{
			$_SESSION['MSG'] = 'Acceso no Autorizado';
			$_SESSION['MSGdes'] = 'PERMISOS INSUFICIENTES';
			$_SESSION['MSGimg'] = $RUTAi.'noautorizado.png';
			header("Location: ".$RUTAm);
			}?>
<script type="text/javascript">



var u = 0;
var sumtotal = 0;
var sumsubt = 0;
var sumiva = 0;
var auxcant;
grilla();
verificarcaja();
$( "#inputproducto" ).focus();
if($("#rows").val() > 0)
{
	$("#aviso_productos").show();
}
else
{
	$("#aviso_productos").hide();
}

        function grilla() {
            jQuery('#list').jqGrid({
                datatype: "clientSide",
                width: 700,
                async: false,
                colNames: ['idpro', 'Producto', 'Nombre Producto', 'Serie', 'Cantidad', 'precio', 'Subtotal', 'IVA', 'Total', 'AplicaIVA' ],
                colModel: [
				{ name: 'idpro', index: 'idpro', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
                        { name: 'producto', index: 'producto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
						{ name: 'nombreproducto', index: 'nombreproducto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'serie', index: 'serie', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, editable:true },
                        { name: 'cantidad', index: 'cantidad', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, editable: false, editrules: { number: true }, editoptions: { dataInit: function (elem) { $(elem).bind("keypress", function (e) { return soloLetrasB(e) }) } } },
						{ name: 'precio', index: 'precio', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, editable: true, editrules: { number: true }, editoptions: { dataInit: function (elem) { $(elem).bind("keypress", function (e) { return soloLetrasB(e) }) } } },
						{ name: 'subtotal', index: 'subtotal', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'iva', index: 'iva', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'total', index: 'total', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'aplicaiva', index: 'aplicaiva', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true }
                    ],
                rowNum: 100,
                rowList: [100],
                pager: '#pager',
                rownumbers: true,
                viewrecords: true,
                multiselect: true,
                sortorder: "desc",
                caption: "Lista Para Venta",
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
					var url = $("#Url").val();
					rowData1 = jQuery("#list").getRowData(rowid);
					$.ajax({
					url: url,
					type: "POST",
					async: false,
					data: {
						cantidad: rowData1['cantidad'],
						pro_cod: rowData1['producto'],
						suc_id: $("#id_suc").val(),
						accion: 'Verificar_Cantidad'
					},
					success:  function(resultado) {
						if(resultado == "True")
						{
							var tot;
							var iva;
							var subtotal = (parseFloat(rowData1['precio'])*rowData1['cantidad']);
							if(rowData1['aplicaiva'] == 'S')
							{
								iva = (subtotal)*0.12;
								tot = (subtotal)+(iva);
							}
							else
							{
								iva = 0;
								tot = (subtotal);
							}
							jQuery("#list").jqGrid('setRowData', rowid, { total: tot });
							jQuery("#list").jqGrid('setRowData', rowid, { iva: iva });
							jQuery("#list").jqGrid('setRowData', rowid, { subtotal: subtotal });

							sumiva = 0;
							sumtotal = 0;
							sumsubt = 0;

                    		var rows = $('#list tr:gt(0)');
							//sumar el total
							rows.children('td:nth-child(9)').each(function () {
								var y = $(this).text().replace(",", ".");
								sumtotal += parseFloat(y);
							});
							rows.children('td:nth-child(7)').each(function () {
								var y = $(this).text().replace(",", ".");
								sumsubt += parseFloat(y);
							});
							rows.children('td:nth-child(8)').each(function () {
								var y = $(this).text().replace(",", ".");
								sumiva += parseFloat(y);
							});
							$('#inputsubt').val(sumsubt.toFixed(2));
							$('#inputtot').val(sumtotal.toFixed(2));
							$('#inputiva').val(sumiva.toFixed(2));
						}
						else
						{
							calculatotales();
						}
					}
					});
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
											calculatotales();
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
			if($("#inputcantidad").val() == null || $("#inputcantidad").val() <= 0)
			{
				vex.dialog.alert ('Ingrese una Cantidad!');
			}
			else
			{
			var url = $("#Url").val();
			var accion = 'OBTENER_PRECIO';
			var datos;
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				pro_cod: $("#inputproducto").val(),
				suc_id: $("#id_suc").val(),
				accion: accion,
				cantidad: $("#inputcantidad").val()
			},
			success:  function(resultado) {
				resu = resultado.replace(/(\r\n|\n|\r)/gm,"");
				if(resu == "false")
				{
					vex.dialog.alert ('El Producto no está creado!');
					$( "#inputproducto" ).focus();
					
				}
				else if(resu == "stock")
				{
					vex.dialog.alert ('No hay suficiente en stock!');
					$( "#inputproducto" ).focus();
				}
				else
				{
				datos = JSON.parse(resultado);

				var res = verificarepuesto(datos['pro_id']);
				if(res == true)
				{
					
				}
				else
				{
					var met_gan = datos['met_gan_pvp'];
					var tot;
					var iva;
					var pvp;
					var precuni;
					var subtotal;
					var ivasubt;
					if(met_gan == 'f' && datos['est_iva'] == 'n')
					{
						precuni = (parseFloat(datos['det_pro_costo']))+(parseFloat(datos['val_gan_pvp']));
						var precunit = parseFloat(precuni).toFixed(2);
						iva = 0;
					}
					if(met_gan == 'f' && datos['est_iva'] == 's')
					{
						iva = (parseFloat(datos['det_pro_costo'])*0.12);
						precuni = (parseFloat(datos['det_pro_costo']))+(parseFloat(datos['val_gan_pvp'])) + iva;
						var precunit = parseFloat(precuni).toFixed(2);
					}
					if(met_gan == 'p' && datos['est_iva'] == 'n')
					{
						var por = ((parseFloat(datos['det_pro_costo'])) * (parseFloat(datos['val_gan_pvp'])))/100;
						precuni = ((parseFloat(datos['det_pro_costo']))+por);
						var precunit = parseFloat(precuni).toFixed(2);
						iva = 0;
					}
					if(met_gan == 'p' && datos['est_iva'] == 's')
					{
						iva = (parseFloat(datos['det_pro_costo'])*0.12);
						var por = ((parseFloat(datos['det_pro_costo'])) * (parseFloat(datos['val_gan_pvp'])))/100;
						precuni = (parseFloat(datos['det_pro_costo']))+por+iva;
						var precunit = parseFloat(precuni).toFixed(2);
					}

					subtotal = (precunit * $("#inputcantidad").val()).toFixed(2);
					
					if(datos['est_iva'] == 's')
					{
						ivasubt = (subtotal * 0.12).toFixed(2);
					}
					else
					{
						ivasubt = 0;
					}
					
					var total = (parseFloat(subtotal) + parseFloat(ivasubt)).toFixed(2);

					var mydata = [
                     { idpro: datos['pro_id'], producto: $("#inputproducto").val(), serie:datos['pro_serie'], nombreproducto: $("#inputdetalle").val(), cantidad: $("#inputcantidad").val(), precio: precunit, total: total, iva: ivasubt, aplicaiva: datos['est_iva'], subtotal: subtotal, serie: $("#inputserie").val() }
        			];
           																				
		var ids = jQuery("#list").jqGrid('getDataIDs');
 		if (ids.length <= 10)
 		{
											   
		    for (var i = 0; i <= mydata.length; i++) 
			{
                jQuery("#list").jqGrid('addRowData', u, mydata[i]);
                u++;
            };
			calculatotales();
				}
 		else
 		{
 			alert('Solo se puede agregar hasta 7 items');
		 }																																																				
				}
				}
			}
			});
			}
        }

function guardarVentaCuentas() {

	var num = jQuery("#list").jqGrid('getGridParam', 'records');
	
	if(num > 0)
	{
		var url = $("#Url2").val();
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
			total: $("#inputtot").val(),
			vendedor:$("#vendedor").val()
		},
		success:  function(resultado) {
			
			$("#guardar_Venta").hide();
			//$("#agregar_producto").hide();
			$("#nueva_venta").show();
		}
		});
	}
	else
	{
		vex.dialog.alert ('DEBE AGREGAR PRODUCTOS PARA REALIZAR LA VENTA!');
	}

}

function guardarVenta() {
	
	var num = jQuery("#list").jqGrid('getGridParam', 'records');
	if(num > 0)
		{
			var condPago=$("#Pagos").val();
			if(condPago!=1){
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
				total: $("#inputtot").val(),
				condPago: condPago,
				vendedor:$("#vendedor").val(),
				iva: $("#inputiva").val(),
				descuento: $("#inputdescu").val(),
				subtotal: $("#inputsubt").val(),
				referencia: $("#referencia").val(),
				cedula: $("#inputcedula").val(),
				vendedor: $("#inputempleado").val(),
				banco: $("#banco").val(),
				cta: $("#cta").val(),				
			},
			success:  function(resultado) {
				
				imprimir_factura();				
				//abrirVentanaIMP(resultado);
				$("#guardar_Venta").hide();
			//	$("#agregar_producto").hide();
				$("#nueva_venta").show();
        $("#cobrar").show();
        $("#tot_cobrar").val($("#inputtot").val());
			}
			});
		}
		else{
			ced=$('#inputcedula').val();
			if(ced !=001){
				document.getElementById('nomclielabel').innerHTML = $("#inputnombre").val();
				document.getElementById('salclielabel').innerHTML = $("#inputtot").val();
				$('#modalcredito').modal();
				}else
		{
			vex.dialog.alert ('NO SE PUEDE DAR CRÉDITO A UN CONSUMIDOR FINAL!');
		}
		
		}
		

	}
	else
		{
			vex.dialog.alert ('DEBE AGREGAR PRODUCTOS PARA REALIZAR LA VENTA!');
		}
}


function guardarVentaCredito() {

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
			condPago: $("#Pagos").val(),
			doc: $("#inputcedula").val(),
			vendedor:$("#vendedor").val(),
			abono: $('#abono').val(),
			sucursal: $('#id_suc').val(),
			vendedor:$("#vendedor").val(),
			iva: $("#inputiva").val(),
			descuento: $("#inputdescu").val(),
			subtotal: $("#inputsubt").val(),
			referencia: $("#referencia").val(),
			cedula: $("#inputcedula").val(),
			total: $("#inputtot").val()
		},
		success:  function(resultado) {
			vex.dialog.alert("Crédito realizado correctamente");
			abrirVentanaIMP(resultado);
			$("#guardar_Venta").hide();
			//$("#agregar_producto").hide();
			$("#nueva_venta").show();
		}
		}); 
	}
	else
	{
		vex.dialog.alert ('DEBE AGREGAR PRODUCTOS PARA REALIZAR LA VENTA!');
	}
}
//Funcion que verifica si ya está agregado el repuesto a la grilla de pedidos
        function verificarepuesto(idpro) {
            var ids = jQuery("#list").jqGrid('getDataIDs');
            for (var i = 0; i < ids.length; i++) {
                var pro = ids[i];
                var pasar = jQuery("#list").jqGrid('getRowData', pro);
                if ((pasar.idpro == idpro)) {
					
					var aux = parseInt(pasar.cantidad) + parseInt($("#inputcantidad").val());
					var url = $("#Url").val();
					var accion = 'OBTENER_PRECIO';
					var datos;
					$.ajax({
					url: url,
					type: "POST",
					async: false,
					data: {
						pro_cod: $("#inputproducto").val(),
						suc_id: $("#id_suc").val(),
						accion: accion,
						cantidad: aux
					},
					success:  function(resultado) {
						resu = resultado.replace(/(\r\n|\n|\r)/gm,"");
						if(resu == "stock")
						{
							vex.dialog.alert ('No hay suficiente en stock!');
							encerar();
							$( "#inputproducto" ).focus();
							return false;
						}
						else
						{
							var aux1 = (parseFloat(pasar.precio) * aux).toFixed(2);
							var ivag = 0;
							if(pasar.iva != 0)
							{
								ivag = (aux1 * 0.12).toFixed(2);
								jQuery("#list").jqGrid('setRowData', pro, { iva: ivag});
							}
							var totg = (parseFloat(aux1) + parseFloat(ivag)).toFixed(2);
		
							jQuery("#list").jqGrid('setRowData', pro, { cantidad: aux });
							jQuery("#list").jqGrid('setRowData', pro, { subtotal: aux1});
							jQuery("#list").jqGrid('setRowData', pro, { total: totg});
							
							encerar();
							calculatotales();
							return true;
						}
					}
					});
							return true;
						}
					}
        }

function soloLetrasB(e) {
            key = e.keyCode || e.which;
            tecla = String.fromCharCode(key).toLowerCase();
            letras = "0123456789";
            especiales = [8, 9, 39, 127, 37];
            tecla_especial = false
            for (var i in especiales) {
                if (key == especiales[i]) {
                    tecla_especial = true;
                }
            }
            if (letras.indexOf(tecla) == -1 && !tecla_especial)
                return false;
        }

function nuevaVenta() {
            location.reload();
        }
function recargas() {
            $('#modalrecarga').modal();
        }
function cliente() {
            $('#modalcliente').modal();
        }

function solonumeros(e)
{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;

	return /\d/.test(String.fromCharCode(keynum));
}

function calculatotales(){
	sumiva = 0;
	sumtotal = 0;
	sumsubt = 0;

    var rows = $('#list tr:gt(0)');
	//sumar el total
	rows.children('td:nth-child(11)').each(function () {
		var y = $(this).text().replace(",", ".");
		sumtotal += parseFloat(y);
	});
	rows.children('td:nth-child(9)').each(function () {
		var y = $(this).text().replace(",", ".");
		sumsubt += parseFloat(y);
	});
	rows.children('td:nth-child(10)').each(function () {
		var y = $(this).text().replace(",", ".");
		sumiva += parseFloat(y);
	});
	$('#inputsubt').val(sumsubt.toFixed(2));
	$('#inputtot').val(sumtotal.toFixed(2));
	$('#inputiva').val(sumiva.toFixed(2));
}
$('#inputcantidad').bind('keypress', function(e) {
if(e.which == 13) {
       	 addProducto();
         inputcantidad.value='1';
		 encerar();
   	 }
})

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

function verificarcaja(){
			var url = $("#Url").val();
			var accion = 'Verificar_Caja';
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				accion: accion,
				sucursal: $("#id_suc").val()
			},
			success:  function(resultado) {
				resu = resultado.replace(/(\r\n|\n|\r)/gm,"");
				if(resu == "False")
				{
					vex.dialog.alert ("LA CAJA ESTA CERRADA");
					$("#guardar_Venta").hide();
					//$("#agregar_producto").hide();
					$("#nueva_venta").hide();
          $("#cobrar").hide();
				}
        else
        {
           $("#cobrar").hide();
           $("#nueva_venta").hide();
        }
			}
            });
		}

$( "#inputproducto" ).autocomplete({
source: $("#url_autocomplete").val() ,//availableTags,
select: function( event, ui ) {
	
var elem = ui.item.code.split('-');
var prod = elem[0];
var seri = elem[1];

$("#inputproducto").val(prod);
$("#inputserie").val(seri);
$("#inputdetalle").val(prod);
},
focus: function( event, ui ) {

}
   });

function abrirVentanaIMP(Referencia) {
  url_impresion=$("#url_impresion").val();
  var nomcli=$("#inputnombre").val();
  var fec_fac= $("#fec_fac").val();
  var dir_cli= $("#inputdireccion").val();
  var tel_cli= $("#inputtelefono").val();
  var ced_cli= $("#inputcedula").val();

$.post(url_impresion, 
{ REF: Referencia, NOMCLI: nomcli,fec_fac:fec_fac,dir_cli:dir_cli,tel_cli:tel_cli,ced_cli:ced_cli }, 

function (result) {
            WinId = window.open('url_impresion', 'newwin', 'width=800,height=900');//resolucion de la ventana
            WinId.document.open();
            WinId.document.write(result);
            WinId.document.close();
        });
}

function cod_barra()
{
      var url = $("#Urlnompro").val();
  
      $.ajax
      ({
        url: url,
        type: "POST",
        async: false,
        data: 
        {  
          pro_codigo: $("#inputproducto").val()
        },
        success:  function(resultado) 
        {
          $("#inputdetalle").val(resultado);
          addProducto();
        }
      })
} 

function encerar(){
		$("#inputproducto").val("");
		$("#inputdetalle").val("");
	}

$('#inputproducto').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        cod_barra();
		encerar();
		$( "#inputproducto" ).focus();
    }
});

function imprimir_factura()
{	
var aux = $("#referencia").val();

		window.open( "../../impresion/facturaSPDF.php?referencia="+aux, "Impresion Factura" , "width=800 , height = 600");
	}
</script>
