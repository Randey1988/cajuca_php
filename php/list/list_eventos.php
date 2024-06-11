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
	<title>Listado de eventos</title>
 
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
			<h2>Lista de eventos</h2>
			<hr>
 
            <!-- Funcion Suprimir -->
			<?php
			if(isset($_GET['funcion']) == 'disabled'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
				$db_disabled = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo='$codigo'");
				if(mysqli_num_rows($db_disabled) == 0){
					echo '<div class="alert alert-info alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> No se encontraron los datos del evento.</div>';
				}else{
					$disabled = mysqli_query($conexion, "UPDATE eventos SET estado='6' WHERE codigo='$codigo'");
					if($disabled){
						echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>El evento ha sido deshabilitado correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> Error, no se pudo deshabilitar el evento.</div>';
					}
				}
			}
			?>

            <!-- Select del filtro -->
			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control form-select" onchange="form.submit()">
						<option value="0">Filtro de datos de clientes:</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
                        <option value="0" <?php if($filter == 'Ver Todos'){ echo 'selected'; } ?>>Ver Todos</option>
						<option value="1" <?php if($filter == 'Pendiente'){ echo 'selected'; } ?>>Ver pendientes</option>
                        <option value="2" <?php if($filter == 'En espera'){ echo 'selected'; } ?>>Ver en espera</option>
                        <option value="3" <?php if($filter == 'Completado'){ echo 'selected'; } ?>>Ver completados</option>
                        <option value="4" <?php if($filter == 'Revisar'){ echo 'selected'; } ?>>Ver para revisar</option>
                        <option value="5" <?php if($filter == 'Subcontratado'){ echo 'selected'; } ?>>Ver subcontratados</option>
                        <option value="6" <?php if($filter == 'Deshabilitado'){ echo 'selected'; } ?>>Ver deshabilitado</option>
					</select>
				</div>
                <br>
				<div class="row g-4">
                    <?php $filtro_orden = (isset($_GET['filtro_orden']) ? strtolower($_GET['filtro_orden']) : NULL);  ?>
                    <?php $check_ocultar_completados = (isset($_GET['check_ocultar_completados']) ? strtolower($_GET['check_ocultar_completados']) : NULL);  ?>
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_fecha" value = 'ordenar_por_fecha' onchange="form.submit()"
                            <?php if($filtro_orden == 'ordenar_por_fecha'){ echo 'checked'; } ?>>
							<label class="form-check-label" for="filtro_orden_fecha">
								Ordenar por fecha
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_monitor" value = 'ordenar_por_codigo' onchange="form.submit()"
                            <?php if($filtro_orden == 'ordenar_por_codigo'){ echo 'checked'; } ?>>
							<label class="form-check-label" for="filtro_orden_evento">
								Ordenar por codigo de evento
							</label>
						</div>
					</div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input type="checkbox" id="check_ocultar_completados" name="check_ocultar_completados" class="form-check-input" value="true" onchange="form.submit()"
                            <?php if($check_ocultar_completados == true){ echo 'checked'; } ?>>
                            <label for="check_ocultar_completados" class="form-check-label">Ocultar eventos completados</label>
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
					<th>Nombre evento</th>
					<th>Fecha y Hora Inicio</th>
                    <th>Fecha y Hora Final</th>
                    <th>Dirección evento</th>
					<th>Población y Provincia</th>
                    <th>Monitores Asignados</th>
                    <th>Actividades Asignadas</th>
                    <th>Código Cliente</th>
                    <th>Estado</th>
				</tr>
				<?php
				if(isset($_GET['buscar'])){
					$buscar = $_GET['buscar'];
					$sql = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo like '$buscar'
						OR codigo_cliente like '%$buscar%'
						OR nombre_evento like '%$buscar%'
						OR fecha_inicio_evento like '%$buscar%'
						OR codigo_coordinador like '%$buscar%'
						OR observaciones like '%$buscar%'
						OR poblacion like '%$buscar%'");
				}else{

                    if (isset($check_ocultar_completados) == true){
                        $sql = "SELECT * FROM eventos WHERE estado BETWEEN 1 AND 2 OR estado BETWEEN 4 AND 5";
                    }elseif($filter == 0){
                        $sql = "SELECT * FROM eventos WHERE estado <> 6";
                    }elseif($filter <> NULL){
                        $sql ="SELECT * FROM eventos WHERE estado='$filter'";
                    }else{
                        $sql = "SELECT * FROM eventos WHERE estado <> 6";
                    }
                    // $sql = $sql . " ORDER BY codigo ASC;";
                    if($filtro_orden == 'ordenar_por_fecha'){
						$sql = $sql . " ORDER BY fecha_inicio_evento ASC;";
					}elseif ($filtro_orden == 'ordenar_por_codigo'){
						$sql = $sql . " ORDER BY codigo ASC;";
					}
                    
					$sql = mysqli_query($conexion, $sql);
                }

				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="11">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
                        $codigo_evento = $row['codigo'];                       
                        echo '
						<tr>
							<td>'.$no.'</td>
							<td><a href="../profiles/profiles_eventos.php?codigo='.$codigo_evento.'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</a></td>
							<td><a href="../profiles/profiles_eventos.php?codigo='.$codigo_evento.'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row['nombre_evento'].'</a></td>
                            <td>'.$row['fecha_inicio_evento'].'  '.$row['horainicio'].'</td>
                            <td>'.$row['fecha_final_evento'].'  '.$row['horafinal'].'</td>
                            <td>'.$row['direccion_evento'].'</td>
                            <td>'.$row['poblacion'].' - '.$row['provincia'].'</td>';

                            // Contar cantidad de monitores asignados
                            $sql_cantidad_monitores = mysqli_query($conexion, "SELECT * FROM eventos_monitores WHERE codigo_eventos = '$codigo_evento'");
                            $cantidad_monitores = mysqli_num_rows($sql_cantidad_monitores);
                        echo '
							<td><a href="../edit/edit_eventos2.php?codigo='.$codigo_evento.'&fecha_evento='.$row['fecha_inicio_evento'].'" title="Editar monitores del evento">'.$cantidad_monitores.'</td>';
                        
                            // Contar cantidad de actividades asignadas
                            $sql_cantidad_actividades = mysqli_query($conexion, "SELECT * FROM eventos_actividades WHERE codigo_eventos = '$codigo_evento'");
                            $cantidad_actividades = mysqli_num_rows($sql_cantidad_actividades);
                        echo '
							<td><a href="../edit/edit_eventos3.php?codigo='.$codigo_evento.'" title="Editar actividades del evento">'.$cantidad_actividades.'</td>';

                        echo '
							<td><a href="../profiles/profiles_clientes.php?codigo='.$row['codigo_cliente'].'" title="Ver perfil cliente del evento" target="_blank" rel="noopener noreferrer">'.$row['codigo_cliente'].'</a></td>';

                        echo '<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Pendiente</span>';
							}                            
                            else if ($row['estado'] == '2' ){
								echo '<span class="label label-warning">En espera</span>';
							}                            
                            else if ($row['estado'] == '3' ){
								echo '<span class="label label-warning">Completado</span>';
							}
                            else if ($row['estado'] == '4' ){
								echo '<span class="label label-warning">Revisar</span>';
							}
                            else if ($row['estado'] == '5' ){
								echo '<span class="label label-info">Subcontratado</span>';
							}
                            else if ($row['estado'] == '6' ){
                                echo '<span class="label label-warning">Deshabilitado</span>';
                            }
						echo '
							</td>';

                            if ($_SESSION ['permisos'] > 1){
                            echo '
                            <td>
                                <a href="../edit/edit_eventos.php?codigo='.$row['codigo'].'" title="Editar datos" class="btn btn-warning btn-sm"><i class="material-icons">edit</i></a>
                                <a href="../edit/edit_eventos2.php?codigo='.$row['codigo'].'&fecha_evento='.$row['fecha_inicio_evento'].'" title="Editar Trabajadores" class="btn btn-primary btn-sm"><i class="material-icons">group</i></a>
                                <a href="../edit/edit_eventos3.php?codigo='.$row['codigo'].'" title="Editar Actividades" class="btn btn-primary btn-sm"><i class="material-icons">golf_course</i></a>
                                <a href="list_eventos.php?funcion=disabled&codigo='.$row['codigo'].'" title="Deshabilitar" onclick="return confirm(\'¿Esta seguro de deshabilitar los datos del evento '.$row['nombre_evento'].' en la localidad de '.$row['poblacion'].' con código '.$row['codigo'].'?\')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></span></a>
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