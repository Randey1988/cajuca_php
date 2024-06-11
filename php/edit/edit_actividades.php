<?php
	// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
	session_start();
	include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../profiles/profiles_actividades.php?codigo=$_GET[codigo]");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Actividad</title>
 
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
			// Consultar y obtener los datos del monitor
            if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_actividades.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>
			<h2>Datos de las actividades &raquo; Editar datos de la actividad</h2>
			<br>

			<?php
            // cargar y actualizar los datos modificados del monitor
			if(isset($_POST['save'])){
                $codigo_actividad	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["codigo_actividad"],ENT_QUOTES)));//Escanpando caracteres
                $estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $tipo		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tipo"],ENT_QUOTES)));//Escanpando caracteres 
				$subtipo      = mysqli_real_escape_string($conexion,(strip_tags($_POST["subtipo"],ENT_QUOTES)));//Escanpando caracteres 
                $etiquetas           = mysqli_real_escape_string($conexion,(strip_tags($_POST["etiquetas"],ENT_QUOTES)));//Escanpando caracteres 
				$nombre        = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$descripcion	      = mysqli_real_escape_string($conexion,(strip_tags($_POST["descripcion"],ENT_QUOTES)));//Escanpando caracteres 
				$precio_hora		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["precio_hora"],ENT_QUOTES)));//Escanpando caracteres 
                $jugadores		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["jugadores"],ENT_QUOTES)));//Escanpando caracteres 
                $observaciones	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 	
                	
				$db_update = mysqli_query($conexion, "UPDATE actividades SET estado='$estado', tipo='$tipo', subtipo='$subtipo', etiquetas='$etiquetas', nombre='$nombre', descripcion='$descripcion', 
                                                precio_hora='$precio_hora', jugadores='$jugadores', observaciones='$observaciones' WHERE codigo='$codigo_actividad'") or die(mysqli_error($conexion));
				if($db_update){
					echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos modificados han sido actualizados con éxito.</div>';
				}else{
					echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
				}
			}
			?>

            <!-- Formulario -->
            <form class="form-horizontal" action="" method="post">

                <!-- Acción -->
                <div class="row justify-content-between">
                    <div class= "form-group col-9"><h3>Actividad con código <?php echo $row['codigo'].': '.$row['nombre']; ?></h3></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3 der">
                        <input type="submit" name="save" class="btn btn-sm btn-success" value="Actualizar datos">
						<a href="../list/list_actividades.php" class="btn btn-sm btn-danger">Regresar</a>
                    </div>
                </div>
                <br>
                <hr>

                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" id="input_codigo_actividad" name="codigo_actividad" value="<?php echo $row ['codigo']; ?>" class="form-control" readonly>
                        <label for="input_codigo_actividad" class="control-label">Codigo Actividad</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                            <option value="">Seleccione uno de los estados </option>
                            <option value="1" <?php if ($row ['estado']==1){echo "selected";} ?>>Activa</option>
                            <option value="2" <?php if ($row ['estado']==2){echo "selected";} ?>>Inactiva</option>
                            <option value="4" <?php if ($row ['estado']==4){echo "selected";} ?>>En Revisión</option>
                            <option value="5" <?php if ($row ['estado']==5){echo "selected";} ?>>Subcontratada</option>
                            <option value="6" <?php if ($row ['estado']==6){echo "selected";} ?>>Deshabilitada</option>
                        </select>
                        <label for="input_estado" class="select-label">Estado</label>
                    </div>
                </div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="tipo" id="input_tipo" class="form-control" value="<?php echo $row ['tipo']; ?>" placeholder="Indique el tipo de actividad que es" required>
                        <label for="input_tipo" class=" control-label">Tipo de actividad</label>
                    </div>
                    <div class="form-floating col-md-4">
                        <input type="text" name="subtipo" id="input_subtipo" class="form-control" value="<?php echo $row ['subtipo']; ?>" placeholder="Indique el subtipo de actividad que es" required>
                        <label for="input_subtipo">Subtipo de actividad</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-8">
                    <input type="text" id="input_etiquetas" name="etiquetas" class="form-control" value="<?php echo $row ['etiquetas']; ?>" placeholder="Indique las etiquetas con las que se puede reconocer" aria-describedby="etiquetas_help">
                    <label for="input_etiquetas" class="control-label">Etiquetas</label>
                    <div id="etiquetas_help" class="form-text">Introduzca las etiquetas separadas por una coma (, ) y solo una palabra por etiqueta.</div>
                </div>
                <hr>
                <br>
                <div class="form-floating col-md-6">
                    <input type="text" name="nombre" id="input_nombre" class="form-control" value="<?php echo $row ['nombre']; ?>" placeholder="Nombre de la actividad" required>
                    <label for="input_nombre">Nombre</label>
                </div>
                <br>
				<div class="form-floating col-md-6">
                    <textarea id="input_descripcion" name="descripcion" class="form-control" placeholder="Descripción de la actividad" aria-describedby="descripcion_help" required><?php echo $row ['descripcion']; ?></textarea>
					<label for="input_descripcion" class="control-label">Descripción</label>
                    <div id="descripcion_help" class="form-text">Introduzca una descripción completa de la actividad, tanto el espacio necesario como tiempo de juego, etc.</div>
				</div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="number" id="input_precio_hora" name="precio_hora" class="form-control" value="<?php echo $row ['precio_hora']; ?>" placeholder="Cuanto cuesta cada €" maxlength="6">
                        <label for="input_precio_hora" class="control-label">Precio € / hora</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <input type="number" id="input_jugadores" name="jugadores" class="form-control" value="<?php echo $row ['jugadores']; ?>" placeholder="Cantidad de jugadores" maxlength="2">
                        <label for="input_jugadores" class="control-label">Cantidad Jugadores</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-6">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"><?php echo $row ['observaciones']; ?></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="observaciones_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>
                <br>
                <br>		
			</form>
		</div>
	</div>
	<br>
    <br>
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="../../js/web_up.js" type="text/javascript"></script>
    
	<footer>
        <?php include("../../include/footer.php");?>
    </footer>

    <?php
    // Cerrar conexión
    $conexion->close();
    ?>

</body>
</html>