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
	<title>Anular Factura</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>    
    
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php');
	?>
    <br/>
    <div class="container" align="center">
		<div class="control-group well span11" align="center">
	
			<div class="page-header" align="center"> <h4>ANULAR FACTURA</h4>
    		
    			<div class="row-fluid" align="center">
                	<div class="span6">
						<div class="control-group">
							<label class="control-label">Ingrese el Numero de Factura</label>
								<div class="controls">
									<input type="text" class="input-block-level span5" id="input_num_fact" name="input_num_fact" placeholder="Num. Factura" required>
								</div>
						</div>
    				</div>  
                    
                    <div class="span6">
						<div class="control-group">
                        <br>
						<label class="control-label"></label>
								<div class="controls">
                <input type="button" class="btn btn-primary" value="ANULAR FACTURA" onClick="anular_factura()">                               
								</div>
						</div>
    				</div>  
                   
                </div>
            </div>
             
       </div> 
     
    </div>
    	<div class="row-fluid">
        	<div class="span1"></div>
            <div class="control-group well span10" align="center">
            <br>
                   <div class="container" align="center">
                                <div class="row-fluid" align="center"	>
                                    <table id="list"><tr><td></td></tr></table> 
                                <div id="pager"></div> 
                            </div>
            </div>
            <div class="span1"></div>
          </div>
            
	</div>
    <input type="hidden" id="Url" value="<?php echo $RUTAm."transacciones/anular-factura/consulta_factura.php"; ?>">
    <input type="hidden" id="id_suc" value="<?php echo $sucursal['suc_id']; ?>">
    <input type="hidden" id="url_autocomplete" value="<?php echo $RUTAm."consultas/constock/funciones/autocomplete_stock.php"; ?>">
    <input type="hidden" id="url_impresion" value="<?php echo $RUTAm."impresion/factura.php"; ?>">
    <input type="hidden" id="input_id" name="input_id" value="">
   
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

var rowData;
var u = 0;
grilla();

        function grilla() {
			var url = $("#Url").val();
			$("#list").jqGrid('setGridParam', { postData: { ref: $("#input_num_fact").val()} });
			$("#list").trigger("reloadGrid");
            jQuery('#list').jqGrid({
                url:url,
                datatype: 'json',
                mtype: 'POST',
                width: 900,
                async: false,
                colNames: ['id','N. Factura', 'Cliente', 'Fecha', 'Total Fac.' ],
                colModel: [
				{ name: 'id', index: 'id', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
				{ name: 'referencia', index: 'referencia', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: false },
                        { name: 'cliente', index: 'cliente', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'fecha', index: 'fecha', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'total', index: 'total', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                    ],
					onSelectRow: function (id) {
						rowData = jQuery("#list").getRowData(id);
						$("#input_num_fact").val(rowData['referencia']);
						$("#input_id").val(rowData['id']);
                	},
                	pager: '#pager',
                    rowNum: 50,
                	rowList: [50, 100, 200, 500],
                    sortname: 'idpro',
                    sortorder: 'asc',
                    viewrecords: true,
					postData: { ref: $("#input_num_fact").val() },
                    caption: 'Lista Facturas'
                });
        	}
			
	$( "#inputproducto" ).autocomplete({
		source: $("#url_autocomplete").val() ,//availableTags,
		select: function( event, ui ) { 
		$("#inputproducto").val(ui.item.code);
		$("#inputdetalle").val(ui.item.label);
		},
		focus: function( event, ui ) {
		}
   }); 

   $('#inputproducto').bind('keypress', function(e) {
if(e.which == 13) {
       	 grilla();
   	 }
});
   
function anular_factura()
{	
	$.ajax
      ({
        url: funciones.php,
        type: "POST",
        async: false,
        data: 
        {  
          num_fac: $("#input_num_fact").val(),
		  id: $("#input_id").val()
        },
        success:  function(resultado) 
        {
			
        }
      })
}

</script>

