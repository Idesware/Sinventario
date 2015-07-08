<?php 
	include('start.php');

  $suc_query = sprintf("SELECT * from sucursal");
  $suc = mysql_query($suc_query, $conexion_mysql) or die(mysql_error());
  $tot_rows = mysql_num_rows($suc);
  $MM_redirectconfig = "configuracion.php";

if ($tot_rows==0)
{ 
  header("Location: ". $MM_redirectconfig );
}

	$loginFormAction = $_SERVER['PHP_SELF'];
	
	if (isset($_GET['accesscheck'])){
	 	$_SESSION['PrevUrl'] = $_GET['accesscheck'];
	}

	if (isset($_POST['user'])){	
  	$loginUsername=$_POST['user'];
		$password=$_POST['pass'];
		$MM_fldUserAuthorization = "";
		$MM_redirectLoginSuccess = "modulos/index.php";
		$MM_redirectLoginFailed = "index.php?wrong=wd";
		$MM_redirecttoReferrer = false;
		mysql_select_db($database_conexion_mysql, $conexion_mysql);
  
  		$LoginRS__query=sprintf("SELECT * FROM usuarios WHERE usr_nombre = %s AND usr_contrasena = %s",
    	GetSQLValueString($loginUsername, "text"), 
		  GetSQLValueString(md5($password), "text"));    
  		$LoginRS = mysql_query($LoginRS__query, $conexion_mysql) or die(mysql_error());
		  $row_RS_datos = mysql_fetch_assoc($LoginRS);		
  		$loginFoundUser = mysql_num_rows($LoginRS);
  		
		if ($loginFoundUser){
    		$loginStrGroup = "";
    
			if (PHP_VERSION >= 5.1){
				session_regenerate_id(true);
			} 
			else{
				session_regenerate_id();
			}
			//declare session variables and assign them
			$_SESSION['MM_Username'] = $loginUsername;
			$_SESSION['MM_UserGroup'] = $loginStrGroup;
			$_SESSION['autentificacion'] = true;
			$_SESSION['id_usuario'] = $row_RS_datos['usr_id'];
			$_SESSION['id_empleado'] = $row_RS_datos['emp_id'];

    		if (isset($_SESSION['PrevUrl']) && false){
      			$MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    		}
    		header("Location: " . $MM_redirectLoginSuccess );
  		}
  		else{
   			header("Location: ". $MM_redirectLoginFailed );
  		}
	}
?>

<!doctype html>
<html>
<head>	
	<meta charset="utf-8">
   	<title>Iniciar Sesión Sys-Int</title>
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
    <link href="<?php echo $RUTAm.'login/styles/styl-login.css'; ?>" rel="stylesheet"></link>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
        <div class="row-fluid">
          <div class="span2"></div>
          <div class="span8" align="center" style="height:62px"><strong style="font-size:50px; color:#1B2772">CIF</strong><img style="alignment-adjust:central" width="70px" height="70px" src="<?php echo $RUTAi.'sistema/logo.png'; ?>"> <strong style="text-align:right; font-size:30px;color:#1B2772"> v 1.0</strong></div>
          <div class="col-md-2"></div>
        </div>	
        <br>    
            <div class="row-fluid">          
            <div class="span4" align="right">
                
            </div>          
            <div class="span4">
                <form name="form_login" class="formulario-login" method="POST" action="<?php echo $loginFormAction; ?>">
                    <h3>Acceso al sistema</h3>
                    <?php
                        if($_GET['wrong']=='wd'){
							echo '<div class="alert alert-error">';
							echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
							echo '<h6>Datos incorrectos, por favor intente de nuevo.</h6>';
							echo '</div>';
						}
                    ?> <div align="center">                       
                    <input  type="text" class="form-control" placeholder="Usuario" name="user" required autofocus>       		
                    <input type="password" class="form-control" placeholder="Contraseña" name="pass" required>
                    <table cellspacing="2">
                      <tr>
                        <td><label class="checkbox"><input type="checkbox" value="remember-me" style="alin:right">Recordar mis datos.</label>	</td>
                      </tr>
                    </table>
                    <input class="btn btn-primary btn-lg" type="submit" name="btn_ingresar" value="Ingresar">  
                    </div>
                </form>
            </div>        	       
        </div>  
    </div>	    
</body>
<footer style="background:#FFF"  class="footer hidden-phone">
<hr>
	<?php include(RUTAm.'login/login-footer.php'); ?>
</footer>
</html>
