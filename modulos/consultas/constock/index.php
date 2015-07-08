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
	<title>Reporte Stock Productos</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>    
    
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php');
	?>
    <br/>
    <div class="container" align="center">
		<div class="control-group well span11" align="center">
	
			<div class="page-header" align="center"> <h4>REPORTE STOCK</h4>
    		
    			<div class="row-fluid" align="center">
                	<div class="span6">
						<div class="control-group">
							<label class="control-label">Producto</label>
								<div class="controls">
									<input type="text" class="input-block-level span5" id="inputproducto" name="inputproducto" placeholder="Codigo Producto" required>
								</div>
						</div>
    				</div>  
                    
                    <div class="span6">
						<div class="control-group">
                        <br>
						<label class="control-label"></label>
								<div class="controls">
                <input type="button" class="btn btn-primary" value="IMPRIMIR REPORTE" onClick="reporte()">
								</div	>
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
    <input type="hidden" id="Url" value="<?php echo $RUTAm."consultas/constock/funciones/funciones.php"; ?>">
    <input type="hidden" id="id_suc" value="<?php echo $sucursal['suc_id']; ?>">
    <input type="hidden" id="url_autocomplete" value="<?php echo $RUTAm."consultas/constock/funciones/autocomplete_stock.php"; ?>">
</body>
</html>
<script type="text/javascript">
function reporte(){
			window.open( "ReporteSPDF.php", "Reporte en Stock" , "width=800 , height = 600");
		}

var u = 0;
grilla();

        function grilla() {
			var url = $("#Url").val();
			$("#list").jqGrid('setGridParam', { postData: { sucursal: $("#id_suc").val(), producto: $("#inputproducto").val()} });
			$("#list").trigger("reloadGrid");
            jQuery('#list').jqGrid({
                url:url,
                datatype: 'json',
                mtype: 'POST',
                width: 900,
                async: false,
                colNames: ['IdPro', 'Producto', 'Stock Actual', 'PVP', 'Costo Unitario' ],
                colModel: [
				{ name: 'idpro', index: 'idpro', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false, hidden: true },
                        { name: 'producto', index: 'producto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'cantidad', index: 'cantidad', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'pvp', index: 'pvp', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'costo', index: 'costo', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                    ],
                	pager: '#pager',
                    rowNum: 50,
                	rowList: [50, 100, 200, 500],
                    sortname: 'idpro',
                    sortorder: 'asc',
                    viewrecords: true,
					postData: { sucursal: $("#id_suc").val(), producto: $("#inputproducto").val() },
                    caption: 'Lista Productos'
                });
        	}
			
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
   $('#inputproducto').bind('keypress', function(e) {
if(e.which == 13) {
       	 grilla();
   	 }
});
   
</script>
	<?php }else
		{
			$_SESSION['MSG'] = 'Acceso no Autorizado';
			$_SESSION['MSGdes'] = 'PERMISOS INSUFICIENTES';
			$_SESSION['MSGimg'] = $RUTAi.'noautorizado.png';
			header("Location: ".$RUTAm);
			}?>
