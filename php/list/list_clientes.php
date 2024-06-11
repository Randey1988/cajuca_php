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
	<title>Listado de clientes</title>
 
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
			<h2>Lista de clientes</h2>
			<hr>
 
			<!-- Funcion Deshabilitar -->
			<?php
			if(isset($_GET['funcion']) == 'disabled'){
				// escaping, additionally removing everything that could be (html/javascript-) code
				$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
				$db_disabled = mysqli_query($conexion, "SELECT * FROM clientes WHERE codigo='$codigo'");
				if(mysqli_num_rows($db_disabled) == 0){
					echo '<div class="alert alert-info alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> No se encontraron los datos del cliente.</div>';
				}else{
					$disabled = mysqli_query($conexion, "UPDATE clientes SET estado='6' WHERE codigo='$codigo'");
					if($disabled){
						echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>El cliente ha sido deshabilitada correctamente.</div>';
					}else{
						echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button> Error, no se pudo deshabilitar el cliente.</div>';
					}
				}
			}
			?>
 
			<form class="form-inline" method="get">
				<div class="form-group">
					<select name="filter" class="form-control form-select" onchange="form.submit()">
						<option value="0">Filtro de datos de clientes:</option>
						<?php $filter = (isset($_GET['filter']) ? strtolower($_GET['filter']) : NULL);  ?>
						<option value="0" <?php if($filter == 'Ver Todos'){ echo 'selected'; } ?>>Ver Todos</option>
						<option value="1" <?php if($filter == 'Activo'){ echo 'selected'; } ?>>Ver activos</option>
                        <option value="4" <?php if($filter == 'Desactualizado'){ echo 'selected'; } ?>>Ver desactualizado</option>
                        <option value="5" <?php if($filter == 'Subcontratado'){ echo 'selected'; } ?>>Ver subcontratados</option>
                        <option value="6" <?php if($filter == 'Deshabilitado'){ echo 'selected'; } ?>>Ver deshabilitado</option>
					</select>
				</div>
			</form>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover align-middle">
				<tr>
                    <th>No</th>
					<th>Código</th>
					<th>Razón Social</th>
					<th>Nombre Completo</th>
                    <th>Población</th>
					<th>Teléfono</th>
                    <th>Mail</th>
                    <th>Estado</th>
				</tr>
				<?php


				if(isset($_GET['buscar'])){
					$buscar = $_GET['buscar'];
					$sql = mysqli_query($conexion, "SELECT * FROM clientes WHERE codigo like '$buscar'
						OR RazonSocial like '%$buscar%'
						OR NIF_CIF like '%$buscar%'
						OR nombre like '%$buscar%'
						OR nombrecompleto_fac like '%$buscar%'
						OR apellidos like '%$buscar%'
						OR poblacion like '%$buscar%'
						OR mail like '%$buscar%'
						OR telefono_1 like '%$buscar%'
						OR telefono_2 like '%$buscar%'");

				}elseif($filter == 0){
					$sql = mysqli_query($conexion, "SELECT * FROM clientes WHERE estado <> 6 ORDER BY codigo ASC");
				}elseif($filter <> NULL){
					$sql = mysqli_query($conexion, "SELECT * FROM clientes WHERE estado='$filter' ORDER BY codigo ASC");
				}else{
					$sql = mysqli_query($conexion, "SELECT * FROM clientes WHERE estado <> 6 ORDER BY codigo ASC");
				}
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){
                        echo '
						<tr>
							<td>'.$no.'</td>
							<td><a href="../profiles/profiles_clientes.php?codigo='.$row['codigo'].'" title="Ver perfil del cliente" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</a></td>
							<td><a href="../profiles/profiles_clientes.php?codigo='.$row['codigo'].'" title="Ver perfil del cliente" target="_blank" rel="noopener noreferrer"><i class="material-icons">business</i> '.$row['RazonSocial'].'</a></td>
							<td><a href="../profiles/profiles_clientes.php?codigo='.$row['codigo'].'" title="Ver perfil del cliente" target="_blank" rel="noopener noreferrer"><i class="material-icons">assignment_ind</i> '.$row['nombre'].' '.$row['apellidos'].'</a></td>
                            <td>'.$row['poblacion'].'</td>
							<td><i class="material-icons">call</i> '.$row['telefono_1'].'</td>
							<td><i class="material-icons">email</i> '.$row['mail'].'</td>';

                        echo '<td>';
							if($row['estado'] == '1'){
								echo '<span class="label label-success">Activo</span>';
							}else if ($row['estado'] == '2' ){
								echo '<span class="label label-info">Inactivo</span>';
							}else if ($row['estado'] == '4' ){
								echo '<span class="label label-warning">En Revisión</span>';
							}else if ($row['estado'] == '5' ){
								echo '<span class="label label-info">Subcontrata</span>';
							}else if ($row['estado'] == '6' ){
                                echo '<span class="label label-warning">Deshabilitado</span>';
                            }
						echo '
							</td>';

                            if ($_SESSION ['permisos'] > 1){
                            echo '
								<td>
									<a href="../edit/edit_clientes.php?codigo='.$row['codigo'].'" title="Editar datos" class="btn btn-warning btn-sm"><i class="material-icons">edit</i></a>
									<a href="list_clientes.php?funcion=disabled&codigo='.$row['codigo'].'" title="Deshabilitar" onclick="return confirm(\'¿Esta seguro de deshabilitar los datos del cliente '.$row['nombre'].' '.$row['apellidos'].' de la empresa '.$row['RazonSocial'].'?\')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
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