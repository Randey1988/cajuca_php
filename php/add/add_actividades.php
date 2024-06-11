<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../list/list_actividades.php");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agregar Actividad</title>
 
	<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link href="../../css/style.css" rel="stylesheet">

</head>
<body>
	<!-- Navbar -->
	<?php include("../../include/nav.php");?>
    
    <!-- Contenido -->
	<div class="container"> 
        <div class="content">
            <a href="#" class="scrollup">Scroll</a>
			<h2>Datos de las actividades &raquo; Agregar datos</h2>
			<hr>
 
			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['add'])){
				$estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $tipo		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tipo"],ENT_QUOTES)));//Escanpando caracteres 
				$subtipo      = mysqli_real_escape_string($conexion,(strip_tags($_POST["subtipo"],ENT_QUOTES)));//Escanpando caracteres 
                $etiquetas           = mysqli_real_escape_string($conexion,(strip_tags($_POST["etiquetas"],ENT_QUOTES)));//Escanpando caracteres 
				$nombre        = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$descripcion	      = mysqli_real_escape_string($conexion,(strip_tags($_POST["descripcion"],ENT_QUOTES)));//Escanpando caracteres 
				$precio_hora		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["precio_hora"],ENT_QUOTES)));//Escanpando caracteres 
                $jugadores		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["jugadores"],ENT_QUOTES)));//Escanpando caracteres 
                $observaciones	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 	
                
				$db_query = mysqli_query($conexion, "SELECT * FROM actividades WHERE nombre='$nombre'");
				if(mysqli_num_rows($db_query) == 0){
						$db_insert = mysqli_query($conexion, "INSERT INTO actividades (estado, tipo, subtipo, etiquetas, nombre, descripcion, precio_hora, jugadores, observaciones)
															VALUES('$estado', '$tipo', '$subtipo', '$etiquetas', '$nombre', '$descripcion', '$precio_hora', '$jugadores', '$observaciones')") or die(mysqli_error($conexion));
						if($db_insert){
							echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos del nuevo cliente han sido guardados con éxito.</div>';
                            $codigo_actividad = mysqli_query($conexion, "SELECT codigo FROM actividades WHERE nombre = '$nombre'");
                            $codigo_actividad = $codigo_actividad['codigo'];
                            header ("Location: ../edit/edit_actividades2.php?funcion='add'&codigo='.$codigo_actividad'");
                            die;
						}else{
							echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
						}
				}else{
					echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Actividad ya existente. Revisa los datos de nuevo.</div>';
				}
			}
			?>
            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">
                <div class="form-floating required col-md-3">
                    <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                        <option value=""> Seleccione uno de los estados de la actividad</option>
                        <option value="1">Activa</option>
                        <option value="2">Inactiva</option>
                        <option value="4">En Revisión</option>
                        <option value="5">Subcontratada</option>
                    </select>
                    <label for="input_estado" class="select-label">Estado</label>
				</div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating required col-md-4">
                        <input type="text" name="tipo" id="input_tipo" class="form-control" placeholder="Indique el tipo de actividad que es" required>
                        <label for="input_tipo" class="control-label">Tipo de actividad</label>
                    </div>
                    <div class="form-floating required col-md-4">
                        <input type="text" name="subtipo" id="input_subtipo" class="form-control" placeholder="Indique el subtipo de actividad que es" required>
                        <label for="input_subtipo" class="control-label">Subtipo de actividad</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-8">
                    <input type="text" id="input_etiquetas" name="etiquetas" class="form-control" placeholder="Indique las etiquetas con las que se puede reconocer" aria-describedby="etiquetas_help">
                    <label for="input_etiquetas" class="control-label">Etiquetas</label>
                    <div id="etiquetas_help" class="form-text">Introduzca las etiquetas separadas por una coma (, ) y solo una palabra por etiqueta.</div>
                </div>
                <hr>
                <br>
                <div class="form-floating required col-md-6">
                    <input type="text" name="nombre" id="input_nombre" class="form-control" placeholder="Nombre de la actividad" required>
                    <label for="input_nombre" class="control-label">Nombre</label>
                </div>
                <br>
				<div class="form-floating col-md-6">
                    <textarea id="input_descripcion" name="descripcion" class="form-control" placeholder="Descripción de la actividad" aria-describedby="descripcion_help" required></textarea>
					<label for="input_descripcion" class="control-label">Descripción</label>
                    <div id="descripcion_help" class="control-label">Introduzca una descripción completa de la actividad, tanto el espacio necesario como tiempo de juego, etc.</div>
				</div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="number" id="input_precio_hora" name="precio_hora" class="form-control" placeholder="Cuanto cuesta cada €" maxlength="6">
                        <label for="input_precio_hora" class="control-label">Precio € / hora</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <input type="number" id="input_jugadores" name="jugadores" class="form-control" placeholder="Cantidad de jugadores" maxlength="2">
                        <label for="input_jugadores" class="control-label">Cantidad Jugadores</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-6">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="observaciones_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>
                <br>
                <br>		

                <!-- Botones de accion -->
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="../list/list_actividades.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
    </div>
    <br>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../js/web_up.js" type="text/javascript"></script>
    <script>
        function comprobar(objeto){
            if (objeto.checked){
                document.getElementById('input_cliente_fac').readonly = true;
                document.getElementById('input_NIF_CIF_fac').readonly = true;
                document.getElementById('input_RazonSocial_fac').readonly = true;
                document.getElementById('input_nombrecompleto_fac').readonly = true;
                document.getElementById('input_direccion_fac').readonly = true;
                document.getElementById('input_cpostal_fac').readonly = true;
                document.getElementById('input_poblacion_fac').readonly = true;
                document.getElementById('input_provincia_fac').readonly = true;
                document.getElementById('input_mail_fac').readonly = true;
            } else{
                document.getElementById('input_cliente_fac').readonly = false;
                document.getElementById('input_NIF_CIF_fac').readonly = false;
                document.getElementById('input_RazonSocial_fac').readonly = false;
                document.getElementById('input_nombrecompleto_fac').readonly = false;
                document.getElementById('input_direccion_fac').readonly = false;
                document.getElementById('input_cpostal_fac').readonly = false;
                document.getElementById('input_poblacion_fac').readonly = false;
                document.getElementById('input_provincia_fac').readonly = false;
                document.getElementById('input_mail_fac').readonly = false;
            }
        }
    </script>
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