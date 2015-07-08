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
    <title>Stock</title>
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
    </script>
    
    <div class="container">
        <div class="page-header">
            <h3>STOCK</h3>
        </div>
        <div class="row-fluid">
            <div class="span8">
                <ul class="breadcrumb">
                    <li class="active"><i class="icon-home"></i> Consultar Stock de <?php echo $sucursal['suc_nombre']; ?></li>
                </ul>
            </div>
            <div class="span4">
                <a class="btn btn-primary btn-large btn-block" onClick="Ventas()"><strong> CONSULTAR STOCK</strong></a>
            </div>
        </div>
        <div class="portlet-body">
            <div class="row-fluid">
                <div class="alert alert-danger">
                    <h4><i class="icon-list"></i> TOTAL DE PRODUCTOS EN STOCK<span class="badge"><?php echo $tot_rows; ?></span></h4>
                </div>
                <div>
                <?php include ('consulta.php');?>
                </div>
                <div id="contFormatT"></div>
            </div>
        </div>
    </div>

</body>

</html>