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

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8"></meta>
    <title>Gestión Ajustes</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <script type="text/javascript">
		$(document).on('ready', function(){
		Tabla();
		});
        function Ventas(){ 
		$("#contFormatoF").load("form.php");
            Tabla();
        }
		function Tabla(){
            $("#contFormatT").load("tabla.php");
        }
    </script>
    <div class="container">
        <div class="page-header">
          
        </div>
        <div class="row-fluid">
            <div class="span12">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon-home"></i> Tipos de Ajustes</li>
                </ul>
            </div>
            
        </div>
        <div class="portlet-body">
            <div class="row-fluid">
                <div class="alert alert-danger">
                    <h4><i class="icon-list"></i> TIPOS DE AJUSTE <span class="badge"><?php echo $tot_rows; ?></span></h4>

                </div>

                <div id="contFormatoF">
                <?php include ("form.php");?>
                </div>
                <div id="contFormatT"></div>
            </div>
        </div>
    </div>

</body>

</html>