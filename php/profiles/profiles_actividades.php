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
	<title>Perfil de la Actividad</title>
 
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
			$db_query = mysqli_query($conexion, "SELECT * FROM actividades WHERE codigo='$codigo'");
			if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_actividades.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>

			<h2>Datos de las actividades &raquo; Perfil <?php echo $row['codigo'].' - '.$row['nombre']; ?></h2>
			<hr>
			
			<!-- Botones de accion -->
			<a href="../list/list_actividades.php" class="btn btn-sm btn-secondary"><i class="material-icons">keyboard_backspace</i> Regresar</a>
			<a href="../edit/edit_actividades.php?codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-warning"><i class="material-icons">edit</i> Editar datos</a>
			<a href="profiles_actividades.php?funcion=delete&codigo=<?php echo $row['codigo']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Esta seguro de ocultar los datos del artículo <?php echo $row['nombre']; ?>')"><i class="material-icons">delete</i> Eliminar</a>
            <hr>

            <!-- Funcion Suprimir -->
			<?php
			if(isset($_GET['funcion']) == 'delete'){
				$delete = mysqli_query($con, "DELETE FROM actividades WHERE codigo='$codigo'");
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
                    <td colspan="2"><h3>DATOS DE LA ACTIVIDAD</h3></td>
                </tr>	
                <tr>
					<th width="30%">Código Actividad</th>
					<td><?php echo $row['codigo']; ?></td>
				</tr>
                <tr>
					<th>Estado</th>
					<td>
						<?php 
							if ($row['estado']==1) {
								echo "Activa";
							} else if ($row['estado']==2){
								echo "Inactiva";
							} else if ($row['estado']==4){
								echo "En revisión";
							} else if ($row['estado']==5){
								echo "Subcontratada";
							} else if ($row['estado']==6){
								echo "Deshabilitada";
							}
						?>
					</td>
				</tr>
                <tr>
					<th>Tipo de Actividad</th>
					<td><?php echo $row['tipo']; ?></td>
				</tr>
                <tr>
					<th>Subtipo de Actividad</th>
					<td><?php echo $row['subtipo']; ?></td>
				</tr>
                <tr>
					<th>Etiquetas de Categorias de Actividad</th>
					<td><?php echo $row['etiquetas']; ?></td>
				</tr>
				<tr>
					<th>Nombre de la Actividad</th>
					<td><?php echo $row['nombre']; ?></td>
				</tr>
            </table>
            <br>
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="2"><h3>CARACTERÍSTICAS DE LA ACTIVIDAD</h3></td>
                </tr>	
                <tr>
					<th width="30%">Descripción de la Actividad</th>
					<td><?php echo $row['descripcion']; ?></td>
				</tr>
                <tr>
					<th>Precio / hora</th>
					<td><?php echo $row['precio_hora']; ?> €</td>
				</tr>
				<tr>
					<th>Cantidad de Jugadores</th>
					<td><?php echo $row['jugadores']; ?></td>
				</tr>
                <tr>
					<th>Observaciones</th>
					<td><?php echo $row['observaciones']; ?></td>
				</tr>
            </table>
            <br>

            <!-- Tabla de materiales registrados de la actividad -->
            <table class="table table-striped table-condensed">
			    <tr>
                    <td colspan="6"><h3>MATERIAL NECESARIO</h3></td>
                    <td colspan="1"><a href="../edit/edit_actividades2.php?codigo=<?php echo $row['codigo']; ?>" title="Editar Materiales" class="btn btn-sm btn-success" target="_blank" rel="noopener noreferrer"><i class="material-icons">edit</i> Editar Materiales</a></td>
                </tr>
                <?php
                    $db_materiales_actividades = mysqli_query($conexion, "SELECT * FROM materiales_actividades WHERE codigo_actividades = '$codigo' ORDER BY codigo_materiales ASC");
                    if(mysqli_num_rows($db_materiales_actividades) == 0){
                        echo '<tr><td colspan="7">No hay datos.</td></tr>';
                    }else{
                        $no = 1;

                    echo '
                        <tr>
                            <th>No</th>
                            <th>Código</th>
                            <th>Tipo - Subtipo Material</th>
                            <th>Nombre</th>
							<th>Características Material</th>
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