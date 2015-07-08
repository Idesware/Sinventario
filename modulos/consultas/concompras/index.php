<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
	if((basename($url_autorizado['men_link'],"/"))==$URL_Visita_Ult){
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Reporte Ventas</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>    
    
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php');
	?>
    <br/>
    <div class="container" align="center">
		<div class="control-group well span11">
	
			<div class="page-header"> <h4>REPORTE COMPRAS</h4>
            <br/>
                <div class="row-fluid">
                	<div class="span5">
        				<div class="control-group">
							<label class="control-label">Fecha Inicio</label>
								<div class="controls">
									<input type="text" id="inputfechainicio" name="inputfechainicio" enable placeholder=" Escoja Fecha Inicio">
                    			</div>      
						</div>
					</div>
                           
         			<div class="span5">
         				<div class="control-group">
            				<label class="control-label">Fecha Fin</label>
                				<div class="controls">
                    				<input type="text" id="inputfechafin" name="inputfechafin" enable placeholder=" Escoja Fecha Fin">
                    			</div>      
            			</div>
        			</div>
                    

                    <div class="span2">
         				<div class="control-group">
                        <br>
            				<input type="button" class="btn btn-primary" value="IMPRIMIR REPORTE" onClick="reporte()">
            			</div>
        			</div>
                    
                </div>
            </div>
            <div class="container">
        <div class="row-fluid">
        	<table id="list"><tr><td></td></tr></table> 
        <div id="pager"></div> 
    	</div>
	</div>
       </div>
    </div>
    <br/>
    <div class="container">
    <div class="row-fluid">
    	<div >
			<table id="list2"><tr><td></td></tr></table> 
    	</div>
   		
    
    <input type="hidden" id="id_suc" value="<?php echo $sucursal['suc_id']; ?>">

    
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
function reporte(){
	fini=$("#inputfechainicio").val() + ' 0:00:00';
	ffin=$("#inputfechafin").val() + ' 23:59:00';
	var vendedor = $("#inputvendedor").val();
	
			window.open( "ReportePDF.php?fini="+fini+"&ffin="+ffin, "Compras del Dia" , "width=800 , height = 600");
		}

var u = 0;
//grilla();

var dates = $("#inputfechainicio, #inputfechafin").datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: "+1d",
            maxDate: "+D",
            changeMonth: true,
            numberOfMonths: 1,
            changeYear: true,
            onSelect: function (selectedDate) {
                var option = this.id == "inputfechainicio" ? "minDate" : "maxDate",
					instance = $(this).data("datepicker"),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings);
                dates.not(this).datepicker("option", option, date);
            }
        });

		$( "#inputvendedor" ).autocomplete({
source: $("#url_autocomplete").val() ,//availableTags,
select: function( event, ui ) {

$("#inputvendedor").val(ui.item.label);
},
focus: function( event, ui ) {

}
   });

        /*function grilla() {
			var url = $("#Url").val();
			var fi = $("#inputfechainicio").val() + ' 0:00:00';
			var ff = $("#inputfechafin").val() + ' 23:59:00';
			$("#list").jqGrid('setGridParam', { postData: { sucursal: $("#id_suc").val(), fechainicio: fi, fechafin: ff} });
			$("#list").trigger("reloadGrid");
            jQuery('#list').jqGrid({
                url:url,
                datatype: 'json',
                mtype: 'POST',
                width: 900,
                async: false,
                colNames: ['IdCabVen', 'Fecha', 'Vendedor', 'Total' ],
                colModel: [
				{ name: 'idcabven', index: 'idcabven', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
                        { name: 'fecha', index: 'fecha', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'vendedor', index: 'vendedor', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'total', index: 'total', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                    ],
						onSelectRow: function (id) {
						var rowData = jQuery("#list").getRowData(id);
						detalle_grilla(rowData);
                	},
                	pager: '#pager',
                    rowNum: 50,
                	rowList: [50, 100, 200, 500],
                    sortname: 'idcabven',
                    sortorder: 'asc',
                    viewrecords: true,
					postData: { sucursal: $("#id_suc").val(), fechainicio: $("#inputfechainicio").val(), fechafin: $("#inputfechafin").val() },
                    caption: 'Lista Ventas'
                });
        	}
			
			function detalle_grilla(datos) {
			var url1 = $("#Url_detalle").val();
			var cod = datos.idcabven;
			$("#list2").jqGrid('setGridParam', { postData: { codigo: cod} });
			$("#list2").trigger("reloadGrid");
            jQuery('#list2').jqGrid({
                url:url1,
                datatype: 'json',
                mtype: 'POST',
                width: 900,
                async: false,
                colNames: ['IdDetVen', 'Producto', 'Precio', 'Cantidad', 'Total' ],
                colModel: [
				{ name: 'iddetven', index: 'iddetven', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
                        { name: 'producto', index: 'producto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'precio', index: 'precio', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'cantidad', index: 'cantidad', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'total', index: 'total', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                    ],
                	pager: '#pager2',
                    rowNum: 50,
                	rowList: [50, 100, 200, 500],
                    sortname: 'iddetven',
                    sortorder: 'asc',
                    viewrecords: true,
					postData: { postData: { codigo: cod} },
                    caption: 'Detalle Ventas'
                });
        	}*/
   
</script>