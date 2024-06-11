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
	<title>Listado de monitores</title>
 
	<!-- Bootstrap -->
	<link href="../../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

	<!-- MaterializeCSS Icons -->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><!-- Web iconos https://materializecss.com/icons.html -->
	
</head>
<body>
	<!-- Navbar -->
	<?php include("../../include/nav.php");?>
    
    <!-- Contenido -->
	<div class="container">
		<div class="content">
			<a href="#" class="scrollup">Scroll</a>
			<h2>Lista de monitores</h2>
			<hr>
 
			<!-- Funcion Suprimir -->
			<?php
			if(isset($_GET['funcion']) == 'disabled'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
				$db_disabled = mysqli_query($conexion, "SELECT * FROM monitores WHERE codigo='$codigo'");
				if(mysqli_num_rows($db_disabled) == 0){
					echo '<div class="alert alert-info alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> No se encontraron los datos del monitor.</div>';
				}else{
					$disabled = mysqli_query($conexion, "UPDATE monitores SET estado='6' WHERE codigo='$codigo'");
					if($disabled){
						echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>El monitor ha sido deshabilitado correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> Error, no se pudo deshabilitar el monitor.</div>';
					}
				}
			}
			?>

			<!-- Filtrado por estado -->
			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control form-select" onchange="form.submit()">
						<option value="0">Filtro de datos de monitores:</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
						<option value="0" <?php if($filter == 'Ver Todos'){ echo 'selected'; } ?>>Ver Todos</option>
						<option value="1" <?php if($filter == 'Fijo'){ echo 'selected'; } ?>>Ver fijos</option>
						<option value="2" <?php if($filter == 'En evento'){ echo 'selected'; } ?>>Ver en eventos</option>
                        <option value="3" <?php if($filter == 'Libre'){ echo 'selected'; } ?>>Ver libres</option>
                        <option value="4" <?php if($filter == 'Desactualizado'){ echo 'selected'; } ?>>Ver desactualizado</option>
                        <option value="5" <?php if($filter == 'Subcontratado'){ echo 'selected'; } ?>>Ver subcontratados</option>
                        <option value="6" <?php if($filter == 'Deshabilitado'){ echo 'selected'; } ?>>Ver deshabilitado</option>
					</select>
				</div>

				<!-- Ordenar lista según -->

				<br>
				<div class="row g-3">
                    <?php $filtro_orden = (isset($_GET['filtro_orden']) ? strtolower($_GET['filtro_orden']) : NULL);  ?>
					<?php $check_tit_monitor = (isset($_GET['check_tit_monitor']) ? strtolower($_GET['check_tit_monitor']) : NULL);  ?>
					<?php $check_tit_coordinador = (isset($_GET['check_tit_coordinador']) ? strtolower($_GET['check_tit_coordinador']) : NULL);  ?>
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
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_poblacion" value = 'ordenar_por_poblacion' onchange="form.submit()"
                            <?php if($filtro_orden == 'ordenar_por_poblacion'){ echo 'checked'; } ?>>
							<label class="form-check-label" for="filtro_orden_poblacion">
								Ordenar por Población
							</label>
						</div>
					</div>
					<div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" id="check_tit_monitor" name="check_tit_monitor" class="form-check-input" value="true" onchange="form.submit()"
                            <?php if($check_tit_monitor == true){ echo 'checked'; } ?>>
                            <label for="check_tit_monitor" class="form-check-label">Solo Tit. Monitor</label>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" id="check_tit_coordinador" name="check_tit_coordinador" class="form-check-input" value="true" onchange="form.submit()"
                            <?php if($check_tit_coordinador == true){ echo 'checked'; } ?>>
                            <label for="check_tit_coordinador" class="form-check-label">Solo Tit. Coordinador</label>
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
					<th>Nombre completo</th>
                    <th>Fecha de nacimiento</th>
                    <th>Población</th>
					<th>Teléfono</th>
                    <th>Titulo Monitor</th>
                    <th>Titulo Coordinador</th>
                    <th>Estado</th>
				</tr>
				<?php
				if(isset($_GET['buscar'])){
					$buscar = $_GET['buscar'];
					$sql = mysqli_query($conexion, "SELECT * FROM monitores WHERE codigo like '$buscar'
						OR DNI_NIF like '%$buscar%'
						OR nombre like '%$buscar%'
						OR apellidos like '%$buscar%'
						OR poblacion like '%$buscar%'
						OR provincia like '%$buscar%'
						OR mail like '%$buscar%'
						OR telefono like '%$buscar%'");
					
				}else{
					if (isset($check_tit_monitor) == true){
                        $sql = "SELECT * FROM monitores WHERE tit_monitor = true";
                    }else if (isset($check_tit_coordinador) == true){
                        $sql = "SELECT * FROM monitores WHERE tit_coordinador = true";
                    }elseif($filter == 0){
						$sql = "SELECT * FROM monitores WHERE estado <> 6";
					}elseif($filter <> NULL){
						$sql ="SELECT * FROM monitores WHERE estado='$filter'";
					}else{
						$sql = "SELECT * FROM monitores WHERE estado <> 6";
					}

					if($filtro_orden == 'ordenar_por_nombre'){
						$sql = $sql . " ORDER BY nombre ASC, apellidos ASC;";
					}elseif ($filtro_orden == 'ordenar_por_poblacion'){
						$sql = $sql . " ORDER BY poblacion ASC;";
					}

					$sql = mysqli_query($conexion, $sql);
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="9">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
                        echo '
						<tr>
							<td>'.$no.'</td>
							<td><a href="../profiles/profiles_monitores.php?codigo='.$row['codigo'].'" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</a></td>
							<td><a href="../profiles/profiles_monitores.php?codigo='.$row['codigo'].'" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer"><i class="material-icons">assignment_ind</i> '.$row['nombre'].' '.$row['apellidos'].'</a></td>
                            <td>'.$row['fecha_nac'].'</td>
                            <td>'.$row['poblacion'].'</td>
							<td>'.$row['telefono'].'</td>
                            <td>';
                            if($row['tit_monitor'] == False){
								echo '<span class="label label-warning">No</span>';
							}
                            else if ($row['tit_monitor']== True ){
								echo '<span class="label label-success">Si</span>';
							}
                        echo '</td>
                            <td>';
                            if($row['tit_coordinador'] == False){
                                echo '<span class="label label-warning">No</span>';
                            }
                            else if ($row['tit_coordinador'] == True ){
                                echo '<span class="label label-success">Si</span>';
                            }
                        echo '</td>
							<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Puesto Fijo</span>';
							}
                            else if ($row['estado'] == '2' ){
								echo '<span class="label label-info">Ocupado</span>';
							}
                            else if ($row['estado'] == '3' ){
								echo '<span class="label label-success">Libre</span>';
							}
                            else if ($row['estado'] == '4' ){
								echo '<span class="label label-warning">En Revisión</span>';
							}
                            else if ($row['estado'] == '5' ){
								echo '<span class="label label-info">Temporal</span>';
							}
                            else if ($row['estado'] == '6' ){
                                echo '<span class="label label-warning">Deshabilitado</span>';
                            }
						echo '
							</td>';

                            if ($_SESSION ['permisos'] > 1){
                            echo '
								<td>
									<a href="../edit/edit_monitores.php?codigo='.$row['codigo'].'" title="Editar datos" class="btn btn-warning btn-sm"><i class="material-icons">edit</i></a>
									<a href="list_monitores.php?funcion=disabled&codigo='.$row['codigo'].'" title="Deshabilitar" onclick="return confirm(\'¿Esta seguro de deshabilitar los datos del monitor '.$row['nombre'].' '.$row['apellidos'].'?\')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
								</td>';
                            }  
						echo '</tr>
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