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
	<title>Reporte Productos por Agotarse</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>    
    
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php');
	?>
    <meta charset="utf-8"></meta>
    <br/>
    <div class="row-fluid">
		<div class="span1">
        </div>
		<div class="span10">
        	<div class="control-group well" align="center">
            <h3>PRODUCTOS POR AGOTARSE</h3>
            <hr>
            <br>
            
                <table id="list"><tr><td></td></tr></table> 
                <div id="pager"></div> 
             <br>
             <br>
    		</div>
            <div class="control-group well" align="center">
                <div class="span6" align="center">
                <strong style="font-size:18px">Generar Reporte para Imprimir</strong>
                </div>
                <div class="span6" align="center">
                <input type="button" class="btn btn-primary" value="IMPRIMIR REPORTE" onClick="reporte()">
                </div>
                <br>
            </div>
        </div>
        <div class="class="span1""> 
        </div>
	</div>
    <input type="hidden" id="Url" value="<?php echo $RUTAm."consultas/constock_minimo/funciones/funciones.php"; ?>">
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
			window.open( "ReportePDF.php", "Reporte de Stock por Agotarse" , "width=800 , height = 600");
		}
var u = 0;
grilla();

        function grilla() {
			var url = $("#Url").val();
            jQuery('#list').jqGrid({
                url:url,
                datatype: 'json',
                mtype: 'POST',
                width: 900,
                async: false,
                colNames: ['IdPro', 'Producto', 'Cantidad', 'Costo' ],
                colModel: [
				{ name: 'idpro', index: 'idpro', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'producto', index: 'producto', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                        { name: 'cantidad', index: 'cantidad', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
						{ name: 'costo', index: 'costo', width: 90, sorttype: 'string', align: "center", frozen: true, sortable: false },
                    ],
                	pager: '#pager',
                    rowNum: 50,
                rowList: [50, 100, 200, 500],
                    sortname: 'idpro',
                    sortorder: 'asc',
                    viewrecords: true,
                    caption: 'Lista Productos Próximos A Agotarse'
                });
        	}
</script>