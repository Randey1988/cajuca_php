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
	<title>Informe de eventos por fechas</title>
 
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

        <!-- Rango de fechas -->
        <div class="content">
            <a href="#" class="scrollup">Scroll</a>
            <!-- <button onclick="history.back()" name="volver atrás">Volver atrás</button> -->
            <h2>Busqueda de eventos por fechas</h2>
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
            </form>
            <br>
            <hr>
        </div>

        <!-- Listado de eventos -->
        <?php
            if (isset($_GET['desde_fecha'])){$desde_fecha = $_GET['desde_fecha'];}
            if (isset($_GET['hasta_fecha'])){$hasta_fecha = $_GET['hasta_fecha'];}
        ?>

		<div class="content">
            <a href="#" class="scrollup">Scroll</a>
			<h2>Lista de eventos por fechas </h2>
			<hr>
 
            <!-- Tabla de eventos -->
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
				
                $consulta_realizada = false;
                if (isset($_GET['desde_fecha']) && isset($_GET['desde_fecha'])){
                    $consulta_realizada = true;
                    $sql = mysqli_query($conexion, "SELECT * FROM eventos WHERE estado <> 6 AND 
                    fecha_inicio_evento BETWEEN '$desde_fecha' AND '$hasta_fecha' ORDER BY fecha_inicio_evento ASC");

                    // $sql = mysqli_query($conexion, "SELECT * FROM eventos WHERE estado <> 6 AND 
                    // fecha_inicio_evento BETWEEN '$desde_fecha' AND '$hasta_fecha' ORDER BY fecha_inicio_evento ASC AND codigo ASC"); 
                }
				
                if ($consulta_realizada == false){
                    echo '<tr><td colspan="11">Consulta sin realizar</td></tr>';
				}elseif(mysqli_num_rows($sql) == 0){
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
							<td><a href="../edit/edit_eventos2.php?codigo='.$codigo_evento.'&fecha_evento='.$row['fecha_inicio_evento'].'" title="Editar monitores del evento" target="_blank" rel="noopener noreferrer">'.$cantidad_monitores.'</td>';
                        
                            // Contar cantidad de actividades asignadas
                            $sql_cantidad_actividades = mysqli_query($conexion, "SELECT * FROM eventos_actividades WHERE codigo_eventos = '$codigo_evento'");
                            $cantidad_actividades = mysqli_num_rows($sql_cantidad_actividades);
                        echo '
							<td><a href="../edit/edit_eventos3.php?codigo='.$codigo_evento.'" title="Editar actividades del evento" target="_blank" rel="noopener noreferrer">'.$cantidad_actividades.'</td>';

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
                                echo '<span class="label label-warning"></span>';
                            }
						echo '
							</td>
							<td>
								<a href="../edit/edit_eventos.php?codigo='.$row['codigo'].'" title="Editar datos" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer"><i class="material-icons">edit</i></a>
								<a href="../edit/edit_eventos2.php?codigo='.$row['codigo'].'&fecha_evento='.$row['fecha_inicio_evento'].'" title="Editar Trabajadores" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer"><i class="material-icons">group</i></a>
								<a href="../edit/edit_eventos3.php?codigo='.$row['codigo'].'" title="Editar Actividades" class="btn btn-primary btn-sm" target="_blank" rel="noopener noreferrer"><i class="material-icons">golf_course</i></a>
                                <a href="list_eventos.php?funcion=delete&codigo='.$row['codigo'].'" title="Eliminar" onclick="return confirm(\'Esta seguro de suprimir los datos del evento '.$row['nombre_evento'].' en la localidad de '.$row['poblacion'].' con código '.$row['codigo'].'?\')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></span></a>
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