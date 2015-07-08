<?php
  error_reporting (5);  
  include('start.php');
  $suc_query = sprintf("SELECT * from sucursal");
  $suc = mysql_query($suc_query, $conexion_mysql) or die(mysql_error());
  $tot_rows = mysql_num_rows($suc);
  $MM_redirecmenu = "index.php";


if ($tot_rows > 0)
{ 
  header("Location: ". $MM_redirecmenu );
}
?>
<!doctype html>
<html>
<head>  
  <meta charset="utf-8">
    <title>Configuracion</title>
    <?php include(RUTAs.'styles/styl-bootstrap.php'); ?>
    <link href="<?php echo $RUTAm.'login/styles/styl-login.css'; ?>" rel="stylesheet"></link>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
          <div class="container">
            <div class="row-fluid">
                    <div class="span2"></div>
                    <div class="span8" align="center" style="height:62px"><strong style="font-size:50px; color:#1B2772">CIF</strong><img style="alignment-adjust:central" width="70px" height="70px" src="<?php echo $RUTAi.'sistema/logo.png'; ?>"> <strong style="text-align:right; font-size:30px;color:#1B2772"> v 1.0</strong></div>
                    <div class="col-md-2"></div>
                  </div>  
            <h3>Control de inventario y facturacion</h3>
            <p>Gracias por usar CIF, ahora configuraremos el sistema ingrese sus datos en los campos solicitados.</p>            
            <table class="table">
              <thead>
                <tr>
                  <th>Empresa</th>
                  <th>Administrador</th>
                  <th><span id="mensaje"></span></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <input type="text" class="form-control" id="nom_empr" placeholder="Nombre Empresa" required>
                  </td>
                  <td>
                    <input type="text" class="form-control" id="nom_admin" placeholder="Nombre" required>
                  </td>
                  <td>
                    <input type="text" class="form-control" id="email_admin" placeholder="E-mail" required>
                  </td>
                </tr>
                <tr>
                  <td>
                    <input type="text" class="form-control" id="dir_empr" placeholder="Direccion Empresa" required>
                  </td>
                  <td><input type="text" class="form-control" id="ced_admin" placeholder="Cedula" required></td>
                  <td><input type="text" class="form-control" id="nom_usu" placeholder="Nombre Usuario" required></td>
                </tr>
                <tr>
                  <td><input type="text" class="form-control" id="tel_empr" placeholder="Telefono Empresa" required/></td>
                  <td><input type="text" class="form-control" id="dir_admin" placeholder="Direccion" required></td>
                  <td><input type="password" class="form-control" id="pass_usu" placeholder="Password Usuario" required></td>
                </tr>
                <tr>
                  <td></td>
                  <td><input type="text" class="form-control" id="tel_admin" placeholder="Telefono" required/></td>
                  <td><input type="password" class="form-control" id="con_pass_usu" placeholder="Confirmar Password" required onkeyup="verificar(this.value);"></td>
                </tr>                
              </tbody>
            </table>
          </div>
          <div class="alert alert-info clearfix">
            <button type="button" class="btn btn-primary btn-lg pull-right" onClick="guardar_conf()">Guardar</button>
          </div>
</body>
<footer style="background:#FFF"  class="footer hidden-phone">
  <?php include(RUTAm.'login/login-footer.php'); ?>
</footer>
</html>

<script type="text/javascript">

function verificar(v){
var p1 = document.getElementById('pass_usu');
if( p1.value != v){
document.getElementById('mensaje').innerHTML = "la contrasena no coincide";
}
  else
  {
    document.getElementById('mensaje').innerHTML = "";
  }
}

function guardar_conf()
    {
   var accion = 'Insertarconfig';
    
            $.ajax({
            type: "POST",
            url: "guardar_conf.php",
            async: false,
        data: {
           accion: accion,
           nom_empr:$("#nom_empr").val(),
           dir_empr:$("#dir_empr").val(),
           tel_empr:$("#tel_empr").val(),
           nom_admin:$("#nom_admin").val(),
           ced_admin:$("#ced_admin").val(),
           dir_admin:$("#dir_admin").val(),
           tel_admin:$("#tel_admin").val(),
           email_admin:$("#email_admin").val(),
           nom_usu:$("#nom_usu").val(),
           con_pass_usu:$("#con_pass_usu").val() 
        },
        success:  function(resultado) 
            {            
                window.location.href = "index.php";           
        }
      });
    }

</script>
