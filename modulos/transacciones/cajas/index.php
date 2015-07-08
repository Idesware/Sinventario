<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	date_default_timezone_set('America/Guayaquil');
	$fecha_actual=date('Y-m-d H:i:s');
	$id_user = $_SESSION['id_usuario'];
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
	if((basename($url_autorizado['men_link'],"/"))==$URL_Visita_Ult){
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_usuario($id_user);
	$sucursal = fnc_datSuc($empleado['suc_id']);
	
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Caja</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>    
    
</head>
<body>
     
	<?php include(RUTAcom.'menu-principal.php');
	?>   
    <div class="container">
		<div class="page-header"><h3> CAJA</h3></div>
        <div class="row-fluid">	
        <div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href=""> Administración de Cajas</a>
                        <span class="divider"></span>
                    </li>
                    <li class="active"><input type="button" class="btn btn-success" name="abrir_caja" id="abrir_caja" value="ABRIR CAJA" onclick="AbrirCaja()"></li>
                    <li class="active"><input type="button" class="btn btn-danger" name="cerrar_caja" id="cerrar_caja" value="CERRAR CAJA" onclick="CerrarCaja()"></li>
                </ul>
			</div>
        </div>
        <div class="row-fluid span6" id="abierta"><h2>CAJA ABIERTA</h2></div>
        <div class="row-fluid span6" id="cerrada"><h2>CAJA CERRADA</h2></div>
        
		<div class="row-fluid">	
			<div class="control-group well span6">
				<form class="form-horizontal">
                <div class="control-group">
						<label class="control-label">Fecha</label>
						<div class="controls">
								<input type="text" class="input-block-level" id="inputfecha" name="inputfecha" value="<?php echo $fecha_actual; ?>"  disabled>
						</div>
					</div>
                	<div class="control-group">
						<label class="control-label">Caja</label>
						<div class="controls" id="chosen_cat" style="height:20px; width:250px ">
					   <?php 
                            $idSel='1';
                            fnc_listCaja($idSel,'caj_id','caj_nombre', 'caja', 'input-block-level', 'caja', 'autofocus required',$sucursal['suc_id']);
                        ?>
	     			</div>  
					</div>
					<div class="control-group">
						<label class="control-label">Dinero Caja Actual</label>
						<div class="controls">
								<input type="text" class="input-block-level" id="inputdinerocaja" name="inputdinerocaja" disabled>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Valor</label>
						<div class="controls">
							<input type="text" class="input-block-level" id="inputcantidad" name="inputcantidad"   placeholder="Dinero Deposito o Retiro" required onkeypress="return solonumeros(event);">
						</div>
					</div>
                    <input type="button" class="btn btn-primary" name="deposito_dinero" id="deposito_dinero" value="INGRESO" onclick="Deposito()">
                    <input type="button" class="btn btn-primary" name="retirar_dinero" id="retirar_dinero" value="EGRESO" onclick="Retiro()">
				</form>
			</div>
		</div>
    </div>
    <input type="hidden" id="Url" value="<?php echo $RUTAm."transacciones/cajas/funciones/funciones.php"; ?>">
    <input type="hidden" id="id_suc" value="<?php echo $sucursal['suc_id']; ?>">
    <input type="hidden" id="usuario" value="<?php echo $persona['per_nombre']; ?>">
	<br>
    <div class="span12">
	<table id="list"><tr><td></td></tr></table> 
    <div id="pager"></div> 
	</div>
</body>
<footer>	
</footer>
</html>

<script>

$('#caja').chosen({
			autoFocus: true
		});
$(document).on('ready', function(){
			$("#caja").autocomplete({
				source: 'funciones/bus-caja.php',
				minLength: 1,
				autoFocus: true
			});
			$("#caja").chosen({});
			verificarcaja();
			cargar_caja();
		});
		
	$('#caja').change(function () {
        cargar_caja();
    });
	
	function cargar_caja(){
		var url = $("#Url").val();
		var accion = 'VALOR_CAJA';
		var caja = $("#caja").val();
		$.ajax({
		url: url,
		type: "POST",
		async: false,
		data: {
			id_caja: caja,
			accion: accion
		},
		success:  function(resultado) {
			$("#inputdinerocaja").val(resultado);
		},
		});
	}
	
	function Deposito() {
        var url = $("#Url").val();
		var accion = 'DEPOSITO';
		var caja = $("#caja").val();
		var ingreso = $("#inputcantidad").val();
		var valor_actual = $("#inputdinerocaja").val();
		if(ingreso == "")
		{
			vex.dialog.alert ('Ingrese un Valor!');
		}
		else
		{
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				id_caja: caja,
				accion: accion,
				ingreso: ingreso,
				sucursal: $("#id_suc").val(),
				usuario: $("#usuario").val()
			},
			success:  function(resultado) {
				$("#inputdinerocaja").val(resultado);
				$("#inputcantidad").val('');

			},
			});
		}
        }
		
		function Retiro() {
        var url = $("#Url").val();
		var accion = 'EGRESO';
		var caja = $("#caja").val();
		var egreso = $("#inputcantidad").val();
		var valor_actual = $("#inputdinerocaja").val();
		if(egreso == "")
		{
			vex.dialog.alert ('Ingrese un Valor!');
		}
		else
		{
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				id_caja: caja,
				accion: accion,
				egreso: egreso,
				sucursal: $("#id_suc").val(),
				usuario: $("#usuario").val()
			},
			success:  function(resultado) {
				if(resultado == "False")
				{
					vex.dialog.alert ('No Hay Suficiente Dinero En Caja!');
				}
				else
				{
					$("#inputdinerocaja").val(resultado);
					$("#inputcantidad").val('');
				}
			},
			});
		}
        }
		
		function AbrirCaja() {
        var url = $("#Url").val();
		var accion = 'ABRIR_CAJA';
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				accion: accion,
				sucursal: $("#id_suc").val()
			},
			success:  function(resultado) {
				if(resultado == "True")
				{
					location.reload();
				}
			},
			});
        }
		
		function CerrarCaja() {
        var url = $("#Url").val();
		var accion = 'CERRAR_CAJA';
			$.ajax({
			url: url,
			type: "POST",
			async: false,
			data: {
				accion: accion,
				sucursal: $("#id_suc").val()
			},
			success:  function(resultado) {
				if(resultado == "True")
				{
					location.reload();
				}
			},
			});
        }
		
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
				if(resultado == "True")
				{
					$("#abierta").show();
					$("#cerrada").hide();
					$("#abrir_caja").hide();
					$("#cerrar_caja").show();
				}
				else
				{
					$("#abierta").hide();
					$("#cerrada").show();
					$("#deposito_dinero").show();
					$("#cerrar_caja").hide();
				}
			},
			});
		}
		
	function solonumeros(e)
	{
	var keynum = window.event ? window.event.keyCode : e.which;
	if ((keynum == 8) || (keynum == 46))
	return true;
	 
	return /\d/.test(String.fromCharCode(keynum));
	}
				
 </script> 
 <?php }else
		{
			$_SESSION['MSG'] = 'Acceso no Autorizado';
			$_SESSION['MSGdes'] = 'PERMISOS INSUFICIENTES';
			$_SESSION['MSGimg'] = $RUTAi.'noautorizado.png';
			header("Location: ".$RUTAm);
			}?>