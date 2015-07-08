<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	$pro_id=$_GET['pro_id'];
	$StockReg=fnc_datStock($pro_id)
	
?>

<!doctype html>
<html>
<head>
 <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <?php /* Consulta uno */
		$sql = sprintf("SELECT * FROM stock inner join detalle_producto on stock.det_pro_id = detalle_producto.det_pro_id inner join producto on producto.pro_id = detalle_producto.pro_id where suc_id='".$sucursal['suc_id']."' and producto.pro_id='".$pro_id."'");
		$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		$row = mysql_fetch_assoc($query);
		$tot_rows = mysql_num_rows($query);
						
						?>
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

function solonumeros(e)
{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	 
	return /\d/.test(String.fromCharCode(keynum));
}

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
                    <li class="active"><i class="icon-home"></i> Responsable del Ajuste <?php echo $row['pro_nombre']; ?></li>
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

<div class="row-fluid"><strong>Producto a Ajustar: <?php echo $row['pro_nombre']; ?>  </strong>


                <form method="post" name="fbuscar" id="fbuscar" action="<?php echo$RUTAm.'administrador/ajustes-stock/funciones/funciones.php';?>">
               
                <br>
                <div class="span4">
                <div class="control-group well"><!--Start-->
                <div class="control-group">
						<label class="control-label"><strong>CANTIDAD DE AJUSTE</strong></label>
						<div class="controls">
							<div class="row-fluid">
								<input type="number" class="input-block-level" id="cantidad" name="cantidad" placeholder="Cantidad de Ajuste" required onkeypress="return solonumeros(event);">
                                <div class="control-group">
                               	  <p required >
                                	  <label>
                                	    <input type="radio" name="R1" value="+" id="R1">
                                	    Mas (+)</label>
                                	    <label>
                                	    <input type="radio" name="R1" value="-" id="R1">
                                	    Menos (-)</label>
                                	  
                              	  </p>
                                 <strong> <?php 
										echo 'Selecionar referencia de Ajuste';
										fnc_listtip_ajuste($id,'input-block-level', 'tipo_ajuste', 'autofocus required'); ?></strong>
                                        
                                        <input type="hidden" id="pro_id" name="pro_id" value="<?php echo $pro_id;?>">
                                </div>
							</div>
						</div>
					</div>
					</div><!--end-->
                    </div>
					<div class="span7">
                            <div class="control-group well"><!--Start-->
                <div class="control-group">
						<label class="control-label"><strong>Respaldo de Ajuste</strong></label>
						<div class="controls">
							<div class="row-fluid">
								<textarea class="input-block-level" id="aju_motivo" name="aju_motivo" placeholder="Motivo del Ajuste" onKeyUp="" required> </textarea>
                            
							</div>
						</div>
					</div>
					</div><!--end-->
                    </div>

	     </div> 
	</div>
	</div>
</div>
    </div>
    <div class="alert alert-info">
        	<div class="row-fluid" align="center">
				
                	<div class="span6">
                	   <button class="btn btn-primary" type="submit" style="width:200px" > <i class="icon-search"> </i>   <strong>Ajustar Cantidad</strong></button>
                      </div> 
                      <div class="span6" >
					<h5 align="center"><i class="icon-list"></i>Cantidad Actual:  <?php echo $row['stk_cantidad']; ?></h5>
                    </div>
			</div>
            <?php if($tot_rows > 0)	{ ?>
            <br>
            <div class="row-fluid" align="center">
            <strong class="btn btn-success">Usuario responsable del Ajuste: <?php echo $persona['per_nombre']?></strong>
            </div>
            <br>
                 			
			<?php mysql_free_result($query);
			}else{ echo '<br><div class="alert "><h4>No se puede realizar un ajuste que no Existe</h4></div>'; } ?>
	</div>
    </div>

                </form>
                
                
</body>

</html>