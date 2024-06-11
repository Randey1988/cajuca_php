<?php
	// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
	session_start();
	include ("../../include/conexion.php");
    include_once ("../../include/session.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Listado de materiales</title>
 
	<!-- Bootstrap -->
	<link href="../../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

	<!-- MaterializeCSS Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 
</head>
<body>
	<!-- Navbar -->
	<?php include("../../include/nav.php");?>
    
    <!-- Contenido -->
	<div class="container">
		<div class="content">
			<a href="#" class="scrollup">Scroll</a>
			<h2>Lista de Usuarios</h2>
			<hr>

            <!-- Restablecer contraseña -->
            <?php
            if(isset($_GET['funcion']) == 'reset'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
				$db_reset = mysqli_query($conexion, "SELECT * FROM usuarios WHERE codigo='$codigo'");
				if(mysqli_num_rows($db_reset) == 0){
					echo '<div class="alert alert-info alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> No se ha podido restablecer la contraseña.</div>';
				}else{
                    $reset_usuario = mysqli_fetch_assoc($db_reset);
                    $contrasena_hash = password_hash($reset_usuario['usuario'], PASSWORD_DEFAULT);
					$restablecer = mysqli_query($conexion, "UPDATE usuarios SET estado='2', contrasena='$contrasena_hash' WHERE codigo='$codigo'");
					if($restablecer){
						echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Se ha restablecido correctamente la contraseña del usuario, con el usuario como nueva contraseña.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> Error, no se pudo restablecer la contraseña.</div>';
					}
				}
			}
            ?>

            <!-- Boton de nuevo usuario -->
            <a href="../login/login_add.php" class="btn btn-sm btn-success"><i class="material-icons">add</i> Añadir usuario</a>
            <br><br>
 


            <!-- Select del filtro -->
 			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control form-select" onchange="form.submit()">
						<option value="0">Filtro de datos de usuarios:</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
                        <option value="0" <?php if($filter == 'Ver Todos'){ echo 'selected'; } ?>>Ver Todos</option>
						<option value="1" <?php if($filter == 'Activo'){ echo 'selected'; } ?>>Ver Activos</option>
						<option value="2" <?php if($filter == 'Renovar contraseña'){ echo 'selected'; } ?>>Ver pend. renovación</option>
                        <option value="4" <?php if($filter == 'En Revisión'){ echo 'selected'; } ?>>Ver en revisión</option>
                        <option value="5" <?php if($filter == 'Temporal'){ echo 'selected'; } ?>>Ver temporal</option>
                        <option value="6" <?php if($filter == 'Deshabilitado'){ echo 'selected'; } ?>>Ver </option>
					</select>
				</div>
			</form>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<tr>
                    <th>No</th>
					<th>Código</th>
                    <th>Permisos</th>
					<th>Usuario</th>
                    <th>Nombre y Apellidos</th>
                    <th>Mail</th>
                    <th>Observaciones</th>
                    <th>Estado</th>
				</tr>
				<?php

                if($filter == 0){
					$sql = mysqli_query($conexion, "SELECT * FROM usuarios WHERE estado <> 6 ORDER BY codigo ASC");
				}elseif($filter <> NULL){
					$sql = mysqli_query($conexion, "SELECT * FROM usuarios WHERE estado='$filter' ORDER BY codigo ASC");
				}else{
					$sql = mysqli_query($conexion, "SELECT * FROM usuarios WHERE estado <> 6 ORDER BY codigo ASC");
				}

				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
                        echo '
						<tr>
							<td>'.$no.'</td>
							<td>'.$row['codigo'].'</td>';

                        echo '<td>';
							if($row['permisos'] == '1'){
								echo '<span class="label label-success">Básico</span>';
							}
							else if ($row['permisos'] == '2' ){
								echo '<span class="label label-info">Avanzado</span>';
							}
                            else if ($row['permisos'] == '3' ){
								echo '<span class="label label-warning">Administrador</span>';
							}
						echo '
							</td>    
							<td>'.$row['usuario'].'</a></td>
                            <td>'.$row['nombre'].' '.$row['apellidos'].'</td>
							<td>'.$row['mail'].'</td>
							<td>'.$row['observaciones'].'</td>';

                        echo '<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Activo</span>';
							}
							else if ($row['estado'] == '2' ){
								echo '<span class="label label-info">Renovar contraseña</span>';
							}
                            else if ($row['estado'] == '4' ){
								echo '<span class="label label-warning">En Revisión</span>';
							}
                            else if ($row['estado'] == '5' ){
								echo '<span class="label label-info">Temporal</span>';
							}
                            else if ($row['estado'] == '6' ){
                                echo '<span class="label label-warning"></span>';
                            }
						echo '
							</td>
                            <td>
                                <a href="login_edit.php?usuario='.$row['usuario'].'" title="Editar datos" class="btn btn-primary btn-sm"><i class="material-icons">edit</i></a>
                                <a href="login_list.php?funcion=reset&codigo='.$row['codigo'].'" title="Restablecer contraseña" onclick="return confirm(\'¿Esta seguro de restablecer la contraseña del usuario '.$row['usuario'].' con código '.$row['codigo'].'?\')" class="btn btn-danger btn-sm"><i class="material-icons">autorenew</i></a>
                            </td>
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
		</div>
	</div><center>
	<p>&copy; Sistemas Web <?php echo date("Y");?></p
		</center>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="../../js/web_up.js" type="text/javascript"></script>

    <!-- Footer -->
    <footer>
        <?php include("../../include/footer.php");?>
    </footer>

    <?php
    // Cerrar conexión
    $conexion->close();
    ?>

</body>
</html>