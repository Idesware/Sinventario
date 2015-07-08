<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();

	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	$varpostpro=$_POST['pro'];
	$pro_id=$_POST['productos'];
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
    <title>Gestión Ajustes Stock</title>
 	<?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <script type="text/javascript">
        function Ventas(){
            Tabla();
        }
		function Tabla(){
            $("#contFormatT").load("tabla.php");
        }
		$('#productos').chosen({
			autoFocus: true
		});
$(document).on('ready', function(){		
		$("#productos").autocomplete({
				source: 'funciones/bus-astock.php',
				minLength: 1,
				autoFocus: true
				
			});
		$("#productos").chosen({});	
		
		$("#tipo_ajuste").autocomplete({
				source: 'funciones/bus-tipoajus.php',
				minLength: 1,
				autoFocus: true
			});
		$("#tipo_ajuste").chosen({});
		
})
    </script>
    
<HEAD>
 <meta name="tipo_contenido"  content="text/html;" http-equiv="content-type" charset="utf-8">
</HEAD>



    <div class="container">
        <div class="page-header">
          
        </div>
        <div class="row-fluid">
            <div class="span8">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon-home"></i> Responsable del Ajuste <?php echo $persona['per_nombre']; ?></li>
                </ul>
            </div>
            <div class="span4">
                <a class="btn btn-primary btn-large btn-block" href="<?php echo $RUTAm."administrador/stock/";?>"><strong>Agregar Producto en Stock</strong></a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row-fluid">
                <div class="alert alert-danger">
                <h4><i class="icon-list"></i> AJUSTE DEL STOCK - <?php
                    $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado");
                    $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
                    echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
                    ?>
                    <span class="badge"><?php echo $tot_rows; ?></span></h4>

                </div>

<div class="row-fluid">
    <div class="span4">
    </div>
    <div class="span4">

    <div class="control-group" >
	     <div class="controls" id="chosen_cat" style="height:20px; width:250px ">
			<br>
               <form method="post" name="fbuscar" id="fbuscar" action="#">
			   <?php 
					
					fnc_listajusStock($idSel,'input-block-level', 'productos', 'autofocus required');
				?>
                <br>
                <br>
                <div align="right">
               	
                <button class="btn btn-primary" type="submit" style="width:120px" > <i class="icon-search"> </i>   <strong>BUSCAR</strong></button>
                </form>
                <div>
	     </div> 
	</div>
	</div>
</div>
    </div>
         <?php /* Consulta uno */
		 				$sql = sprintf("SELECT * FROM stock inner join detalle_producto on stock.det_pro_id = detalle_producto.det_pro_id inner join producto on producto.pro_id = detalle_producto.pro_id where suc_id='".$sucursal['suc_id']."' and producto.pro_id='".$pro_id."'");
							$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
							$row = mysql_fetch_assoc($query);
							$tot_rows = mysql_num_rows($query);
						
						?>
        	<div class="row-fluid">
				<div class="alert alert-info">
					<h5 align="center"><i class="icon-list"></i> Productos Registrados<span class="badge"></h5>
				</div>
			</div>
            <?php if($tot_rows > 0)	{ ?>
            <br>
            <div class="row-fluid">
			<br>
            <div class="alert">
            <table class="table table-bordered table-condensed table-striped" id="tab_usr" style="color:#000" align="center">
				<thead>
					<tr>
						<th width="125px"></th>
						<th>Id</th>
						<th>Nombre</th>
                        <th>Cantidad Actual</th>
                        <th>Stock Minimo</th>
					</tr>
				</thead>
				<tbody >
                <?php
				do{
				?>
					<tr>
						<td>
                            <div class="btn-group">
                        		<a href="<?php echo $RUTAm; ?>administrador/ajustes-stock/ajuste.php?pro_id=<?php echo $row['pro_id']; ?>&accion=Actualizar" class="btn btn-primary btn-mini" style="font-size:13px;"><i class="icon-edit"></i> <strong>Realizar Ajuste</strong></a>
                        	</div>
						</td>
						<td style="text-align:center"><?php echo $row['pro_id']; ?></td>
						<td><?php echo $row['pro_nombre']; ?></td>
                        <td ><?php echo $row['stk_cantidad']; ?></td>
                        <td><?php echo $row['stk_minimo']; ?></td>
                        </tr>
					<?php 
					} while ($row = mysql_fetch_assoc($query)); 
					?>
				</tbody>
			</table>
            </div>
            
            <strong class="btn btn-success">Campo permitido para ajustes es de CANTIDAD.</strong>
            </div>
            <br>
                 			
			<?php mysql_free_result($query);
			}else{ echo '<br><div class="alert "><h4>Buscar registro de stock</h4></div>'; } ?>
	</div>
    </div>
  
</body>
<?php fnc_msgGritter();?>
</html>