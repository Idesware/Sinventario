<?php 
	if (!isset($_SESSION)) session_start();
	include('start.php');
	fnc_autentificacion();
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title>Crear usuario</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
    
	<script type="text/javascript">
		$(document).on('ready', function(){
			$("#emp").autocomplete({
				//source: [{"value":"Some Name","id":1},{"value":"Some Othername","id":2}]
				//source: '../../system/funciones/fncs-system.php',
				source: 'funciones/bus-emp.php',
				minLength: 1,
				autoFocus: true
			});
		});
		$(document).on('ready', function(){
		$("#pac").autocomplete({
				//source: [{"value":"Some Name","id":1},{"value":"Some Othername","id":2}]
				//source: '../../system/funciones/fncs-system.php',
				source: 'funciones/bus-paciente.php',
				minLength: 1,
				autoFocus: true
			});
		});
	
	</script>
     
</head>
<body>                             
                               <?php do{ ?> 
                                <div class="row-fluid">
                                	<?php do{ ?>                                
                                    <div class="span3">                                            
                                        <div class="control-group">
											<label class="checkbox"> 						
                                                <input type="checkbox" id="" value="option1">1
												<label class="checkbox">
													<input type="checkbox" id="" value="option1">1.1
												</label>												
                                            </label>

                                        </div>                                            
                                    </div>
                                    <?php $cont ++; }while($cont<4); $cont=0; ?>
                                </div>
                                <?php $cont1 ++; }while($cont1<2);  ?>
                                  
                                
</body>
<footer>
</footer>
</html>                                
                                