    <?php 
	include('../../../connections-db/conexion-mysql.php');
	$sqltabp = sprintf("SELECT * FROM tipos WHERE tip_eliminado ='N' and tip_tabla = 'ajuste'");
	$query = mysql_query($sqltabp, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	?>
    <script>
    function Eliminar(id){
		alert("Está por eliminar el registro: "+id);
		
			parametros="id="+id+"&accion=Eliminar";
			$.ajax({
			type: "POST",
			url: "funciones/funciones.php",
			data: parametros,
			success : function (resultado){ 			 
			Tabla();
			}
   			 });
			 Tabla();
		msg();
		logsys('ELIMINA REGISTRO TIPO DE AJUSTE '+id )
		}
		function logsys(REFLOG){
          $.ajax({type: "POST",url: "../../log/sys-log.php",data:'REFLOG='+REFLOG, success : function (resultado){ }});  
        }
    </script>
    <div align="center">
  <strong style="text-align:center; font-size:24px;color:#006">Ajustes Registrados</strong>
  <hr>
<table  border="0"cellspacing="2" width="500px" class="table table-info table-striped">
    <?php if($tot_rows > 0)	{ ?>
				<thead>
					<tr>
                    	<th width="100px"></th>
                        <th>Nombre</th>
                  </tr>
				</thead>
				<tbody>
					<?php do { ?>
					<tr>
                    	<td>
                        <a class="btn btn-danger btn-mini" onClick="Eliminar(<?php  echo $row['tip_id'];?>)"><i class="icon-trash"></i> Eliminar</a>
                        </td>
                        <td><strong><?php echo $row['tip_des']; ?></strong></td>
                     </tr>
					<?php } while ($row = mysql_fetch_assoc($query)); ?>
				</tbody>
			</table>
            </div>        			
			<?php mysql_free_result($query);
			}else{ echo '<div class="alert alert-error"><h4>NO CUENTA CON TIPOS DE AJUSTES PERMITIDOS.</h4></div>'; } ?>
		</div>     	     