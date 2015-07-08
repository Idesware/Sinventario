<?php
if (!function_exists("GetSQLValueString")){
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
		if (PHP_VERSION < 6){ $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue; }
		$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

		switch ($theType) {
			case "text":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL"; break;    
			case "long":
			case "int":
				$theValue = ($theValue != "") ? intval($theValue) : "NULL"; break;
			case "double":
				$theValue = ($theValue != "") ? doubleval($theValue) : "NULL"; break;
			case "date":
				$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL"; break;
			case "defined":
				$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue; break;
		}return $theValue;
	}
}
//SI EL USUARIO NO SE HA LOGEADO REDIRECCIONA A wrong-access.php
function fnc_autentificacion(){
	if(!$_SESSION['autentificacion'])
		header('Location: '.$GLOBALS['RUTA'].'wrong-access.php');		 
}
//FUNCION PARA IDENTIFICAR SI UNA VARIABLE INGRESAR POR POST O GET
function fnc_varGetPost($var){
	if(isset($_POST[$var])){
		$tipVar = $_POST[$var];
	}
	if(isset($_GET[$var])){
		$tipVar = $_GET[$var];
	}
	return $tipVar;
}
//FUNCION PARA MOSTRAR UN MENSAJE
function fnc_msgGritter(){
	if(isset($_SESSION['MSG'])){
		echo '<script type="text/javascript">msgGritter("'.$_SESSION['MSG'].'","'.$_SESSION['MSGdes'].'","'.$_SESSION['MSGimg'].'");</script>';
		unset($_SESSION['MSG']);
		unset($_SESSION['MSGdes']);
		unset($_SESSION['MSGimg']);
	}
}
//LISTA LOS EMPLEADOS AGRUPADOS POR SUCURSAL
function fnc_listEmp($id_user=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	if(!isset($id_user)){
		$sql_1 = "SELECT * FROM sucursal WHERE suc_eliminado = 'N' ORDER BY suc_id";
		$query_1 = mysql_query($sql_1, $conexion_mysql) or die(mysql_error());
		
		$sql_2 = "SELECT * FROM persona INNER JOIN empleado ON persona.per_id = empleado.per_id WHERE empleado.emp_id NOT IN (SELECT emp_id FROM usuarios) AND empleado.emp_eliminado = 'N'";
		$query_2 = mysql_query($sql_2, $conexion_mysql) or die(mysql_error());
		$tot_rows = mysql_num_rows($query_2);
		
		if($tot_rows>0) $txt = 'Seleccione un empleado';
		else $txt = 'No existen empleados';
		
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option disabled selected>'.$txt.'</option>';
		while($rows_1 = mysql_fetch_array($query_1)){
			echo '<optgroup label="'.$rows_1['suc_nombre'].'">';
			$sql_2 = "SELECT * FROM persona INNER JOIN empleado ON persona.per_id = empleado.per_id WHERE empleado.emp_id NOT IN (SELECT emp_id FROM usuarios) AND empleado.suc_id ='".$rows_1['suc_id']."' AND empleado.emp_eliminado = 'N'";
			$query_2 = mysql_query($sql_2, $conexion_mysql) or die(mysql_error());	
			while($rows_2 = mysql_fetch_array($query_2)){
				echo '<option value="'.$rows_2['emp_id'].'">'.$rows_2['per_nombre'].'</option>';
			}
			echo '<optgroup>';
		}
		echo '</select>';
		mysql_free_result($query_1);
		mysql_free_result($query_2);
	}else{
		$sql = "SELECT * FROM persona INNER JOIN empleado ON persona.per_id = empleado.per_id INNER JOIN usuarios ON empleado.emp_id = usuarios.emp_id WHERE usuarios.usr_id = '".$id_user."'";
		$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
		$row = mysql_fetch_assoc($query);		
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'">';
		echo '<option value="'.$row['emp_id'].'" selected>'.$row['per_nombre'].'</option>';
		echo '</select>';
		mysql_free_result($query);
	}
}
//GENERA UN SELECT
function fnc_genSelect($datos=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	$tot_rows = mysql_num_rows($datos);
	if($tot_rows>0) $txt = 'Seleccione';
	else $txt = 'No existen sucursales';	
	
	echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
	echo '<option disabled selected>'.$txt.'</option>';
	while($row = mysql_fetch_assoc($datos)){
		echo '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
	}
	echo '</select>';
	mysql_free_result($datos);
}
//DATOS PERSONALES
function fnc_datPer($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM persona WHERE per_id = %s", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query);
}
//DATOS USUARIO
function fnc_usuario($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM persona 
INNER JOIN empleado on persona.per_id = empleado.per_id
INNER JOIN usuarios on usuarios.emp_id = empleado.emp_id
WHERE usr_id = %s", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query);
}

