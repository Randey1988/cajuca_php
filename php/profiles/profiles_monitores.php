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
	<title>Perfil del Monitor</title>
 
	<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	
	<!-- MaterializeCSS Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

	<!-- StyleCSS -->
	<link href="../../css/style.css" rel="stylesheet">

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
			$db_query = mysqli_query($conexion, "SELECT * FROM monitores WHERE codigo='$codigo'");
			if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_monitores.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>

			<h2>Datos de los monitores &raquo; Perfil de <?php echo $row['nombre'].' '.$row['apellidos']; ?></h2>
			<hr>
			
			<!-- Botones de accion -->
			<a href="../list/list_monitores.php" class="btn btn-sm btn-secondary"><i class="material-icons">keyboard_backspace</i> Regresar</a>
			<a href="../edit/edit_monitores.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-warning"><i class="material-icons">edit</i> Editar datos</a>
			<a href="profiles_monitores.php?funcion=delete&codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de ocultar los datos del monitor <?php echo $row['nombre'].' '.$row['apellidos']; ?>')"><i class="material-icons">delete</i> Eliminar</a>
			<hr>

			<?php
			if(isset($_GET['funcion']) == 'delete'){
				$delete = mysqli_query($con, "DELETE FROM monitores WHERE codigo='$codigo'");
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
					<th width="30%">Código</th>
					<td><?php echo $row['codigo']; ?></td>
				</tr>
                <tr>
					<th>Estado</th>
					<td>
						<?php 
							if ($row['estado']==1) {
								echo "Puesto Fijo";
							} else if ($row['estado']==2){
								echo "Ocupado";
							} else if ($row['estado']==3){
								echo "Libre";
							} else if ($row['estado']==4){
								echo "En Revisión";
							} else if ($row['estado']==5){
								echo "Temporal";
							} else if ($row['estado']==6){
								echo "Deshabilitado";
							}
						?>
					</td>
				</tr>
				<tr>
					<th>DNI - NIF</th>
					<td><?php echo $row['DNI_NIF']; ?></td>
				</tr>
				<tr>
					<th>Nombre y apellidos del monitor</th>
					<td><?php echo $row['nombre'].' '.$row['apellidos']; ?></td>
				</tr>
				<tr>
					<th>Fecha de Nacimiento</th>
					<td><?php echo $row['fecha_nac']; ?></td>
				</tr>
				<tr>
					<th>Dirección</th>
					<td><?php echo $row['direccion']; ?></td>
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
					<th>Email</th>
					<td><?php echo $row['mail']; ?></td>
				</tr>
				<tr>
					<th>Teléfono</th>
					<td><?php echo $row['telefono']; ?></td>
				</tr>
                <tr>
					<th>Número Seguridad Social</th>
					<td><?php echo $row['nsegsocial']; ?></td>
				</tr>
                <tr>
					<th>Cuenta Bancaria</th>
					<td><?php echo $row['cuenta']; ?></td>
				</tr>
                <tr>
					<th>Título de Monitor</th>
					<td>
						<?php 
							if ($row['tit_monitor']==true) {
								echo "Si";
							} else {
								echo "No";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>Título de Coordinador</th>
					<td>
                        <?php 
							if ($row['tit_coordinador']==true) {
								echo "Si";
							} else {
								echo "No";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>Otros Títulos</th>
					<td><?php echo $row['tit_otros']; ?></td>
				</tr>
				<tr>
					<th>Observaciones</th>
					<td><?php echo $row['observaciones']; ?></td>
				</tr>
				
			</table>
			<hr>
			<br>

			<!-- Tabla de eventos del monitor -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="7"><h3>EVENTOS DEL MONITOR</h3></td>
                </tr>
                <?php
					$db_eventos_monitores = mysqli_query($conexion, "SELECT codigo_eventos FROM eventos_monitores WHERE codigo_monitores = $codigo ORDER BY fecha_inicio_evento ASC");
                    // $db_eventos = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo_cliente = $codigo ORDER BY fecha_inicio_evento ASC");
                    if(mysqli_num_rows($db_eventos_monitores) == 0){
                        echo '<tr><td colspan="7">No hay datos.</td></tr>';
                    }else{
                        $no = 1;


                    echo '
                        <tr>
							<th>No</th>
							<th>Código</th>
							<th>Nombre evento</th>
							<th>Fecha y Hora Inicio</th>
							<th>Fecha y Hora Final</th>
							<th>Población y Provincia</th>
							<th>Estado</th>
                        </tr>';

                        while($row_eventos_monitores = mysqli_fetch_assoc($db_eventos_monitores)){
							$codigo_evento_del_monitor = $row_eventos_monitores['codigo_eventos'];
							$db_eventos = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo = '$codigo_evento_del_monitor'");
							$row_eventos = mysqli_fetch_assoc($db_eventos);
							echo '
							<tr>
								<td>'.$no.'</td>
								<td><a href="../profiles/profiles_eventos.php?codigo='.$row_eventos['codigo'].'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row_eventos['codigo'].'</a></td>
								<td><a href="../profiles/profiles_eventos.php?codigo='.$row_eventos['codigo'].'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row_eventos['nombre_evento'].'</a></td>
								<td>'.$row_eventos['fecha_inicio_evento'].'  '.$row_eventos['horainicio'].'</td>
								<td>'.$row_eventos['fecha_final_evento'].'  '.$row_eventos['horafinal'].'</td>
								<td>'.$row_eventos['poblacion'].' - '.$row_eventos['provincia'].'</td>';

								echo '
								<td>';

								if($row_eventos['estado'] == '1'){
									echo '<span class="label label-success">Pendiente</span>';
								}                            
								else if ($row_eventos['estado'] == '2' ){
									echo '<span class="label label-warning">En espera</span>';
								}                            
								else if ($row_eventos['estado'] == '3' ){
									echo '<span class="label label-warning">Completado</span>';
								}
								else if ($row_eventos['estado'] == '4' ){
									echo '<span class="label label-warning">Revisar</span>';
								}
								else if ($row_eventos['estado'] == '5' ){
									echo '<span class="label label-info">Subcontratado</span>';
								}
								else if ($row_eventos['estado'] == '6' ){
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
			<hr>
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