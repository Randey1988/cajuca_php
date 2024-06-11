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
	<title>Perfil del Material</title>
 
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
			$db_query = mysqli_query($conexion, "SELECT * FROM materiales WHERE codigo='$codigo'");
			if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_materiales.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>

			<h2>Datos de los Materiales &raquo; Perfil del Material <?php echo $row['codigo'].' - '.$row['nombre']; ?></h2>
			<hr>

			<!-- Botones de accion -->
            <a href="../list/list_materiales.php" class="btn btn-sm btn-secondary"><i class="material-icons">keyboard_backspace</i> Regresar</a>
			<a href="../edit/edit_materiales.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-warning"><i class="material-icons">edit</i> Editar datos</a>
			<a href="profiles_materiales.php?funcion=delete&codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de ocultar los datos del material <?php echo $row['nombre']; ?>')"><i class="material-icons">delete</i> Eliminar</a>
            <hr>

            <!-- Funcion Sumprimir -->
			<?php
			if(isset($_GET['funcion']) == 'delete'){
				$delete = mysqli_query($con, "DELETE FROM materiales WHERE codigo='$codigo'");
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
                    <td colspan="2"><h3>DATOS DEL MATERIAL</h3></td>
                </tr>	
                <tr>
					<th width="30%">Código Material</th>
					<td><?php echo $row['codigo']; ?></td>
				</tr>
                <tr>
					<th>Estado</th>
					<td>
						<?php 
							if ($row['estado']==1) {
								echo "En Almacén";
							} else if ($row['estado']==2){
								echo "Sin Stock";
							} else if ($row['estado']==4){
								echo "En Revisión";
							} else if ($row['estado']==5){
								echo "Subcontratado";
							} else if ($row['estado']==6){
								echo "Deshabilitado";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>Tipo de Material</th>
					<td><?php echo $row['tipo_material']; ?></td>
				</tr>
                <tr>
					<th>Subtipo de Material</th>
					<td><?php echo $row['subtipo_material']; ?></td>
				</tr>
				<tr>
					<th>Nombre del artículo</th>
					<td><?php echo $row['nombre']; ?></td>
				</tr>
				<tr>
					<th>Caracteristicas del Artículo</th>
					<td><?php echo $row['caracteristicas']; ?></td>
				</tr>
                <tr>
					<th>Observaciones</th>
					<td><?php echo $row['observaciones']; ?></td>
				</tr>
            </table>
			<hr>
			<br>

			<!-- Tabla de actividades que usan el material -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="7"><h3>ACTIVIDADES CON EL MATERIAL</h3></td>
                </tr>
                <?php
					$db_materiales_actividades = mysqli_query($conexion, "SELECT codigo_actividades FROM materiales_actividades WHERE codigo_materiales = $codigo ORDER BY codigo_actividades ASC");
                    
                    if(mysqli_num_rows($db_materiales_actividades) == 0){
                        echo '<tr><td colspan="7">No hay datos.</td></tr>';
                    }else{
                        $no = 1;


                    echo '
                        <tr>
							<th>No</th>
							<th>Código</th>
							<th>Tipo - Subtipo Actividad</th>
							<th>Nombre</th>
							<th>Descripcion</th>
							<th>Observaciones</th>
							<th>Estado</th>
                        </tr>';

                        while($row_materiales_actividades = mysqli_fetch_assoc($db_materiales_actividades)){
							$codigo_actividad_del_material = $row_materiales_actividades['codigo_actividades'];
							$db_actividades = mysqli_query($conexion, "SELECT * FROM actividades WHERE codigo = '$codigo_actividad_del_material'");
							$row_actividades = mysqli_fetch_assoc($db_actividades);
							echo '
							<tr>
								<td>'.$no.'</td>
								<td><a href="../profiles/profiles_actividades.php?codigo='.$row_actividades['codigo'].'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer">'.$row_actividades['codigo'].'</a></td>
								<td>'.$row_actividades['tipo'].' - '.$row_actividades['subtipo'].'</td>
								<td><a href="../profiles/profiles_actividades.php?codigo='.$row_actividades['codigo'].'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer"><i class="material-icons">golf_course</i>'.$row_actividades['nombre'].'</a></td>
								<td>'.$row_actividades['descripcion'].'</td>
								<td>'.$row_actividades['observaciones'].'</td>
								';

								echo '
								<td>';

								if($row_actividades['estado'] == '1'){
									echo '<span class="label label-success">Activa</span>';
								}
								else if ($row_actividades['estado'] == '2' ){
									echo '<span class="label label-info">Inactiva</span>';
								}
								else if ($row_actividades['estado'] == '4' ){
									echo '<span class="label label-warning">En revisión</span>';
								}
								else if ($row_actividades['estado'] == '5' ){
									echo '<span class="label label-info">Subcontratada</span>';
								}
								else if ($row_actividades['estado'] == '6' ){
									echo '<span class="label label-warning">Deshabilitada</span>';
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