//DATOS EMPLEADO
function fnc_datEmp($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM empleado WHERE emp_id = %s AND emp_eliminado = %s", 
	GetSQLValueString($id, "int"),
	GetSQLValueString('N', "text"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query); 
}
//DATOS USUARIO
function fnc_datUsu($id){ 
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM usuarios WHERE usr_id = %s AND usr_eliminado = %s", 
	GetSQLValueString($id, "int"),
	GetSQLValueString('N', "text"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}
//DATOS PACIENTE
function fnc_datPac($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM paciente WHERE per_id = %s AND pac_eliminado='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query); 
}
//DATOS TELÉFONO PACIENTE
function fnc_datTelPac($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM telefono WHERE per_id = %s AND tel_eliminado='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	
	mysql_free_result($query); 
}
//DATOS SUCURSAL
function fnc_datSuc($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM sucursal WHERE suc_id = %s AND suc_eliminado='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}
//DATOS SUCURSALES
function fnc_datosSuc(){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT suc_id AS id, suc_nombre AS nombre FROM sucursal WHERE suc_eliminado=%s", GetSQLValueString('N', "text"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	return $query;
	mysql_free_result($query);
}
//DATOS TRATAMIENTO
function fnc_datTra($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM tratamientos inner join tra_categorias on tratamientos.tra_cat_id = tra_categorias.tra_cat_id  WHERE tratamientos.tra_elim = 'N'and tratamientos.tra_id = %s AND tra_elim='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}

//DATOS SOLO TRATAMIENTOS
function fnc_datTraS($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM tratamientos WHERE tra_cat_id = %s AND tra_elim='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}

//DATOS CATEGORÍA
function fnc_datCat($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM tra_categorias WHERE tra_cat_id = %s AND tra_cat_elim='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}
//DATOS TELEFONOS
function fnc_datTel($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM telefono WHERE tel_id = %s AND tel_eliminado='N'", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}
//CALCULA LA EDAD
function fnc_calEdad($fec_nac){
    list($ano,$mes,$dia) = explode("-",$fec_nac);
    $ano_diferencia  = date("Y") - $ano;
    $mes_diferencia = date("m") - $mes;
    $dia_diferencia   = date("d") - $dia;
    if ($dia_diferencia < 0 || $mes_diferencia < 0)
        $ano_diferencia--;
    return $ano_diferencia;	

}
//DATOS PACIENTE BUSCAR
function fnc_datPacBus($buscar_paciente){
	echo $buscar_paciente;
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM paciente inner join persona on persona.per_id=paciente.per_id WHERE per_nombre like '%s'
	", GetSQLValueString($buscar_paciente, "texto"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query);
}
//LISTA DE PACIENTES AGRUPADOS
function fnc_listPac($id_user=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	$sql = "SELECT * FROM persona INNER JOIN paciente ON persona.per_id = paciente.per_id WHERE paciente.pac_eliminado = 'N' AND persona.per_nombre LIKE '%".$_REQUEST['term']."%'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);
	if($tot_rows>0) $txt = 'Seleccione un Paciente';
		else $txt = 'No existen pacientes';
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option disabled selected>'.$txt.'</option>';
		
		while($rows_1 = mysql_fetch_array($query)){
			echo '<option value="'.$rows_1['pac_id'].'">'.$rows_1['per_nombre'].'</option>';
			echo '<optgroup>';
		}
}
//LISTA DE CATEGORIAS PARA EL ADMINISTRADOR
function fnc_listCatAdm($id_user=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	$sql = "SELECT * FROM tra_categorias WHERE tar_cat_nom LIKE '%".$_REQUEST['term']."%'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);
	if($tot_rows>0) $txt = 'Seleccione una Categoria';
		else $txt = 'No existen Categorias';
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option disabled selected>'.$txt.'</option>';
		
		while($rows_1 = mysql_fetch_array($query)){
			echo '<option value="'.$rows_1['tra_cat_id'].'">'.$rows_1['tar_cat_nom'].'</option>';
			echo '<optgroup>';
		}
}

//DATOS CATEGORIA POR TRATAMIENTO
function fnc_datCatTra($tra_id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("select tratamientos.tra_id, tra_categorias.tra_cat_id, tra_categorias.tra_cat_nom, tratamientos.tra_nom from tratamientos inner join tra_categorias on tra_categorias.tra_cat_id = tratamientos.tra_cat_id where tratamientos.tra_id = '%s'",
	 GetSQLValueString($tra_id, "texto"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query);
}

//LISTA DE CATEGORIAS EN SUCURSALES
function fnc_listSuc($idSelSuc=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	$sql = "SELECT * FROM sucursal";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);

		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($idSelSuc==$rows_1['suc_id']) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1['suc_id'].'" '.$valSel.'>'.$rows_1['suc_nombre'].'</option>';
		}
		echo '<select>';
}
//LISTA DE TRATAMIENTOS 
function fnc_listTra($Sel_Tra=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	$sql = "SELECT * FROM tratamientos where tra_elim='N'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($Sel_Tra==$rows_1['tra_id']) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1['tra_id'].'" '.$valSel.'>'.$rows_1['tra_nom'].'</option>';
		}
		echo '<select>';
}
//DATOS SUCURSAL-PRECIOS-TRATAMIENTOS
function fnc_datCatPreTra($pre_id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM precios inner join sucursal on precios.suc_id = sucursal.suc_id  inner join tratamientos on tratamientos.tra_id = precios.tra_id WHERE precios.pre_id = %s AND tra_elim='N'", 
	GetSQLValueString($pre_id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}
//fincion general de busqueda
function fnc_datGeneral($id,$variable,$tabla){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM ".$tabla." WHERE ".$variable."  = %s ", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}

//DATOS PRODUCTOS
function fnc_datPro($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM producto  INNER JOIN unidad_producto ON producto.unidad_id=unidad_producto.unidad_id
		INNER JOIN categoria_producto ON producto.cat_id=categoria_producto.cat_id
        WHERE pro_id= %s AND pro_eliminado = 'N'", 
	GetSQLValueString($id, "int"));

	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row; 
	mysql_free_result($query); 
}









//LISTA DE PRODUCTOS DE PRODUCTOS
	function fnc_listPro($idSel=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	$sql = "SELECT pro_id,pro_nombre FROM producto
where pro_inv_inic = 'N'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);

		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($idSel==$rows_1['pro_id']) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1['pro_id'].'" '.$valSel.'>'.$rows_1['pro_nombre'].'</option>';
		}
		echo '<select>';
};
//LISTA DE GENERAL 
function fnc_listGeneral($idvar, $Var_con, $Var_con2, $tabla, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	$sql = "SELECT * FROM ".$tabla;
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($idvar==$rows_1[$Var_con]) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1[$Var_con].'" '.$valSel.'>'.$rows_1[$Var_con2].'</option>';
		}
		echo '<select>';
}
function fnc_listGeneralWhere($idvar, $Var_con, $Var_con2,$condicion, $tabla, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	$sql = "SELECT * FROM ".$tabla."where ".$condicion;
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);
		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($idvar==$rows_1[$Var_con]) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1[$Var_con].'" '.$valSel.'>'.$rows_1[$Var_con2].'</option>';
		}
		echo '<select>';
}

