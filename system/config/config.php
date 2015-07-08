<?php
if (!isset($_SESSION)){session_start();}	
/*
//REMOTO
define('RUTA',$_SERVER['DOCUMENT_ROOT'].'/');
define('RUTAcom',$_SERVER['DOCUMENT_ROOT'].'/componentes/');
define('RUTAcon',$_SERVER['DOCUMENT_ROOT'].'/connections-db/');
define('RUTAi',$_SERVER['DOCUMENT_ROOT'].'/images/');
define('RUTAidb',$_SERVER['DOCUMENT_ROOT'].'/images/db/');
define('RUTAm',$_SERVER['DOCUMENT_ROOT'].'/modulos/');
define('RUTAp',$_SERVER['DOCUMENT_ROOT'].'/plugins/');
define('RUTAs',$_SERVER['DOCUMENT_ROOT'].'/system/');

global $RUTA,$RUTAcom,$RUTAcon,$RUTAi,$RUTAm,$RUTAp,$RUTAs;
$RUTA='http://demo.idesware.com/';
$RUTAcom=$RUTA.'componentes/';
$RUTAcon=$RUTA.'connections-db/';
$RUTAi=$RUTA.'images/';
$RUTAidb=$RUTA.'images/db/';
$RUTAm=$RUTA.'modulos/';
$RUTAp=$RUTA.'plugins/';
$RUTAs=$RUTA.'system/';

$_SESSION['urlp']=$_SESSION['urlc'];
$_SESSION['urlc']=basename($_SERVER['SCRIPT_FILENAME']);//URL clean Current;
$sdate=date('Y-m-d');
$sdatet=date('Y-m-d H:m:s');
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
*/

//LOCAL
define('RUTA',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/');
define('RUTAcom',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/componentes/');
define('RUTAcon',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/connections-db/');
define('RUTAi',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/images/');
define('RUTAidb',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/images/db/');
define('RUTAm',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/modulos/');
define('RUTAp',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/plugins/');
define('RUTAs',$_SERVER['DOCUMENT_ROOT'].'/Sinventario/system/');

global $RUTA,$RUTAcom,$RUTAcon,$RUTAi,$RUTAm,$RUTAp,$RUTAs;
$RUTA='http://localhost/Sinventario/';
$RUTAcom=$RUTA.'componentes/';
$RUTAcon=$RUTA.'connections-db/';
$RUTAi=$RUTA.'images/';
$RUTAidb=$RUTA.'images/db/';
$RUTAcon=$RUTA.'connections-db/';
$RUTAm=$RUTA.'modulos/';
$RUTAp=$RUTA.'plugins/';
$RUTAs=$RUTA.'system/';

$_SESSION['urlp']=$_SESSION['urlc'];
$_SESSION['urlc']=basename($_SERVER['SCRIPT_FILENAME']);//URL clean Current;
$sdate=date('Y-m-d');
$sdatet=date('Y-m-d H:m:s');
setlocale(LC_ALL,"es_ES@euro","es_ES","esp");

?>