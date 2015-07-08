<!-- llamando a los estilos y librerias de jquery -->
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/jquery.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/jquery-ui-1.10.3.custom.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/chosen.jquery.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/jquery.gritter.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/fullcalendar.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/bootstrap-wysihtml5.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/jquery.jqGrid.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $RUTAp.'jquery/js/grid.locale-es.js'; ?>"></script>


<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/jquery-ui-1.10.3.custom.min.css'; ?>"></link>
<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/chosen.css'; ?>"></link>
<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/jquery.gritter.css'; ?>"></link>
<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/fullcalendar.css'; ?>"></link>
<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/css001.css'; ?>"></link>
<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/bootstrap-wysihtml5.css'; ?>"></link>
<link type="text/css" rel="stylesheet" href="<?php echo $RUTAp.'jquery/css/ui.jqgrid.css'; ?>"></link>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script type="text/javascript">
	$(document).on('ready', function(){
		/*//MUESTRA UN OBJETO DENTRO DE UN TIEMPO DETERMINADO
		$('#objeto').delay(100).toggle(1000); //Aparece despues de 0,1s y el efecto se completa en 1s.
		setTimeout("$('#msg').fadeOut(1000);", 5000); //Desaparece despues de 5s y el efecto se completa en 1s. */
		
		/*//AUTOCOMPLETE
		$("#emp").autocomplete({
			source: 'funciones/bus-emp.php',
			minLength: 1,
			autoFocus: true
		});*/
		$('#caja').chosen({
			autoFocus: true
		});
		$('#emp').chosen({
			autoFocus: true
		});
		$('#suc').chosen({
			autoFocus: true
		});

		
		//*****METODO QUE COMPRUEBA LA DISPONIBILIDAD DEL NOMBRE DE USUARIO*****
		$("#usu").keyup(function() {
			var userId = $("#user_id").val();
			var texto = $("#usu").val();
			if(texto.length > 0){
				var ajaxUser = $.post('../funciones/bus-usuario.php',{idUser:userId,usuario:texto},resultUser,'json');
				ajaxUser.error(resUserError);
			}else{
				$('#resultUser').html('');
			}
		});		
		//*****METODO QUE COMPRUEBA LA DISPONIBILIDAD DEL NOMBRE DE SUCURSAL*****
		$("#suc_nombre").keyup(function() {
			var sucId = $("#suc_id").val();
			var texto = $("#suc_nombre").val();
			if(texto.length > 0){
				var ajaxSuc = $.post('../funciones/bus-sucursal.php',{idSuc:sucId,sucursal:texto},resultSuc,'json');
				ajaxSuc.error(resSucError);
			}else{
				$('#resultSuc').html('');
			}
		});
	});	
	//*****MUESTRA UN MESAJE ACERCA DE LA DISPONIBILIDAD DEL USUARIO*****
	var userDisp;
	function resultUser(r){
		$('#resultUser').html(r.mensaje);
		userDisp = r.estado;
	}			
	function resUserError(){
		alert('NO SE PUDO VERIFICAR LA DISPONIBILIDAD DEL NOMBRE DE USUARIO.');
	}	
	//*****MUESTRA UN MESAJE ACERCA DE LA DISPONIBILIDAD DE LA SUCURSAL*****
	var sucDisp;
	function resultSuc(r){
		$('#resultSuc').html(r.mensaje);
		sucDisp = r.estado;
	}			
	function resSucError(){
		alert('NO SE PUDO VERIFICAR LA DISPONIBILIDAD DEL NOMBRE DE LA SUCURSAL.');
	}	
	//*****FUNCION QUE VERIFICA QUE EL USUARIO Y LA CONTRASENA SEAN CORRECTOS*****
	function verificarPassUser(){
		var user = document.getElementById("usu").value;
		var pass = document.getElementById("pass").value;
		var vpass = document.getElementById("vpass").value;
		if((userDisp == false) || (pass != vpass)){
			if((userDisp == false)){
				alert('EL NOMBRE DE USUARIO "' + user + '" YA EXISTE, INTENTE DE NUEVO.');
				return false;
			}
			else{
				alert('LAS CONTRASEÑAS NO COINCIDEN, INTENTE DE NUEVO.');
				return false;
			}
		}else{
			return true;
		}
	};
	//*****FUNCION QUE VERIFICA QUE NO  CORRECTOS*****
	function verificarSuc(){
		var suc_nombre = $("#suc_nombre").val();
		if((sucDisp == false)){
			alert('EL NOMBRE DE LA SUCURSAL "' + suc_nombre + '" YA EXISTE, INTENTE DE NUEVO.');
			return false;
		}
		else{
			return true;
		}		
	};
	//*****INICIO GRITTER*****
	$.extend($.gritter.options, {
		class_name: 'gritter-light', // for light notifications (can be added directly to $.gritter.add too)
		position: 'bottom-right', // possibilities: bottom-left, bottom-right, top-left, top-right
		fade_in_speed: 1000, // how fast notifications fade in (string or int)
		fade_out_speed: 1500, // how fast the notices fade out
		time: 5000 // hang on the screen for...
	});
	function msgGritter(titulo,descripcion,imagen){
		$.gritter.add({
			// (string | mandatory) the heading of the notification
			title: titulo,
			// (string | mandatory) the text inside the notification
			text: descripcion,
			// (string | optional) the image to display on the left
			image: imagen,
			// (bool | optional) if you want it to fade out on its own or just sit there
			sticky: false,
				// (int | optional) the time you want it to be alive for before fading out
			time: '',
			// (string | optional) the class name you want to apply to that specific message
			class_name: 'my-sticky-class'
		});
	}
	//*****FIN GRITTER*****
	
	
	
	
</script>