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
	<title>Perfil del Cliente</title>
 
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
			$db_query = mysqli_query($conexion, "SELECT * FROM clientes WHERE codigo='$codigo'");
			if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_clientes.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>

			<h2>Datos de los Clientes &raquo; Perfil de <?php echo $row['nombre'].' '.$row['apellidos']; ?></h2>
			<hr>

			<!-- Botones de accion -->
			<a href="../list/list_clientes.php" class="btn btn-sm btn-secondary"><i class="material-icons">keyboard_backspace</i> Regresar</a>
			<a href="../edit/edit_clientes.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-warning"><i class="material-icons">edit</i> Editar datos</a>
			<a href="profiles_clientes.php?funcion=delete&codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de ocultar los datos del cliente <?php echo $row['nombre'].' '.$row['apellidos']; ?>')"><i class="material-icons">delete</i> Eliminar</a>
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
                    <td colspan="2"><h3>DATOS DE CLIENTE</h3></td>
                </tr>	
                <tr>
					<th width="30%">Código Cliente</th>
					<td><?php echo $row['codigo']; ?></td>
				</tr>
                <tr>
					<th>Estado</th>
					<td>
						<?php 
							if ($row['estado']==1) {
								echo "Activo";
							} else if ($row['estado']==2){
								echo "Inactivo";	
							} else if ($row['estado']==4){
								echo "En revisión";
							} else if ($row['estado']==5){
								echo "Subcontrata";
							} else if ($row['estado']==6){
								echo "Deshabilitado";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>NIF - CIF</th>
					<td><?php echo $row['NIF_CIF']; ?></td>
				</tr>
                <tr>
					<th>Razon Social de la empresa</th>
					<td><?php echo $row['RazonSocial']; ?></td>
				</tr>
				<tr>
					<th>Nombre y apellidos del monitor</th>
					<td><?php echo $row['nombre'].' '.$row['apellidos']; ?></td>
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
					<th>Observaciones</th>
					<td><?php echo $row['observaciones']; ?></td>
				</tr>
            </table>
            <hr>
			<br>
            <table class="table table-striped table-condensed">
                <tr>
                    <td colspan="2"><h3>DATOS DE FACTURACIÓN</h3></td>
                </tr>
                <tr>
					<th width="30%">NIF - CIF</th>
					<td><?php echo $row['NIF_CIF_fac']; ?></td>
				</tr>
                <tr>
					<th>Razon Social de la empresa</th>
					<td><?php echo $row['RazonSocial_fac']; ?></td>
				</tr>
				<tr>
					<th>Nombre y apellidos del monitor</th>
					<td><?php echo $row['nombrecompleto_fac']; ?></td>
				</tr>
				<tr>
					<th>Dirección</th>
					<td><?php echo $row['direccion_fac']; ?></td>
				</tr>
                <tr>
					<th>Código Postal</th>
					<td><?php echo $row['cpostal_fac']; ?></td>
				</tr>
                <tr>
					<th>Población y Provincia</th>
					<td><?php echo $row['poblacion_fac'].' - '.$row['provincia_fac']; ?></td>
				</tr>
                <tr>
					<th>Email</th>
					<td><?php echo $row['mail_fac']; ?></td>
				</tr>				
			</table>
			<hr>
			<br>
			<!-- Tabla de eventos del cliente -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="7"><h3>EVENTOS DEL CLIENTE</h3></td>
                </tr>
                <?php
                    $db_eventos = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo_cliente = $codigo ORDER BY fecha_inicio_evento ASC");
                    if(mysqli_num_rows($db_eventos) == 0){
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

                        while($row_eventos = mysqli_fetch_assoc($db_eventos)){
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