<?php include('start.php'); ?>
<!doctype html>
<html>
<head>	
	<meta charset="utf-8">
    <title>Permisos insuficientes</title>	
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
    <link href="<?php echo $RUTAm.'login/styles/styl-login.css'; ?>" rel="stylesheet"></link>  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
</head>
<body>
	<div class="container">	    
		<div class="row">      
			<div class="span3"></div>   
			<div class="span6">				  
				<div class="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<h4>Permisos insuficientes, por favor intente de nuevo.</h4>
				</div>
                <p><a href="index.php"><h4 class="text-center">Iniciar sesión</h4></a></p>
			</div>          
			<div class="span3"></div>        	       
		</div>	
	</div>	    
</body>
<footer class="footer hidden-phone" style="background:#CCC">
	<?php include(RUTAm.'login/login-footer.php'); ?>
</footer>
</html>