//LISTA DE AJUSTE DE STOCK
	function fnc_listajusStock($idSel=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	$sql = "SELECT * FROM producto inner join detalle_producto on detalle_producto.pro_id=producto.pro_id inner join stock on detalle_producto.det_pro_id=stock.det_pro_id where producto.pro_eliminado='N'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);

		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($idSel==$rows_1['pro_id']) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1['pro_id'].'" '.$valSel.'>'.$rows_1['pro_nombre'].'</option>';
		}
		echo '<select>';
};
//LISTA DE TIPO DE AJUSTES
	function fnc_listtip_ajuste($idSel=NULL, $clase=NULL, $id=NULL, $opt=NULL){
	include(RUTAcon.'conexion-mysql.php');
	
	$sql = "SELECT * FROM tipos where tip_tabla='ajuste' and tip_eliminado='N'";
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$tot_rows = mysql_num_rows($query);

		echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
		echo '<option>- Seleccione Opcion- </option>';
		while($rows_1 = mysql_fetch_array($query)){
			if($idSel==$rows_1['tip_id']) $valSel='selected';
			else $valSel='';
			echo '<option value="'.$rows_1['tip_id'].'" '.$valSel.'>'.$rows_1['tip_des'].'</option>';
		}
		echo '<select>';
};
//DATOS STOCK
function fnc_datStock($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM detalle_producto inner join stock on detalle_producto.det_pro_id=stock.det_pro_id where detalle_producto.pro_id=%s", 
	GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}

