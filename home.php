<?php
// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
session_start();
include_once ("include/conexion.php");

// if (isset($_SESSION['usuario'])){}else{
//     $_SESSION['usuario'] = "cajuca_admin";
// }

include_once ("include/session.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Listado de monitores</title>
 
	<!-- Bootstrap -->
	<link href="css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- MaterializeCSS Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 
</head>
<body>
    <?php
        // Consulta si la variable esta activa y si tiene valor true para activar la notificación especial de inicio de sesión.
        if(isset($_SESSION['inicio'])) {  
            if ($_SESSION['inicio'] = true){ 
                echo "<script type='text/javascript'>alert('Sesión iniciada correctamente');</script>"; 
                $_SESSION['inicio'] = null;
            }
        }
    ?>

	<!-- Navbar -->
	<?php include("include/nav.php");?>
    
    <!-- Login correcto -->
    


    <!-- Contenido -->
	<div class="container">

    <!-- Búsqueda y filtro -->
        <div class="content">
            <a href="#" class="scrollup">Scroll</a>
            <!-- <button onclick="history.back()" name="volver atrás">Volver atrás</button> -->
            <h2>Búsqueda</h2>
			<hr>
            <form class="form-floating" role="search" action="php/find.php" method="$_GET">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect" name="categoria" aria-label="Seleccione el tipo de elemento a buscar" required>
                                <option selected>Seleccione la categoria a buscar</option>
                                <option value="Monitor">Monitor</option>
                                <option value="Cliente">Cliente</option>
                                <option value="Evento">Evento</option>
                                <option value="Actividad">Actividad</option>
                            </select>
                            <label for="floatingSelect">Categoría</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input class="form-control col-md-4" type="search" id="input_buscar" name="buscar" placeholder="Buscar" aria-label="Buscar" required>
                            <label for="input_buscar" class="control-label">Buscar...</label>         
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


    <!-- Listado de eventos recientes -->
		<div class="content">
			<h2>Listado de eventos recientes</h2>
			<hr>

			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
                    <th>No</th>
					<th>Código</th>
					<th>Nombre evento</th>
					<th>Fecha y Hora Inicio</th>
                    <th>Fecha y Hora Final</th>
                    <th>Dirección evento</th>
					<th>Población</th>
                    <th>Provincia</th>
                    <th>Código Cliente</th>
                    <th>Estado</th>
				</tr>
				<?php
							
				$sql = mysqli_query($conexion, "SELECT * FROM eventos WHERE estado BETWEEN 1 AND 2 OR estado BETWEEN 4 AND 5 ORDER BY fecha_inicio_evento ASC LIMIT 15");
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="10">No hay datos.</td></tr>';
				}else{
					$no = 1;
					while($row = mysqli_fetch_assoc($sql)){                       
                        echo '
						<tr>
                        <td>'.$no.'</td>
                        <td><a href="php/profiles/profiles_eventos.php?codigo='.$row['codigo'].'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</a></td>
                        <td><a href="php/profiles/profiles_eventos.php?codigo='.$row['codigo'].'" title="Ver perfil del evento" target="_blank" rel="noopener noreferrer">'.$row['nombre_evento'].'</a></td>
                        <td>'.$row['fecha_inicio_evento'].' | '.$row['horainicio'].'</td>
                        <td>'.$row['fecha_final_evento'].' | '.$row['horafinal'].'</td>
                        <td>'.$row['direccion_evento'].'</td>
                        <td>'.$row['poblacion'].'</td>
                        <td>'.$row['provincia'].'</td>
                        <td><a href="php/profiles/profiles_clientes.php?codigo='.$row['codigo_cliente'].'" title="Ver perfil cliente del evento" target="_blank" rel="noopener noreferrer">'.$row['codigo_cliente'].'</a></td>';

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
						</tr>
						';
						$no++;
					}
				}
				?>
			</table>
			</div>
            <center>
	        <p>&copy; Sistemas Web <?php echo date("Y");?></p
		    </center>

		</div>
	</div>


    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="js/web_up.js" type="text/javascript"></script>

    <!-- Footer -->
    <footer>
        <?php include("include/footer.php");?>
    </footer>

    <?php
    // Cerrar conexión
    $conexion->close();
    ?>

</body>
</html>