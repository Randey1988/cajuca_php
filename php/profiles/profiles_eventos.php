<?php
	// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
	session_start();
	include("../../include/conexion.php");
	include_once ("../../include/session.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Perfil del Evento</title>
 
	<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="../../css/style.css" rel="stylesheet">
	
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
		<?php
			// escaping, additionally removing everything that could be (html/javascript-) code
			$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
			$db_query = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo='$codigo'");
			if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_eventos.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>

			<h2>Datos de los eventos &raquo; Perfil <?php echo $row['codigo'].' - '.$row['nombre_evento']; ?></h2>
			<hr>
			
			<!-- Botones de accion -->
			<a href="../list/list_eventos.php" class="btn btn-sm btn-secondary"><i class="material-icons">keyboard_backspace</i> Regresar</a>
			<a href="../tools/informe_eventos.php?codigo_evento=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-info" target="_blank" rel="noopener noreferrer"><i class="material-icons">print</i> Informe Completo</a>
			<a href="../edit/edit_eventos.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-warning"><i class="material-icons">edit</i> Editar datos</a>
			<a href="profiles_eventos.php?funcion=delete&codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de ocultar los datos del evento <?php echo $row['codigo'].' - '.$row['nombre_evento']; ?>')"><i class="material-icons">delete</i> Eliminar</a>
			
			
			<hr>
			
			<!-- Funcion Suprimir -->
			<?php
			if(isset($_GET['funcion']) == 'delete'){
				$delete = mysqli_query($con, "DELETE FROM clientes WHERE codigo='$codigo'");
				if($delete){
					echo '<div class="alert alert-danger alert-dismissable">><button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>Data berhasil dihapus.</div>';
				}else{
					echo '<div class="alert alert-info alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Data gagal dihapus.</div>';
				}
			}
			?>
			<!-- Profile -->
			<table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="2"><h3>DATOS DE EVENTO</h3></td>
                </tr>	
                <tr>
					<th width="30%">Código Evento</th>
					<td><?php echo $codigo; ?></td>
				</tr>
                <tr>
					<th>Estado</th>
					<td>
						<?php 

							if ($row['estado']==1) {
								echo "Pendiente";
							} else if ($row['estado']==2){
								echo "En espera";
							} else if ($row['estado']==3){
								echo "Completado";
							} else if ($row['estado']==4){
								echo "En revisión";
							} else if ($row['estado']==5){
								echo "Subcontratado";
							} else if ($row['estado']==6){
								echo "Deshabilitado";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>Código del Cliente del Evento</th>
					<td><a href="../profiles/profiles_clientes.php?codigo=<?php echo $row['codigo_cliente'];?>" title="Ver perfil del cliente" target="_blank" rel="noopener noreferrer"><?php echo $row['codigo_cliente']; ?></td>
				</tr>
                <tr>
					<th>Código del Coordinador del Evento</th>
					<td><?php echo $row['codigo_coordinador']; ?></td>
					<!-- <td><a href="../profiles/profiles_monitor.php?codigo=<?php //echo $row['codigo_cliente'];?>" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer"><?php //echo $row['codigo_coordinador']; ?></td> -->
				</tr>
				<tr>
					<th>Nombre del Evento</th>
					<td><?php echo $row['nombre_evento']; ?></td>
				</tr>
				<tr>
					<th>Descripción del Evento</th>
					<td><?php echo $row['descripcion_evento']; ?></td>
				</tr>
			</table>
            <hr>
			<br>
            <table class="table table-striped table-condensed">
                <tr>
                    <td colspan="2"><h3>PLANIFICACIÓN DEL EVENTO</h3></td>
                </tr>
				<tr>
					<th width="30%">Lugar del Evento</th>
					<td><?php echo $row['direccion_evento']; ?></td>
				</tr>
                <tr>
					<th>Código Postal</th>
					<td><?php echo $row['cpostal']; ?></td>
				</tr>
                <tr>
					<th>Población y Provincia</th>
					<td><?php echo $row['poblacion'].' - '.$row['provincia']; ?></td>
				</tr>
                <tr>
					<th>Fecha y Hora del Inicio del Evento</th>
					<td><?php echo $row['fecha_inicio_evento'].' - '.$row['horainicio']; ?></td>
				</tr>
				<tr>
					<th>Fecha y Hora del Final del Evento</th>
					<td><?php echo $row['fecha_final_evento'].' - '.$row['horafinal']; ?></td>
				</tr>
                <tr>
					<th>Observaciones</th>
					<td><?php echo $row['observaciones']; ?></td>
				</tr>
            </table>
            <hr>
            <table class="table table-striped table-condensed">
                <tr>
                    <td colspan="2"><h3>DESGLOSE DE TIEMPOS DEL EVENTO</h3></td>
                </tr>
                <tr>
					<th width="30%">Tiempo Trabajadas</th>
					<td><?php echo $row['horas_trabajadas']; ?> horas</td>
				</tr>
                <tr>
					<th>Tiempo de Desplazamiento</th>
					<td><?php echo $row['horas_desplazamiento']; ?> horas</td>
				</tr>
				<tr>
					<th>Tiempo de Montaje</th>
					<td><?php echo $row['horas_montaje']; ?> horas</td>
				</tr>
				<tr>
					<th>Tiempo de Desmontaje</th>
					<td><?php echo $row['horas_desmontaje']; ?> horas</td>
				</tr>
			</table>
			<br>
			<hr>

								
			<!-- Tabla de monitores registrados de la actividad -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="8"><h3>MONITORES ASIGNADOS</h3></td>
					<td><a href="../edit/edit_eventos2.php?codigo=<?php echo $row['codigo'].'&fecha_evento='.$row['fecha_inicio_evento']; ?>" title="Editar Trabajadores" class="btn btn-sm btn-success" 
						target="_blank" rel="noopener noreferrer"><i class="material-icons">edit</i> Editar Trabajadores</a></td>
				</tr>
                <?php
                    $db_eventos_monitores = mysqli_query($conexion, "SELECT * FROM eventos_monitores WHERE codigo_eventos = '$codigo' ORDER BY codigo_monitores ASC");
                    if(mysqli_num_rows($db_eventos_monitores) == 0){
                        echo '<tr><td colspan="9">No hay datos.</td></tr>';
                    }else{
                        $no = 1;

						echo '
                        <tr>
                            <th>No</th>
                            <th>Código</th>
                            <th>DNI - NIF</th>
                            <th>Nombre Completo</th>
                            <th>Fecha Nacimiento</th>
                            <th>Población</th>
							<th>Título de Monitor</th>
                            <th>Título de Coordinador</th>
                            <th>Otros Títulos</th>
                        </tr>';

                        while($row_eventos_monitores = mysqli_fetch_assoc($db_eventos_monitores)){
                            $codigo_monitores = $row_eventos_monitores['codigo_monitores'];
                            $sql_monitores = mysqli_query($conexion, "SELECT DNI_NIF, nombre, apellidos, fecha_nac, poblacion, tit_monitor, tit_coordinador, tit_otros  FROM monitores WHERE codigo = '$codigo_monitores' ORDER BY codigo ASC");
                            $row_monitores = mysqli_fetch_assoc($sql_monitores);
							// var_dump($codigo_monitores);
							echo '
                                <tr>
                                    <td>'.$no.'</td>
                                    <td><a href="../profiles/profiles_monitores.php?codigo='.$codigo_monitores.'" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer">'.$codigo_monitores.'</a></td>
                                    <td>'.$row_monitores['DNI_NIF'].'</td>
                                    <td>'.$row_monitores['nombre'].' '.$row_monitores['apellidos'].'</td>
                                    <td>'.$row_monitores['fecha_nac'].'</td>
									<td>'.$row_monitores['poblacion'].'</td>
									<td>';

									if ($row_monitores['tit_monitor'] == true) {
										echo "Si";
									} else {
										echo "No";
									}
								echo '
									</td>
									<td>';
									if ($row_monitores['tit_coordinador'] == true) {
										echo "Si";
									} else {
										echo "No";
									}
								echo '
									</td>
                                    <td>'.$row_monitores['tit_otros'].'</td>
                                </tr>';
                            $no++;
                        }
                    }
                ?>
            </table>                    
			<br>
			<hr>

			<!-- Tabla de actividades registrados de la actividad -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="6"><h3>ACTIVIDADES ASIGNADAS</h3></td>
					<td colspan="2"><a href="../edit/edit_eventos3.php?codigo=<?php echo $row['codigo']; ?>" title="Editar Actividades" class="btn btn-sm btn-success"
					target="_blank" rel="noopener noreferrer"><i class="material-icons">edit</i> Editar Actividades</a></td>
                </tr>
                <?php
                    $db_eventos_actividades = mysqli_query($conexion, "SELECT * FROM eventos_actividades WHERE codigo_eventos = '$codigo' ORDER BY codigo_actividades ASC");
                    if(mysqli_num_rows($db_eventos_actividades) == 0){
                        echo '<tr><td colspan="8">No hay datos.</td></tr>';
                    }else{
                        $no = 1;

						echo '
                        <tr>
							<th>No</th>
							<th>Código</th>
							<th>Tipo y Subtipo Actividad</th>
							<th>Nombre</th>
							<th>Descripcion</th>
							<th>Cantidad de Actividades</th>
							<th>Precio € / h</th>
							<th>Jugadores</th>
							
                        </tr>';

                        while($row_eventos_actividades = mysqli_fetch_assoc($db_eventos_actividades)){
                            $codigo_actividades = $row_eventos_actividades['codigo_actividades'];
							$cantidad_actividades = $row_eventos_actividades['cantidad_actividades'];
                            $sql_actividades = mysqli_query($conexion, "SELECT tipo, subtipo, nombre, descripcion, precio_hora, jugadores, estado FROM actividades WHERE codigo = '$codigo_actividades' ORDER BY codigo ASC");
							$row_actividades = mysqli_fetch_assoc($sql_actividades);
							
							echo '
                                <tr>
                                    <td>'.$no.'</td>
                                    <td><a href="../profiles/profiles_actividades.php?codigo='.$codigo_actividades.'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer">'.$codigo_actividades.'</a></td>
                                    <td>'.$row_actividades['tipo'].' - '.$row_actividades['subtipo'].'</td>
                                    <td>'.$row_actividades['nombre'].'</td>
									<td>'.$row_actividades['descripcion'].'</td>
									<td>'.$cantidad_actividades.'</td>
									<td>'.$row_actividades['precio_hora'].'</td>
									<td>'.$row_actividades['jugadores'].'</td>
                                </tr>';
                            $no++;
                        }
                    }
                ?>
            </table>
			<br>
		</div>
	</div>
 	<br>

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