function fnc_stockprecio($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT pro_nombre, det_pro_costo, est_iva, met_gan_pvp, val_gan_pvp, pvp FROM detalle_producto 
INNER JOIN producto ON detalle_producto.pro_id = producto.pro_id 
WHERE producto.pro_id=%s", 
	GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}


//funcion que obtiene el numero de productos que han cumplido el stock minimo
function fnc_numproago($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM producto INNER JOIN detalle_producto on producto.pro_id = detalle_producto.pro_id INNER JOIN stock on detalle_producto.det_pro_id = stock.det_pro_id WHERE pro_eliminado = 'N' AND det_pro_eliminado = 'N' AND stk_cantidad <= stk_minimo AND suc_id = %s", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	return $tot_rows;
	mysql_free_result($query);
}
//FUNCIÓN QUE OBTIENE LA PERSONA Y PROVEEDOR
function fnc_Perprov($id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = sprintf("SELECT * FROM persona INNER JOIN proveedor on persona.per_id = proveedor.per_id where proveedor.per_id=%s", GetSQLValueString($id, "int"));
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	$tot_rows = mysql_num_rows($query); 
	return $row;
	mysql_free_result($query);
}
function fnc_listCaja($idvar, $Var_con, $Var_con2, $tabla, $clase=NULL, $id=NULL, $opt=NULL, $suc){
include(RUTAcon.'conexion-mysql.php');
$sql = "SELECT * FROM ".$tabla. " WHERE suc_id = ".$suc;
$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
$tot_rows = mysql_num_rows($query);
echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
echo '<option>- Seleccione Opcion- </option>';
while($rows_1 = mysql_fetch_array($query)){
if($idvar==$rows_1[$Var_con]) $valSel='selected';
else $valSel='';
echo '<option value="'.$rows_1[$Var_con].'" '.$valSel.'>'.$rows_1[$Var_con2].'</option>';
}
echo '<select>';
}


function fnc_listUnidades($idvar, $Var_con, $Var_con2, $tabla, $clase=NULL, $id=NULL, $opt=NULL){
include(RUTAcon.'conexion-mysql.php');
$sql = "SELECT * FROM ".$tabla;
$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
$tot_rows = mysql_num_rows($query);
echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
echo '<option>- Seleccione Opcion- </option>';
while($rows_1 = mysql_fetch_array($query)){
if($idvar==$rows_1[$Var_con]) $valSel='selected';
else $valSel='';
echo '<option value="'.$rows_1[$Var_con].'" '.$valSel.'>'.$rows_1[$Var_con2].'</option>';
}
echo '<select>';
}

function fnc_listCategoria($idvar, $Var_con, $Var_con2, $tabla, $clase=NULL, $id=NULL, $opt=NULL){
include(RUTAcon.'conexion-mysql.php');
$sql = "SELECT * FROM ".$tabla;
$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
$tot_rows = mysql_num_rows($query);
echo '<select class="'.$clase.'" id="'.$id.'" name="'.$id.'" '.$opt.'>';
echo '<option>- Seleccione Opcion- </option>';
while($rows_1 = mysql_fetch_array($query)){
if($idvar==$rows_1[$Var_con]) $valSel='selected';
else $valSel='';
echo '<option value="'.$rows_1[$Var_con].'" '.$valSel.'>'.$rows_1[$Var_con2].'</option>';
}
echo '<select>';
}

//Funcion Validar URL
function fnc_datURLv($urlactual,$usr_id){
	include(RUTAcon.'conexion-mysql.php');
	$sql = 'SELECT * FROM menus inner join menu_usuario on menus.men_id=menu_usuario.men_id
where usr_id ='.$usr_id.' and men_link LIKE  "%'.$urlactual.'"';
	$query = mysql_query($sql, $conexion_mysql) or die(mysql_error());
	$row = mysql_fetch_assoc($query);
	return $row;
	mysql_free_result($query);
}
?>