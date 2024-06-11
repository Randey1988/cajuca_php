<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../list/list_eventos.php");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agregar Evento</title>
 
	<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../../css/style.css" rel="stylesheet">
	<!-- <style>
        body{
            margin: auto;
            padding-top:50px;
        }
        .nav-fill > .nav-link,
        .nav-fill .nav-item {
        flex: 1 1 auto;
        text-align: center;
        padding: 0 10px 0 10px;
        }
	</style> -->
</head>
<body>
	<!-- Navbar -->
	<?php include("../../include/nav.php");?>
    
    <!-- Contenido -->
	<div class="container"> 
        <div class="content">
            <a href="#" class="scrollup">Scroll</a>
			<h2>Datos de los eventos &raquo; Agregar datos</h2>
			<hr>
 
			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['add'])){
				$estado			        = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $codigo_cliente		    = mysqli_real_escape_string($conexion,(strip_tags($_POST["NIF_CIF"],ENT_QUOTES)));//Escanpando caracteres 
				$nombre_evento          = mysqli_real_escape_string($conexion,(strip_tags($_POST["RazonSocial"],ENT_QUOTES)));//Escanpando caracteres 
                $descripcion_evento     = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$dirección_evento       = mysqli_real_escape_string($conexion,(strip_tags($_POST["apellidos"],ENT_QUOTES)));//Escanpando caracteres 
				$cpostal		        = mysqli_real_escape_string($conexion,(strip_tags($_POST["cpostal"],ENT_QUOTES)));//Escanpando caracteres 
                $poblacion	            = mysqli_real_escape_string($conexion,(strip_tags($_POST["poblacion"],ENT_QUOTES)));//Escanpando caracteres 
                $provincia		        = mysqli_real_escape_string($conexion,(strip_tags($_POST["provincia"],ENT_QUOTES)));//Escanpando caracteres 
                $fecha_inicio_evento    = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail"],ENT_QUOTES)));//Escanpando caracteres 
                $fecha_final_evento		= mysqli_real_escape_string($conexion,(strip_tags($_POST["telefono_1"],ENT_QUOTES)));//Escanpando caracteres 
                $horainicio		        = mysqli_real_escape_string($conexion,(strip_tags($_POST["telefono_2"],ENT_QUOTES)));//Escanpando caracteres 
                $horafinal	            = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 	
                $codigo_coordinador		= mysqli_real_escape_string($conexion,(strip_tags($_POST["NIF_CIF_fac"],ENT_QUOTES)));//Escanpando caracteres 
                $horas_trabajadas	    = mysqli_real_escape_string($conexion,(strip_tags($_POST["RazonSocial_fac"] ,ENT_QUOTES)));//Escanpando caracteres 
                $horas_desplazamiento	= mysqli_real_escape_string($conexion,(strip_tags($_POST["nombrecompleto_fac"],ENT_QUOTES)));//Escanpando caracteres 
                $horas_montaje		    = mysqli_real_escape_string($conexion,(strip_tags($_POST["direccion_fac"],ENT_QUOTES)));//Escanpando caracteres 
                $horas_desmontaje	    = mysqli_real_escape_string($conexion,(strip_tags($_POST["cpostal_fac"],ENT_QUOTES)));//Escanpando caracteres 
                
				$db_query = mysqli_query($conexion, "SELECT * FROM eventos WHERE nombre_evento='$nombre_evento'");
				if(mysqli_num_rows($db_query) == 0){
						$db_insert = mysqli_query($conexion, "INSERT INTO eventos (estado, codigo_cliente, nombre_evento, descripcion_evento, direccion_evento, cpostal, poblacion, provincia, fecha_inicio_evento, fecha_final_evento, horainicio, horafinal, codigo_coordinador, horas_trabajadas, horas_desplazamiento, horas_montaje, horas_desmontaje, observaciones)
															VALUES('$estado', '$codigo_cliente', '$nombre_evento', '$descripcion_evento', '$direccion_evento', '$cpostal', '$poblacion', '$provincia', '$fecha_inicio_evento', '$fecha_final_evento', '$horainicio', '$horafinal', '$codigo_coordinador', '$horas_trabajadas', '$horas_desplazamiento', '$horas_montaje', '$horas_desmontaje', '$observaciones')") or die(mysqli_error($conexion));
						if($db_insert){
							echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos del nuevo cliente han sido guardados con éxito.</div>';
                            $codigo_evento = mysqli_query($conexion, "SELECT codigo FROM eventos WHERE nombre_evento = '$nombre_evento'");
                            $codigo_evento = $codigo_evento['codigo'];
                            header ("Location: ../edit/edit_eventos2.php?funcion='add'&codigo=$codigo_evento&fecha_evento=$fecha_inicio_evento");
                            die;
                        }else{
							echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
						}
				}else{
					echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Evento ya existente. Revisa los datos de nuevo.</div>';
				}
			}
			?>
            <!-- Formulario -->
			<form class="form-horizontal align-items-center" action="" method="post"> <!-- action="../edit/edit_eventos2.php" -->
                <div class="form-floating col-md-3">
                    <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                        <option value=""> Seleccione uno de los estados </option>
                        <option value="1">Pendiente</option>
                        <option value="2">En espera</option>
                        <option value="3">Completado</option>
                        <option value="4">Para revision</option>
                        <option value="5">Subcontrata</option>
                    </select>
                    <label for="input_estado" class="select-label">Estado</label>
				</div>
                <br>
                <div class="form-floating col-md-6">
                    <select class="form-select" id="input_codigo_cliente" name="codigo_cliente" aria-label="Selector de Cliente">
                        <option selected>Seleccione un cliente</option>
                        <?php
                            $db_clientes = mysqli_query($conexion, "SELECT codigo, RazonSocial, nombre, apellidos FROM clientes WHERE estado < '5' ORDER BY RazonSocial ASC");
                            while($row_clientes = mysqli_fetch_assoc($db_clientes)){
                                echo '<option value="'.$row_clientes['codigo'].'">'.$row_clientes['RazonSocial'].' | '.$row_clientes['nombre'].' '.$row_clientes['apellidos'].' </option>';
                            }
                        ?>
                    </select>
                    <label for="input_codigo_cliente">Cliente del Evento</label>
                </div>
                <br>
                <div class="form-floating col-md-6">
                    <select class="form-select" id="input_codigo_coordinador" name="codigo_coordinador" aria-label="Selector de Coordinador">
                        <option selected>Seleccione un coordinador</option>
                        <?php
                            $db_coordinadores = mysqli_query($conexion, "SELECT c.codigo, c.DNI_NIF, m.nombre, m.apellidos FROM coordinadores c, monitores m WHERE c.DNI_NIF like m.DNI_NIF ORDER BY nombre ASC, apellidos ASC");
                            while($row_coordinadores = mysqli_fetch_assoc($db_coordinadores)){
                                echo '<option value="'.$row_coordinadores['codigo'].'">'.$row_coordinadores['DNI_NIF'].' | '.$row_coordinadores['nombre'].' '.$row_coordinadores['apellidos'].' </option>';
                            }
                        ?>
                    </select>
                    <label for="input_codigo_coordinador">Coordinador del Evento</label>
                </div>
                <hr>
				<div class="form-floating col-md-6">
                    <input type="text" name="nombre_evento" id="input_nombre_evento" class="form-control" placeholder="Nombre del evento" aria-describedby="nombre_evento_help" required>
                    <label for="input_nombre_evento" class=" control-label">Nombre del evento</label>
                    <div id="nombre_evento_help" class="form-text">Introduzca el nombre principal del evento.</div>
                </div>
                <br>
                <div class="form-floating col-md-6">
                    <textarea id="input_descripcion_evento" name="descripcion_evento" class="form-control" placeholder="Descripción del evento" aria-describedby="descripcion_evento_help"></textarea>
					<label for="input_descripcion_evento" class="control-label">Descripción de Eventos</label>
                    <div id="descripcion_evento_help" class="form-text">Introducir un resumen de en que consiste el evento y motivos.</div>
				</div>
                <br>
                <hr>
                <h4><b>Planificación del Evento</b></h4>
                <hr>
				<div class="form-floating col-md-4">
                    <textarea id="input_direccion" name="direccion" class="form-control" placeholder="Dirección"></textarea>
					<label for="input_direccion" class="control-label">Dirección</label>
				</div>
                <br>
                <div class="form-floating col-md-2">
                    <input type="number" id="input_cpostal" name="cpostal" class="form-control" placeholder="Código Postal" maxlength="5">
					<label for="input_cpostal" class="control-label">Código Postal</label>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="text" id="input_poblacion" name="poblacion" class="form-control" placeholder="Población - Localidad">
					<label for="input_poblacion" class="control-label">Población</label>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="text" id="input_provincia" name="provincia" class="form-control" placeholder="Provincia" aria-describedby="provincia_help">
					<label for="input_provincia" class="control-label">Provincia</label>
                    <div id="provincia_help" class="form-text">Introduzca solo la provincia.</div>
				</div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="date" id="input_fecha_inicio_evento" name="fecha_inicio_evento" class="form-control" placeholder="Fecha de Inicio del evento" required>
                        <label for="input_fecha_inicio_evento">Fecha de Inicio del evento</label>
                    </div>
                    <div class="form-floating col-md-3">
                        <input type="time" id="input_horainicio" name="horainicio" class="form-control" placeholder="Hora de Inicio" required>
                        <label for="input_horainicio">Hora de Inicio</label>
                    </div>
                </div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="date" id="input_fecha_final_evento" name="fecha_final_evento" class="form-control" placeholder="Fecha de Fin del evento" required>
                        <label for="input_fecha_final_evento" class="control-label">Fecha de Fin del evento</label>
                    </div>
                    <div class="form-floating col-md-3">
                        <input type="time" id="input_horafinal" name="horafinal" class="form-control" placeholder="Hora de Fin" required>
                        <label for="input_horafinal" class="control-label">Hora de Fin</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-6">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="observaciones_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>
                <br>
                <hr>
                <h4><b>Desglose de tiempos del evento</b></h4>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="number" name="horas_trabajadas" id="input_horas_trabajadas" class="form-control" placeholder="Solo números enteros.">
                        <label for="input_horas_trabajadas" class=" control-label">Tiempo Trabajado en horas</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="number" name="horas_desplazamiento" id="input_horas_desplazamiento" class="form-control" placeholder="Solo números enteros.">
                        <label for="input_horas_desplazamiento" class=" control-label">Tiempo de Desplazamiento en horas</label>
                    </div>
                </div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="number" name="horas_montaje" id="input_horas_montaje" class="form-control" placeholder="Solo números enteros.">
                        <label for="input_horas_montaje" class=" control-label">Tiempo de Montaje en horas</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="number" name="horas_desmontaje" id="input_horas_desmontaje" class="form-control" placeholder="Solo números enteros.">
                        <label for="input_horas_desmontaje" class=" control-label">Tiempo de Desmontaje en horas</label>
                    </div>
                </div>
                <br>
                <br>		

                <!-- Botones de accion -->
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="../list/list_eventos.php" class="btn btn-sm btn-danger">Cancelar</a>
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
    // $('.date').datepicker({
    //     format: 'yyyy-mm-dd',
    // })
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