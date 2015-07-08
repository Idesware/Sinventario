<script>
$(document).on('ready', function(){
	Tabla();
			$("#tipo").autocomplete({
				source: 'funciones/bus-tipo.php',
				minLength: 1,
				autoFocus: true
			});
			$("#tipo").chosen({});
		});
		function msg(){
            $("#gritter").load("msg.php");
        }
		function logsys(REFLOG){
          $.ajax({type: "POST",url: "<?php echo RUTAm.'log/sys-log.php';?>",data:'REFLOG='+REFLOG, success : function (resultado){ }});  
		}
		function Cargar(){
			Tabla();
			razon=$("#razon").val();
			parametros="razon="+razon+"&accion=Insertar";
			$.ajax({
			type: "POST",
			url: "funciones/funciones.php",
			data: parametros,
			success : function (resultado){ 			 
			Tabla();
			razon=$("#razon").val("");
			Tabla();
			msg();
			logsys('INSERTA REGISTRO TIPO DE AJUSTE');
			}
   			 });

		}
				
 </script> 
     
<div class="row-fluid">
    <div class="span3">
    <div class="control-group">
	     <div class="controls">
	     </div>  
	</div>
    </div>
    <div class="span6" align="center">
    <strong style="font-size:18px"> Tipo de Ajuste </strong>
    <input type="text" id="razon" placeholder="Tipo de Ajuste" style="width:250px">
    <br>
      <div align="center">
    	<a class="btn btn-warning" onClick="Cargar()"> Registrar</a> 
        <a class="btn btn-success" href="<?php echo $RUTAm.'administrador/tipo-ajuste/';?>"> Cancelar</a> 
    <br>    
    </div>
    <br> 
    </div>
    <div class="span3">
    </div>
    <br>
    <div  id="gritter"></div>
</div>



