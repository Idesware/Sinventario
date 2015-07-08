<?php 
	$id_user = $_SESSION['id_usuario'];
	$id_emp = $_SESSION['id_empleado'];
	$empleado = fnc_datEmp($id_emp);
	$persona = fnc_datPer($empleado['per_id']);
	$sucursal = fnc_datSuc($empleado['suc_id']);
?>

<div class="navbar navbar navbar-static-top">
	<div class="navbar-inner">
  		<div class="">
        	<button class="btn btn-navbar" data-target=".nav-collapse" data-toggle="collapse" type="button">
            	<span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
          	<a class="brand" href="<?php echo $RUTAm.'index.php'; ?>">CIF Autorizado a <strong><?php echo $sucursal['suc_nombre']; ?></strong></a>
 			<div class="nav-collapse collapse">
                <ul class="nav navbar-nav">
                    <?php
					//Consulta para Menus
					$sql_menus = sprintf("SELECT * FROM menus INNER JOIN menu_usuario ON menus.men_id = menu_usuario.men_id WHERE menus.men_padre = %s AND menu_usuario.usr_id = %s AND menus.men_eliminado = %s ORDER BY men_orden ASC",
					GetSQLValueString('0','int'),
					GetSQLValueString($id_user,'int'),
					GetSQLValueString('N','text'));
					$query_menus = mysql_query($sql_menus, $conexion_mysql) or die(mysql_error());
					$rows_menus = mysql_fetch_assoc($query_menus);
					$tot_rows_menus = mysql_num_rows($query_menus);
					
					if($tot_rows_menus > 0){
					do{ 
					//Consulta para Submenus
					$sql_submenus = sprintf("SELECT * FROM menus INNER JOIN menu_usuario ON menus.men_id = menu_usuario.men_id WHERE menus.men_padre = %s AND menu_usuario.usr_id = %s AND menus.men_eliminado = %s ORDER BY men_orden ASC",
					GetSQLValueString($rows_menus['men_id'],'int'),
					GetSQLValueString($id_user,'int'),
					GetSQLValueString('N','text'));
					$query_submenus = mysql_query($sql_submenus, $conexion_mysql) or die(mysql_error());
					$rows_submenus = mysql_fetch_assoc($query_submenus);
					$tot_rows_submenus = mysql_num_rows($query_submenus); ?>
                    
					<li class="dropdown"> 
						<?php if($rows_menus['men_link']){ $link = $RUTAm.$rows_menus['men_link']; }else{ $link = "#"; } ?>
						<a href="<?php echo $link; ?>" class="dropdown-toggle" <?php if($tot_rows_submenus > 0){ ?> data-toggle="dropdown" <?php } ?>><?php echo $rows_menus['men_nombre']; ?>
							<?php if($tot_rows_submenus > 0){ ?>
                            <b class="caret"></b>
                            <?php } ?>                           
						</a>
						<?php if($tot_rows_submenus > 0){ ?>
						<ul class="dropdown-menu">
						<?php 
							do{ 
							if($rows_submenus['men_link']){ $link = $RUTAm.$rows_submenus['men_link']; }else{ $link = "#"; } ?>
							<li><a href="<?php echo $link; ?>"><?php echo $rows_submenus['men_nombre']; ?></a></li>
							<?php 
							}while($rows_submenus = mysql_fetch_assoc($query_submenus)); mysql_free_result($query_submenus); ?>     
						</ul> 
						<?php } ?>                             	                    
					</li>
					<?php 
					}while($rows_menus = mysql_fetch_assoc($query_menus)); mysql_free_result($query_menus); 
					}else
						echo '<span style="color:red; ">No existen menus en la base de datos.</span>'; ?>
            	</ul> 
                <!-- Nombre de usuario logeado -->
               	<ul class="nav navbar-nav pull-right">
                	<li class="divider-vertical"></li>
					<div class="btn-group">
                        <a class="btn btn-primary" href="#"><i class="icon-user icon-white"></i> <?php echo $persona['per_nombre']; ?></a>
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#"><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a tabindex="-1" href="#"><i class="icon-user"></i> Mi perfil</a></li>
                            <li class="divider"></li>
                            <li><a tabindex="-1" href="<?php echo $RUTA.'logout.php'?>"><i class="icon-off"></i> Salir</a></li>
                    	</ul>
                    </div>
           		</ul>
           </div>   
  		</div>
    </div>  
</div>