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
	<title>Listado de actividades</title>
 
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
			<h2>Lista de Actividades</h2>
			<hr>
 
			<!-- Funcion Deshabilitar -->
			<?php
			if(isset($_GET['funcion']) == 'disabled'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
				$db_disabled = mysqli_query($conexion, "SELECT * FROM actividades WHERE codigo='$codigo'");
				if(mysqli_num_rows($db_disabled) == 0){
					echo '<div class="alert alert-info alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> No se encontraron los datos de la actividad.</div>';
				}else{
					$disabled = mysqli_query($conexion, "UPDATE actividades SET estado='6' WHERE codigo='$codigo'");
					if($disabled){
						echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>La actividad ha sido deshabilitada correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> Error, no se pudo deshabilitar la actividad.</div>';
					}
				}
			}
			?>
 
 			<!-- Filtrado por estado -->
			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control form-select" onchange="form.submit()">
						<option value="0">Filtro de datos de actividades:</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
						<option value="0" <?php if($filter == 'Ver Todos'){ echo 'selected'; } ?>>Ver Todos</option>
						<option value="1" <?php if($filter == 'Activa'){ echo 'selected'; } ?>>Ver activos</option>
						<option value="2" <?php if($filter == 'Inactiva'){ echo 'selected'; } ?>>Ver inactivos</option>
                        <option value="4" <?php if($filter == 'En Revisión'){ echo 'selected'; } ?>>Ver en revisión</option>
                        <option value="5" <?php if($filter == 'Subcontratada'){ echo 'selected'; } ?>>Ver subcontratados</option>
                        <option value="6" <?php if($filter == 'Deshabilitada'){ echo 'selected'; } ?>>Ver deshabilitado</option>
					</select>
				</div>

				<!-- Ordenar lista según -->
				<br>
				<div class="row g-4">
                    <?php $filtro_orden = (isset($_GET['filtro_orden']) ? strtolower($_GET['filtro_orden']) : NULL);  ?>
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_nombre" value = 'ordenar_por_nombre' onchange="form.submit()"
                            <?php if($filtro_orden == 'ordenar_por_nombre'){ echo 'checked'; } ?>>
							<label class="form-check-label" for="filtro_orden_nombre">
								Ordenar por Nombre
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_tipo" value = 'ordenar_por_tipo' onchange="form.submit()"
                            <?php if($filtro_orden == 'ordenar_por_tipo'){ echo 'checked'; } ?>>
							<label class="form-check-label" for="filtro_orden_tipo">
								Ordenar por Tipo y Subtipo
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_jugadores" value = 'ordenar_por_jugadores' onchange="form.submit()"
                            <?php if($filtro_orden == 'ordenar_por_jugadores'){ echo 'checked'; } ?>>
							<label class="form-check-label" for="filtro_orden_jugadores">
								Ordenar por Nº Jugadores
							</label>
						</div>
					</div>
				</div>

			</form>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<tr>
                    <th>No</th>
					<th>Código</th>
					<th>Tipo Actividad</th>
					<th>Subtipo Actividad</th>
                    <th>Nombre</th>
					<th>Descripcion</th>
					<th>Jugadores</th>
					<th>Materiales Asignados</th>
                    <th>Observaciones</th>
                    <th>Estado</th>
				</tr>
				<?php

				if(isset($_GET['buscar'])){
					$buscar = $_GET['buscar'];
					$sql = mysqli_query($conexion, "SELECT * FROM actividades WHERE codigo like '$buscar' 
						OR tipo like '%$buscar%'
						OR subtipo like '%$buscar%'
						OR etiquetas like '%$buscar%'
						OR nombre like '%$buscar%'
						OR observaciones like '%$buscar%'");

				}else{
					if($filter == 0){
						$sql = "SELECT * FROM actividades WHERE estado <> 6";
					}elseif($filter <> NULL){
						$sql = "SELECT * FROM actividades WHERE estado='$filter'";		
					
					}else{
						$sql = "SELECT * FROM actividades WHERE estado <> 6";
					}

					if($filtro_orden == 'ordenar_por_nombre'){
						$sql = $sql . " ORDER BY nombre ASC;";
					}elseif ($filtro_orden == 'ordenar_por_tipo'){
						$sql = $sql . " ORDER BY tipo ASC, subtipo ASC;";
					}elseif ($filtro_orden == 'ordenar_por_jugadores'){
						$sql = $sql . " ORDER BY jugadores ASC;";
					}
                    
					$sql = mysqli_query($conexion, $sql);
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
                        $codigo_actividades = $row['codigo'];
						echo '
						<tr>
							<td>'.$no.'</td>
							<td><a href="../profiles/profiles_actividades.php?codigo='.$codigo_actividades.'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer">'.$codigo_actividades.'</a></td>
							<td>'.$row['tipo'].'</td>
							<td>'.$row['subtipo'].'</td>
                            <td><a href="../profiles/profiles_actividades.php?codigo='.$codigo_actividades.'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer"><i class="material-icons">golf_course</i>'.$row['nombre'].'</a></td>
							<td>'.$row['descripcion'].'</td>
							<td>'.$row['jugadores'].'</td>';

							// Contar cantidad de monitores asignados
							$sql_cantidad_materiales = mysqli_query($conexion, "SELECT * FROM materiales_actividades WHERE codigo_actividades = '$codigo_actividades'");
							$cantidad_materiales = mysqli_num_rows($sql_cantidad_materiales);
						echo '
 							<td><a href="../edit/edit_actividades2.php?codigo='.$codigo_actividades.'" title="Editar materiales de la actividad" target="_blank" rel="noopener noreferrer">'.$cantidad_materiales.'</td>';

						echo '
							<td>'.$row['observaciones'].'</td>';

                        echo '
							<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Activa</span>';
							}
							else if ($row['estado'] == '2' ){
								echo '<span class="label label-info">Inactiva</span>';
							}
                            else if ($row['estado'] == '4' ){
								echo '<span class="label label-warning">En revisión</span>';
							}
                            else if ($row['estado'] == '5' ){
								echo '<span class="label label-info">Subcontratada</span>';
							}
                            else if ($row['estado'] == '6' ){
                                echo '<span class="label label-warning">Deshabilitada</span>';
                            }
						echo '
							</td>';
							
                            if ($_SESSION ['permisos'] > 1){
                                echo '
								<td>
									<a href="../edit/edit_actividades.php?codigo='.$row['codigo'].'" title="Editar datos" class="btn btn-warning btn-sm"><i class="material-icons">edit</i></a>
									<a href="../edit/edit_actividades2.php?codigo='.$row['codigo'].'" title="Editar Materiales" class="btn btn-primary btn-sm"><i class="material-icons">inbox</i></a>
									<a href="list_actividades.php?funcion=disabled&codigo='.$row['codigo'].'" title="Deshabilitar" onclick="return confirm(\'¿Esta seguro de deshabilitar los datos de la actividad '.$row['nombre'].'del tipo '.$row['tipo'].' y subtipo '.$row['subtipo'].'?\')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
								</td>';
                            }                      
		
						echo '
						</tr>';

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