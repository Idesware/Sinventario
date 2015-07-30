<?php
	include('../../../start.php');
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
</head>
<div class="container">
	<div class="row">
    	<div class="col-md-2 col-md-offset-5">
        	<img id="loader" src="../../images/loading.gif"/>
            Cargando...
        </div> 
    </div>
</div>
<?php
/**
 *
 * @author   Esteban Vintimilla
 * @date   Julio 13, 2015
 * 
 */
 
//COMO EL INPUT FILE FUE LLAMADO archivo ENTONCES ACCESAMOS A TRAVÉS DE $_FILES["archivo"]
$extension = substr($_FILES["archivo"]["name"],-4,4);
if($extension == ".csv")
{
	
//SI EL ARCHIVO SE ENVIÃ" Y ADEMÃS SE SUBIO CORRECTAMENTE
if (isset($_FILES["archivo"]) && is_uploaded_file($_FILES['archivo']['tmp_name'])) {

 //SE ABRE EL ARCHIVO EN MODO LECTURA
 $fp = fopen($_FILES['archivo']['tmp_name'], "r");
 //SE RECORRE
 while (!feof($fp)){ //LEE EL ARCHIVO A DATA, LO VECTORIZA A DATA
  
  //SI SE QUIERE LEER SEPARADO POR TABULADORES
  //$data  = explode(" ", fgets($fp));
  //SI SE LEE SEPARADO POR COMAS
  $data  = explode(";", fgets($fp));
  
  	////////////////////////////////////////////////////////////////////////////////////////////////
	$nombre = utf8_encode($data[0]);
	////////////////////////////////////////////////////////////////////////////////////////////////
	$documento = utf8_encode($data[1]);
	////////////////////////////////////////////////////////////////////////////////////////////////
	$direccion = $data[2];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$mail = $data[3];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$telefono = $data[4];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$nombre_comercial = $data[5];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$nombre_contacto = $data[6];
	////////////////////////////////////////////////////////////////////////////////////////////////
	$telefono_contacto = $data[7];
	
	if($nombre != "" || $documento != "" || $direccion != "" || $mail != "" || $telefono != "" || $nombre_comercial != "" || $nombre_contacto != "" || $telefono_contacto != "")
	{
			
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$sqlValidarPersona = "Select per_id From persona Where per_documento ='$documento'" ;
	$queryValidarPersona = mysql_query($sqlValidarPersona, $conexion_mysql) or die(mysql_error());
	$tot_rows_sqlValidarPersona = mysql_num_rows($queryValidarPersona);	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if($tot_rows_sqlValidarPersona == 0)
		{
			$sqlPersona = "insert into persona(per_nombre,per_documento,per_direccion1,per_mail,per_telefono) values('$nombre','$documento','$direccion','$mail','$telefono')";
			mysql_query($sqlPersona, $conexion_mysql) or die(mysql_error());
			$id_persona=mysql_insert_id();
			$sqlProveedor = "insert into proveedor(prov_nom_com,per_id,prov_nom_cont,prov_cel_cont,prov_estado,prov_eliminado) values('$nombre_comercial','$id_persona','$nombre_contacto','$telefono_contacto','Activo','N')";
			mysql_query($sqlProveedor, $conexion_mysql) or die(mysql_error());	
		}
		else
		{
			$row = mysql_fetch_assoc($queryValidarPersona);
			$id_persona = $row['per_id'];
			$sqlProveedor = "insert into proveedor(prov_nom_com,per_id,prov_nom_cont,prov_cel_cont,prov_estado,prov_eliminado) values('$nombre_comercial','$id_persona','$nombre_contacto','$telefono_contacto','Activo','N')";
			mysql_query($sqlProveedor, $conexion_mysql) or die(mysql_error());
		}
	}
 }
   $mensaje = "Archivo subido correctamente";
} else 
	$mensaje = "Error al subir el archivo";
}
else{
	$mensaje = "El tipo de archivo debe ser .csv";
}
?>
</html>
<script type="text/javascript">
	setTimeout(recargar_pagina, 2000)
	
	function recargar_pagina() {
		alert("<?php echo $mensaje; ?>");
  		window.location="index.php";
	}
	
	function submit()
	{
		$('#loader').show();
		$('#loader').hide();
	}
</script>