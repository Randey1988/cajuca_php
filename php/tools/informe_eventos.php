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
	<title>Informe del Evento</title>
 
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
         <!-- Búsqueda y filtro -->
         <div class="content">
            <a href="#" class="scrollup">Scroll</a>
            <!-- <button onclick="history.back()" name="volver atrás">Volver atrás</button> -->
            <h2>Seleccione el evento </h2>
			<hr>
            <form class="form-floating" role="search" action="" method="$_GET">
                <div class="row g-4">

                    <div class="col-md-8">
                        <div class="form-floating">
                            <select class="form-select" id="input_codigo_evento" name="codigo_evento" aria-label="Selector de Eventos" required>
                                <option selected>Seleccione un evento</option>
                                <?php
                                    $selector_codigo_evento = (isset($_GET['codigo_evento']) ? strtolower($_GET['codigo_evento']) : NULL);
                                    $db_eventos = mysqli_query($conexion, "SELECT codigo, nombre_evento, poblacion, fecha_inicio_evento, estado FROM eventos WHERE estado = 1 OR estado = 2 OR estado = 5 ORDER BY fecha_inicio_evento ASC, nombre_evento ASC");
                                    while($rows = mysqli_fetch_assoc($db_eventos)){
                                        echo '<option value="'.$rows['codigo'].'"';
                                        if($selector_codigo_evento == $rows['codigo']){ echo 'selected'; };
                                        echo '>'.$rows['fecha_inicio_evento'].' | '.$rows['nombre_evento'].', en '.$rows['poblacion'];
                                        
                                        if ($rows ['estado']==1){echo " - Pendiente";}
                                        elseif ($rows ['estado']==2){echo " - En Espera";}
                                        elseif ($rows ['estado']==5){echo " - Subcontrata";}
							
                                        echo ' </option>';
                                    }
                                ?>
                            </select>
                            <label for="input_codigo_evento">Evento</label>
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

	    <div class="content">
		
            <?php
            // escaping, additionally removing everything that could be (html/javascript-) code
            
            // Consulta de datos
            $consulta_realizada = false;
            
            if (isset($_GET['codigo_evento'])){
                $consulta_realizada = true;
                $codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo_evento"],ENT_QUOTES)));
                $db_query = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo='$codigo'");
            }
            
			if ($consulta_realizada == false){
                echo '<h3>Consulta sin realizar</h3>';
            }elseif(mysqli_num_rows($db_query) == 0){
				echo '<h3>Datos del informe no encontrados</h3>';
                $consulta_realizada = false;
			}else{
				$row_eventos = mysqli_fetch_assoc($db_query);
                $consulta_realizada = true;
			}                
            
            // Esto solo permite que los que han realizado la consulta correctamente accedan
            if ($consulta_realizada != false){
            ?>
            
            <center><h2><b>INFORME COMPLETO DEL EVENTO</b></h2></center>
            <br>
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

							if ($row_eventos['estado']==1) {
								echo "Pendiente";
							} else if ($row_eventos['estado']==2){
								echo "En espera";
							} else if ($row_eventos['estado']==3){
								echo "Completado";
							} else if ($row_eventos['estado']==4){
								echo "Revisar";
							} else if ($row_eventos['estado']==5){
								echo "Subcontratado";
							} else if ($row_eventos['estado']==6){
								echo "Deshabilitado";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>Código del Cliente del Evento</th>
					<td><a href="../profiles/profiles_clientes.php?codigo=<?php echo $row_eventos['codigo_cliente'];?>" title="Ver perfil del cliente" target="_blank" rel="noopener noreferrer"><?php echo $row_eventos['codigo_cliente']; ?></td>
				</tr>
                <tr>
					<th>Código del Coordinador del Evento</th>
					<td><?php echo $row_eventos['codigo_coordinador']; ?></td>
					<!-- <td><a href="../profiles/profiles_monitor.php?codigo=<?php //echo $row_eventos['codigo_cliente'];?>" title="Ver perfil del monitor" target="_blank" rel="noopener noreferrer"><?php //echo $row_eventos['codigo_coordinador']; ?></td> -->
				</tr>
				<tr>
					<th>Nombre del Evento</th>
					<td><?php echo $row_eventos['nombre_evento']; ?></td>
				</tr>
				<tr>
					<th>Descripción del Evento</th>
					<td><?php echo $row_eventos['descripcion_evento']; ?></td>
				</tr>
			</table>
            <hr>
			<br>

            <!-- Tabla de la planificación -->
            <table class="table table-striped table-condensed">
                <tr>
                    <td colspan="2"><h3>PLANIFICACIÓN DEL EVENTO</h3></td>
                </tr>
				<tr>
					<th width="30%">Lugar del Evento</th>
					<td><?php echo $row_eventos['direccion_evento']; ?></td>
				</tr>
                <tr>
					<th>Código Postal</th>
					<td><?php echo $row_eventos['cpostal']; ?></td>
				</tr>
                <tr>
					<th>Población y Provincia</th>
					<td><?php echo $row_eventos['poblacion'].' - '.$row_eventos['provincia']; ?></td>
				</tr>
                <tr>
					<th>Fecha y Hora del Inicio del Evento</th>
					<td><?php echo $row_eventos['fecha_inicio_evento'].' - '.$row_eventos['horainicio']; ?></td>
				</tr>
				<tr>
					<th>Fecha y Hora del Final del Evento</th>
					<td><?php echo $row_eventos['fecha_final_evento'].' - '.$row_eventos['horafinal']; ?></td>
				</tr>
                <tr>
					<th>Observaciones</th>
					<td><?php echo $row_eventos['observaciones']; ?></td>
				</tr>
            </table>
            <hr>
            <br>

            <!-- Tabla de los tiempos del evento -->
            <table class="table table-striped table-condensed">
                <tr>
                    <td colspan="2"><h3>DESGLOSE DE TIEMPOS DEL EVENTO</h3></td>
                </tr>
                <tr>
					<th width="30%">Tiempo Trabajadas</th>
					<td><?php echo $row_eventos['horas_trabajadas']; ?> horas</td>
				</tr>
                <tr>
					<th>Tiempo de Desplazamiento</th>
					<td><?php echo $row_eventos['horas_desplazamiento']; ?> horas</td>
				</tr>
				<tr>
					<th>Tiempo de Montaje</th>
					<td><?php echo $row_eventos['horas_montaje']; ?> horas</td>
				</tr>
				<tr>
					<th>Tiempo de Desmontaje</th>
					<td><?php echo $row_eventos['horas_desmontaje']; ?> horas</td>
				</tr>
			</table>
			<hr>
			<br>
            <h2><b>DATOS DE LOS TRABAJADORES</b></h2>
            <br>

            <!-- Tabla de los datos del coordinador de la actividad -->
            <?php
                $codigo_coordinador = $row_eventos['codigo_coordinador'];
                $db_coordinador = mysqli_query($conexion, "SELECT * FROM monitores m, coordinadores c WHERE c.DNI_NIF = m.DNI_NIF AND c.codigo = $codigo_coordinador");
                $row_coordinador = mysqli_fetch_assoc($db_coordinador);
            ?>
            <table class="table table-striped table-condensed">
                <tr>
                    <td colspan="2"><h3>COORDINADOR ASIGNADO DEL EVENTO</h3></td>
                </tr>
                <tr>
					<th>DNI - NIF</th>
					<td><a href="../profiles/profiles_monitores.php?codigo=<?php echo $codigo_coordinador;?>" title="Ver perfil del coordinador" target="_blank" rel="noopener noreferrer"><?php echo $row_coordinador['DNI_NIF']; ?></a></td>
				</tr>
				<tr>
					<th>Nombre y apellidos del monitor</th>
					<td><a href="../profiles/profiles_monitores.php?codigo=<?php echo $codigo_coordinador;?>" title="Ver perfil del coordinador" target="_blank" rel="noopener noreferrer"><?php echo $row_coordinador['nombre'].' '.$row_coordinador['apellidos']; ?></a></td>
				</tr>
				<tr>
					<th>Fecha de Nacimiento</th>
					<td><?php echo $row_coordinador['fecha_nac']; ?></td>
				</tr>
                <tr>
					<th>Población y Provincia</th>
					<td><?php echo $row_coordinador['poblacion'].' - '.$row_coordinador['provincia']; ?></td>
				</tr>
                <tr>
					<th>Email</th>
					<td><?php echo $row_coordinador['mail']; ?></td>
				</tr>
				<tr>
					<th>Teléfono</th>
					<td><?php echo $row_coordinador['telefono']; ?></td>
				</tr>
                <tr>
					<th>Observaciones</th>
					<td><?php echo $row_coordinador['observaciones']; ?></td>
				</tr>
			</table>
			<hr>
			<br>
								
			<!-- Tabla de monitores registrados de la actividad -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="9"><h3>MONITORES ASIGNADOS</h3></td>
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
                            $sql_monitores = mysqli_query($conexion, "SELECT DNI_NIF, nombre, apellidos, fecha_nac, poblacion, tit_monitor, tit_coordinador, tit_otros  FROM monitores WHERE codigo = '$codigo_monitores' AND estado BETWEEN 1 AND 3 OR estado = 5 ORDER BY codigo ASC");
                            $row_monitores = mysqli_fetch_assoc($sql_monitores);
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
            <h2><b>LISTADO DE LAS ACTIVIDADES Y MATERIALES</b></h2>
            <br>
			<!-- Tabla de actividades registrados del evento -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="8"><h3>ACTIVIDADES ASIGNADAS</h3></td>
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

                        $codigos_actividades_del_evento = [];

                        while($row_eventos_actividades = mysqli_fetch_assoc($db_eventos_actividades)){
                            $codigo_actividades = $row_eventos_actividades['codigo_actividades'];
							$cantidad_actividades = $row_eventos_actividades['cantidad_actividades'];
                            $codigos_actividades_del_evento[] = $codigo_actividades;  //variable para usar en la tabla de materiales
                            
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
            <hr>

            <h2><b>LISTADOS DE LOS MATERIALES POR CADA ACTIVIDAD</b></h2>
            <br>
            <!-- Preparar una tabla por cada material del evento -->
            <?php
            if(isset($codigos_actividades_del_evento) == 0){
				echo 'No hay datos.';
            }else{
                foreach ($codigos_actividades_del_evento as $codigo_actividad_del_evento){
                    $db_actividades = mysqli_query($conexion, "SELECT * FROM actividades WHERE codigo = '$codigo_actividad_del_evento' ORDER BY codigo ASC");
                    $row_actividades = mysqli_fetch_assoc($db_actividades);
                    $db_materiales_actividades = mysqli_query($conexion, "SELECT * FROM materiales_actividades WHERE codigo_actividades = '$codigo_actividad_del_evento' ORDER BY codigo_actividades ASC");

            ?>
            
                        <!-- Tabla de materiales registrados del evento -->
                <table class="table table-striped table-condensed">
                    <tr>
                        <td colspan="7"><h3>Actividad con código <?php echo $codigo_actividad_del_evento.': '.$row_actividades['nombre']; ?></h3></td>
                    </tr>
                    <?php

                        if(mysqli_num_rows($db_materiales_actividades) == 0){
                            echo '<tr><td colspan="7">No hay datos.</td></tr>';
                        }else{

                            $no = 1;

                            echo '
                            <tr>
                                <th>No</th>
                                <th>Código</th>
                                <th>Tipo y Subtipo Material</th>
                                <th>Nombre</th>
                                <th>Características</th>
                                <th>Cantidad Material</th>
                                <th>Estado</th>
                            </tr>';

                            while($row_materiales_actividades = mysqli_fetch_assoc($db_materiales_actividades)){
                                $codigo_materiales = $row_materiales_actividades['codigo_materiales'];
                                $sql_materiales = mysqli_query($conexion, "SELECT tipo_material, subtipo_material, nombre, caracteristicas, estado FROM materiales WHERE codigo = '$codigo_materiales'");
                                $row_materiales = mysqli_fetch_assoc($sql_materiales);
                                
                                echo '
                                <tr>
                                    <td>'.$no.'</td>
                                    <td><a href="../profiles/profiles_materiales.php?codigo='.$row_materiales_actividades['codigo_materiales'].'" target="_blank" rel="noopener noreferrer">'.$row_materiales_actividades['codigo_materiales'].'</a></td>
                                    <td>'.$row_materiales['tipo_material'].' - '.$row_materiales['subtipo_material'].'</td>
                                    <td>'.$row_materiales['nombre'].'</td>
                                    <td>'.$row_materiales['caracteristicas'].'</td>
                                    <td>'.$row_materiales_actividades['cantidad_materiales'].'</td>
                                    ';

                                    echo '
                                    <td>';
                                    if($row_materiales['estado'] == '1'){
                                        echo '<span class="label label-success">En Almacén</span>';
                                    }
                                    else if ($row_materiales['estado'] == '2' ){
                                        echo '<span class="label label-info">Sin Stock</span>';
                                    }
                                    else if ($row_materiales['estado'] == '4' ){
                                        echo '<span class="label label-warning">En Revisión</span>';
                                    }
                                    else if ($row_materiales['estado'] == '5' ){
                                        echo '<span class="label label-info">Subcontratado</span>';
                                    }
                                    else if ($row_materiales['estado'] == '6' ){
                                        echo '<span class="label label-warning">Deshabilitado</span>';
                                    }
                                    
                                    echo '
                                    </td>
                                </tr>';
                                $no++;
                            }
                        }
                    ?>
                </table>
                <br>
            <?php }}} ?>
            <hr>
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