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

        <!-- Rango de fechas -->
		<div class="content">
            <a href="#" class="scrollup">Scroll</a>
            <!-- <button onclick="history.back()" name="volver atrás">Volver atrás</button> -->
            <h2>Busqueda de monitores por fechas de eventos</h2>
			<hr>
            <form class="form-floating" role="search" action="" method="$_GET">
                <div class="row g-4">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date" id="input_desde_fecha" name="desde_fecha" class="form-control" placeholder="Fecha de Inicio de los eventos" required>
                            <label for="input_desde_fecha">Desde la fecha:</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date" id="input_hasta_fecha" name="hasta_fecha" class="form-control" placeholder="Fecha de Inicio del evento" required>
                            <label for="input_hasta_fecha">Hasta la fecha:</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-floating">
                            <button class="btn btn-outline-success btn-lg" type="submit">Buscar</button>
                        </div>
                    </div>
                </div>
				<br>
				<div class="row g-4">
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_fecha" value = 'ordenar_por_fecha'>
							<label class="form-check-label" for="filtro_orden_fecha">
								Ordenar por fecha
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="filtro_orden" id="filtro_orden_monitor" value = 'ordenar_por_monitor' checked>
							<label class="form-check-label" for="filtro_orden_monitor">
								Ordenar por monitor
							</label>
						</div>
					</div>
				</div>
            </form>
            <br>
            <hr>
        </div>


        <!-- Listado de monitores -->
        <?php
            if (isset($_GET['desde_fecha'])){$desde_fecha = $_GET['desde_fecha'];}
            if (isset($_GET['hasta_fecha'])){$hasta_fecha = $_GET['hasta_fecha'];}
			if (isset($_GET['filtro_orden'])){$filtro_orden = $_GET['filtro_orden'];}
        ?>

		<div class="content">
            <a href="#" class="scrollup">Scroll</a>
			<h2>Lista de monitores por fechas de eventos</h2>
			<hr>
 
			<!-- Tabla de monitores -->
			<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<tr>
                    <th>No</th>
					<th>Código Monitor</th>
					<th>Nombre completo</th>
                    <th>Población</th>
					<th>Teléfono</th>
					<th>Código Evento</th>
                    <th>Nombre Evento</th>
                    <th>Fecha Inicio Evento</th>
					<th>Lugar Evento</th>
                    <th>Estado Evento</th>
				</tr>

				<?php

				// Consulta de datos
                $consulta_realizada = false;
                if (isset($_GET['desde_fecha']) && isset($_GET['desde_fecha'])){
                    $consulta_realizada = true;

					$sql_monitores = "SELECT codigo, nombre, apellidos, poblacion, telefono, mail,
					codigo_eventos, fecha_inicio_evento FROM eventos_monitores e, monitores m 
					WHERE codigo = codigo_monitores AND
					fecha_inicio_evento BETWEEN '$desde_fecha' AND '$hasta_fecha'";
					if ($filtro_orden == 'ordenar_por_monitor'){
						$sql_monitores = $sql_monitores . " ORDER BY codigo_monitores ASC;";
					}else{
						$sql_monitores = $sql_monitores . " ORDER BY fecha_inicio_evento ASC;";
					}
                    
					$sql_monitores = mysqli_query($conexion, $sql_monitores);
                }
				
                if ($consulta_realizada == false){
                    echo '<tr><td colspan="10">Consulta sin realizar</td></tr>';
				}elseif(mysqli_num_rows($sql_monitores) == 0){
					echo '<tr><td colspan="10">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row_monitor = mysqli_fetch_assoc($sql_monitores)){
						$codigo_evento = $row_monitor['codigo_eventos'];
						$sql_evento = mysqli_query($conexion, "SELECT * FROM eventos 
						WHERE codigo = $codigo_evento");
						$row_evento = mysqli_fetch_assoc($sql_evento);

                        echo '
						<tr>
							<td>'.$no.'</td>
							<td><a href="../profiles/profiles_monitores.php?codigo='.$row_monitor['codigo'].'" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer">'.$row_monitor['codigo'].'</a></td>
							<td><a href="../profiles/profiles_monitores.php?codigo='.$row_monitor['codigo'].'" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer"><i class="material-icons">assignment_ind</i> '.$row_monitor['nombre'].' '.$row_monitor['apellidos'].'</a></td>
                            <td>'.$row_monitor['poblacion'].'</td>
							<td>'.$row_monitor['telefono'].'</td>';

						echo '<td><a href="../profiles/profiles_eventos.php?codigo='.$row_evento['codigo'].'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row_evento['codigo'].'</td>
							<td><a href="../profiles/profiles_eventos.php?codigo='.$row_evento['codigo'].'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row_evento['nombre_evento'].'</td>
							<td>'.$row_evento['fecha_inicio_evento'].'</td>
							<td>'.$row_evento['poblacion'].'</td>';

                        echo '<td>';
							if($row_evento['estado'] == '1'){
								echo '<span class="label label-info">Pendiente</span>';
							}
                            else if ($row_evento['estado'] == '2' ){
								echo '<span class="label label-info">En espera</span>';
							}
                            else if ($row_evento['estado'] == '3' ){
								echo '<span class="label label-success">Completado</span>';
							}
                            else if ($row_evento['estado'] == '4' ){
								echo '<span class="label label-warning">En revisión</span>';
							}
                            else if ($row_evento['estado'] == '5' ){
								echo '<span class="label label-info">Subcontratado</span>';
							}
                            else if ($row_evento['estado'] == '6' ){
                                echo '<span class="label label-warning">Deshabilitado</span>';
                            }
						echo '
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