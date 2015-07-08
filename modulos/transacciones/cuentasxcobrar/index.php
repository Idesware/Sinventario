<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();

	$id_user = $_SESSION['id_usuario'];
	$URL_Visita_Ult=basename($_SERVER['REQUEST_URI'], "/");
	$url_autorizado=fnc_datURLv($URL_Visita_Ult, $id_user);
	if((basename($url_autorizado['men_link'],"/"))==$URL_Visita_Ult){
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
	<meta charset="utf-8">
  	<title>Cuentas por Cobrar</title>
	<?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
<div><!--Inicio de Tabla de Consulta -->
<?php
	$sqltabp = sprintf("SELECT * FROM pagos inner join cabecera_ventas on pagos.cab_ven_id=cabecera_ventas.cab_ven_id inner join cliente on cliente.cli_id = cabecera_ventas.cli_id inner join persona on persona.per_id=cliente.per_id
 where pagos.pag_sal >=0.01 and pagos.pag_estado='N'");
	$query = mysql_query($sqltabp, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	
	?>
    <div style="text-align:center">
    <br>
  <strong style="text-align:center; font-size:24px;color:#006">Cuentas por Cobrar </strong>
  <hr>
<table  border="0"cellspacing="2" width="500px" class="table table-info table-striped">
    <?php if($tot_rows > 0)	{ ?>
				<thead>
					<tr>
						<th style="text-align:center"></th>
                        <th style="text-align:center">Vendedor</th>
                        <th style="text-align:center">Fecha de Cr&eacute;dito</th>
                        <th style="text-align:center">cliente</th>
                        <th style="text-align:center"># Referencia</th>
                        <th style="text-align:center">Valor</th>
                        <th style="text-align:center">valor Pagado</th>
                        <th style="text-align:center">valor Pendiente</th>
                  </tr>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
						<td style="text-align:center"><a type="button" class="btn btn-info" onClick="abonar( <?php echo $row['cab_ven_ref']; ?>, <?php echo $row['pag_tot']; ?>, <?php echo $row['pag_sal']; ?>, <?php echo $row['pag_id']; ?>, <?php echo $row['cab_ven_id']; ?>)" ><img src="../../../images/sistema/pago.png" width="24" height="24"  alt=""/> Pagar</a></td>
                        <td style="text-align:center"><?php echo $row['cab_ven_usu']; ?></td>
                        <td style="text-align:center"><?php echo $row['cab_ven_fecha']; ?></td>
                        <td style="text-align:center"><?php echo $row['per_nombre']; ?></td>
                        <td style="text-align:center"><?php echo $row['cab_ven_ref']; ?></td>
                        <td style="text-align:center"><?php echo '$ '.$row['pag_tot']; ?></td>
                        <td style="text-align:center"><?php echo '$ '.$row['pag_abo']; ?></td>
						<td style="text-align:center"><?php echo '$ '.$row['pag_sal']; ?></td>
                       	</tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
            </div>        			
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>NO TIENE CUENTAS POR COBRAR</h4></div>'; } ?>
		</div>     	     
</div> <!--Fin de Tabla de Consulta -->
<!-- Modal -->
        <div align="center" id="modalpagcredito"   class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalpagcredito" aria-hidden="true">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
                <h4 id="modalpagcredito"><strong>Abono del Cr�dito </strong></h4>
                <div align="center">
            </div>
          </div>
          <div class="modal-body">
            <?php include("abono.php"); ?>
          </div>
          <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancelar</button>
          </div>
        </div>
           <!-- Modal -->
          

</body>

</html>
<script type="text/javascript">
function abonar(ref,tot,sal,idpag, cab_ven_id) {
			$("#numpag_id").val(idpag);
			document.getElementById('refcuenxpag').innerHTML = ref;
			document.getElementById('totcuenxpag').innerHTML = tot;
			document.getElementById('saldocuenxpag').innerHTML = sal;
			$("#saldo").val(sal);
			$("#cab_ven_id").val(cab_ven_id);
			$("#abono").attr('MAX',sal);
			//document.getElementById('permoncuenxpag').innerHTML = nom;
		$('#modalpagcredito').modal();
        }
</script>
<?php }else
		{
			$_SESSION['MSG'] = 'Acceso no Autorizado';
			$_SESSION['MSGdes'] = 'PERMISOS INSUFICIENTES';
			$_SESSION['MSGimg'] = $RUTAi.'noautorizado.png';
			header("Location: ".$RUTAm);
			}?>