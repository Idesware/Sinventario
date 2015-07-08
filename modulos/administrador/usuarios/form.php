<?php 
	if (!isset($_SESSION)) session_start();
	include('../../../start.php');
	fnc_autentificacion();
	$id_usr = fnc_varGetPost('user_id');
	$datUsr = fnc_datUsu($id_usr);

	if($datUsr){
		$accion = 'Actualizar';
		$button = '<input type="submit" class="btn btn-primary" name="btnGuardar" id="btnGuardar" value="ACTUALIZAR">';
	}else{
		$accion = 'Insertar';
		$button='<input type="submit" class="btn btn-primary" name="btnGuardar" id="btnGuardar" value="INSERTAR">';
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8"></meta>
	<title><?php echo $accion; ?> usuario</title>
    <?php include(RUTAp.'jquery/styl-jquery.php'); ?>
    <?php require_once(RUTAs.'styles/styl-bootstrap.php'); ?>
</head>
<body>
	<?php include(RUTAcom.'menu-principal.php'); ?>
    <div class="container">
		<div class="page-header"><h3><?php echo strtoupper($accion); ?> USUARIO</h3></div>
		<div class="row-fluid">
        	<div class="span8">
                <ul class="breadcrumb">
                    <li>
                        <i class="icon-home"></i>
                        <a href="<?php echo $RUTAm; ?>administrador/usuarios/index.php"> Administración de usuarios</a>
                        <span class="divider">/</span>
                    </li>
                    <li class="active"><?php echo $accion; ?> usuario</li>
                </ul>
			</div>
            <div class="span4">
            	<?php if($datUsr){ ?>
            	<a href="<?php echo $RUTAm; ?>administrador/usuarios/form.php" class="btn btn-primary btn-large btn-block"><strong> NUEVO USUARIO</strong></a>
                <?php } ?>
            </div> 
			
		</div>
		<div class="row-fluid">
			<div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab">Datos</a></li>
                    <li><a href="#tab2" data-toggle="tab">Permisos</a></li>
                </ul>
                <div class="control-group well">
                    <form class="form-horizontal" method="post" action="<?php echo $RUTAm; ?>administrador/funciones/usuarios-fncs.php" onSubmit="return verificarPassUser()">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab1">
                                <div class="control-group">
                                    <label class="control-label">Empleado</label>
                                    <div class="controls">
										
                                        <?php fnc_listEmp($id_usr,'input-block-level', 'emp', 'autofocus required'); ?>
                                    </div>                                            	                      	
                                </div>                                
                                <div class="control-group">
                                    <label class="control-label">Usuario</label>
                                    <div class="controls">
										<div class="input-append span10">
											<input type="text" class="input-block-level" id="usu" name="usu" value="<?php echo $datUsr['usr_nombre']; ?>" placeholder="Usuario" onKeyUp="" required>
                                             <span class="add-on" id="resultUser"></span>
										</div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Contraseña</label>
                                    <div class="controls">
                                        <input type="password" class="input-block-level" id="pass" name="pass" value="<?php echo $datUsr['usr_contrasena']; ?>" placeholder="Contraseña" required>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label">Verificar contraseña</label>
                                    <div class="controls">
                                        <input type="password" class="input-block-level" id="vpass" name="vpass" value="<?php echo $datUsr['usr_contrasena']; ?>" placeholder="Verificar contraseña" required>
                                    </div> 
                                </div>
                                
                                <p align="center"><?php echo $button; ?></p>
							</div>
                    		<div class="tab-pane" id="tab2">                                	
								<?php
                                //Consulta para Menus
                                $sql_menus = sprintf("SELECT * FROM menus WHERE men_padre=%s AND men_eliminado=%s ORDER BY men_orden ASC",
                                GetSQLValueString('0','int'),
                                GetSQLValueString('N','text'));
                                $query_menus = mysql_query($sql_menus, $conexion_mysql) or die(mysql_error());
                                $row_menus = mysql_fetch_assoc($query_menus);
                                $tot_rows_menus = mysql_num_rows($query_menus); 
								
								$cont=0;
								if($tot_rows_menus > 0){
                                do{ 
                                //Consulta para Submenus
                                $sql_submenus = sprintf("SELECT * FROM menus WHERE men_padre=%s AND men_eliminado=%s",
                                GetSQLValueString($row_menus['men_id'],'int'),
                                GetSQLValueString('N','text'));
                                $query_submenus = mysql_query($sql_submenus, $conexion_mysql) or die(mysql_error());
                                $row_submenus = mysql_fetch_assoc($query_submenus);
                                $tot_rows_submenus = mysql_num_rows($query_submenus); ?>
                                                
                                <?php if($cont==0 || $cont==6){ ?>
                                <div class="row-fluid">
                                <?php } ?>
                                    <div class="span2">                                            
                                        <div class="control-group">
                                            <label class="checkbox"> 						
                                                <input name="menus[]" type="checkbox" id="menus" value="<?php echo $row_menus['men_id']; ?>" <?php if(("menus").value==2){ ?> disabled <?php }else?>checked> <?php echo $row_menus['men_nombre']; ?>
												<?php if($tot_rows_submenus > 0){
												do{ ?>
												<label class="checkbox">
													<input name="menus[]" type="checkbox" id="menus" value="<?php echo $row_submenus['men_id']; ?>" checked> <?php echo $row_submenus['men_nombre']; ?>
												</label>
												<?php 
												}while($row_submenus = mysql_fetch_assoc($query_submenus)); mysql_free_result($query_submenus); 
												} ?>  
                                            </label>
                                        </div>                                            
                                    </div>
                                <?php if($cont==5 || $cont==6){ ?>
                                </div>
                                <?php } 
								$cont ++;
								
                                }while($row_menus = mysql_fetch_assoc($query_menus)); mysql_free_result($query_menus); 
                                }else echo '<span style="color:red; ">No existen menus en la base de datos.</span>'; ?>
							</div>
                            <div class="form-actions">
                                <input type="hidden" name="accion" id="accion" value="<?php echo $accion; ?>">
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $id_usr; ?>">
								<?php echo $button; ?>
                                <a href="<?php echo $RUTAm; ?>administrador/usuarios/index.php" type="button" class="btn">CANCELAR</a>
                            </div>
						</div>
                	</form>
				</div>
            </div>
		</div>
    </div>
</body>
<footer>
</footer>
</html>