<?php 
	if (!isset($_SESSION)) session_start();
	include('../start.php');
	fnc_autentificacion();
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	function date_ame2euro($date=NULL){ if(!$date) $datef=date('d-m-Y');
 	else $datef=date("d-m-Y",strtotime($date)); 
 	return $datef;
  }
?>

<!doctype html>
<html>
<head>
  <title>Menu</title>
  <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
  <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); 
	
		$suc_id=$sucursal['suc_id'];
		$sqlcaja=sprintf("SELECT * FROM caja WHERE suc_id = %s AND caj_eliminado = 'N'",
    	GetSQLValueString($suc_id, "text"));
  		$query = mysql_query($sqlcaja, $conexion_mysql) or die(mysql_error());
		$row = mysql_fetch_assoc($query);
		$tot_rows = mysql_num_rows($query); 		
		$estado = $row['caj_estado'];
		
		if($estado == "A")
		{
			$texto = "<span class='label label-warning' style='padding-top:20px;padding-bottom:15px;padding-left:40px;padding-right:40px'><label style='text-align:center; font-stretch:expanded; font-weight:bold; font-size:28px;'>CAJA ABIERTA</label></span>";
		}
		else
		{
			$texto = "<span class='label label-important' style='padding-top:20px;padding-bottom:15px;padding-left:40px;padding-right:40px'><label style='text-align:center; font-stretch:expanded; font-weight:bold; font-size:28px;'>CAJA CERRADA</label></span>";
		}
	fnc_msgGritter();
	?>
<div class="container">
<div class="control-group well span11">
<div class="control-group well span5">
<div class="page-header"> <h4  class="text-info">Datos del Empleado Responsable</h4>

</div>
<p> Nombre:  <strong><?php echo $persona['per_nombre'];?></strong></p>
<p> Dirección:  <strong><?php echo $persona['per_direccion1'];?></strong></p>
<p> Teléfono:  <strong><?php echo $persona['per_telefono'];?></strong></p>
</div>

<div class="control-group well span4">
<div class="page-header"> <h4 class="text-info">Estado  de la Caja</h4>
<div class="row-fluid span6" id="abierta">
<br>
<?php echo $texto;?>

</div>

</div>	
    </div>
      </div>
       <div class="container">
        <div class="control-group well span11">
            <div class="page-header"> <h3>Productos por Agotarse</h3>
                <div class="span6">
                    <table id="list"></table> 
                      <input type="hidden" id="Url" value="<?php echo $RUTAm."consultas/constock_minimo/funciones/funciones.php"; ?>">
                      <input type="hidden" id="Url2" value="<?php echo $RUTAm."transacciones/ventas/funciones/funciones.php"; ?>">
                      <input type="hidden" id="id_suc" value="<strong><?php echo $sucursal['suc_id']; ?></strong>">
                </div>
            </div>
        </div>	
       </div>
   <br/>
</body>
</html>
<script type="text/javascript">
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
		
		
$( document ).ready(function() {
   //verificarcaja();
});		

</script>