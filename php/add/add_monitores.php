<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../list/list_monitores.php");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agregar Monitor</title>
 
	<!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="../../css/style.css">

</head>
<body>
	<!-- Navbar -->
	<?php include("../../include/nav.php");?>
    
    <!-- Contenido -->
	<div class="container"> 
        <div class="content">
            <a href="#" class="scrollup">Scroll</a>
			<h2>Datos de los monitores &raquo; Agregar datos</h2>
			<hr>
			
			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['add'])){
				$estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $DNI_NIF		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["DNI_NIF"],ENT_QUOTES)));//Escanpando caracteres 
				$nombre           = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$apellidos        = mysqli_real_escape_string($conexion,(strip_tags($_POST["apellidos"],ENT_QUOTES)));//Escanpando caracteres 
				$fecha_nac        = mysqli_real_escape_string($conexion,(strip_tags($_POST["fecha_nac"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	      = mysqli_real_escape_string($conexion,(strip_tags($_POST["direccion"],ENT_QUOTES)));//Escanpando caracteres 
				$cpostal		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["cpostal"],ENT_QUOTES)));//Escanpando caracteres 
                $poblacion		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["poblacion"],ENT_QUOTES)));//Escanpando caracteres 
                $provincia		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["provincia"],ENT_QUOTES)));//Escanpando caracteres 
                $mail   		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail"],ENT_QUOTES)));//Escanpando caracteres 
                $telefono		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["telefono"],ENT_QUOTES)));//Escanpando caracteres 
                $nsegsocial		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["nsegsocial"],ENT_QUOTES)));//Escanpando caracteres 
                $cuenta		      = mysqli_real_escape_string($conexion,(strip_tags($_POST["cuenta"],ENT_QUOTES)));//Escanpando caracteres 
				$tit_monitor	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tit_monitor"],ENT_QUOTES)));//Escanpando caracteres
                $tit_coordinador  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tit_coordinador"],ENT_QUOTES)));//Escanpando caracteres 
                $tit_otros		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tit_otros"],ENT_QUOTES)));//Escanpando caracteres  
				$observaciones	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 
                			
				$db_query = mysqli_query($conexion, "SELECT * FROM monitores WHERE DNI_NIF='$DNI_NIF'");
				if(mysqli_num_rows($db_query) == 0){
						$db_insert = mysqli_query($conexion, "INSERT INTO monitores (estado, DNI_NIF, nombre, apellidos, fecha_nac, direccion, cpostal, poblacion, provincia, mail, telefono, nsegsocial, cuenta, tit_monitor, tit_coordinador, tit_otros, observaciones)
															VALUES('$estado', '$DNI_NIF', '$nombre', '$apellidos', '$fecha_nac', '$direccion', '$cpostal', '$poblacion', '$provincia', '$mail', '$telefono', '$nsegsocial', '$cuenta', '$tit_monitor', '$tit_coordinador', '$tit_otros', '$observaciones')") or die(mysqli_error($conexion));
						if($db_insert){

                            // Si tiene titulo de coordinador/a y no está registrado, se registra
                            if($tit_coordinador == true){
                                $db_sql_tit_coordinador = mysqli_query($conexion, "SELECT DNI_NIF FROM coordinadores WHERE DNI_NIF = '$DNI_NIF'") or die(mysqli_error($conexion));
                                if(mysqli_num_rows($db_sql_tit_coordinador) == 0){
                                    $db_insert = mysqli_query($conexion, "INSERT INTO coordinadores (DNI_NIF) VALUES('$DNI_NIF')") or die(mysqli_error($conexion));
                                }
                            }
							echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos del nuevo monitor han sido guardados con éxito.</div>';
						}else{
							echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
						}
					 
				}else{
					echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Monitor ya existente. Revisa los datos de nuevo.</div>';
				}
			}
			?>

			<form class="form-horizontal" action="" method="post">

                <!-- Botones de accion -->
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="../list/list_monitores.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
                <hr>
                <br>

                <!-- Formulario -->
                <div class="form-floating col-md-3">
                    <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                        <option value=""> Seleccione uno de los estados del monitor/a</option>
                        <option value="1">Puesto Fijo</option>
                        <option value="2">Ocupado</option>
                        <option value="3">Libre</option>
                        <option value="4">En revisión</option>
                        <option value="5">Temporal</option>
                    </select>
                    <label for="input_estado" class="select-label">Estado</label>
				</div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" name="DNI_NIF" id="input_DNI_NIF" class="form-control" placeholder="Introduzca solo números y mayúsculas." required>
                        <label for="input_DNI_NIF" class=" control-label">DNI - NIF - NIE</label>
                    </div>
                    <div class="form-floating col-md-3">
                        <input type="date" id="input_fecha_nac" name="fecha_nac" class="input-group date form-control" date="" data-date-format="yyyy-mm-dd" placeholder="Año-mes-día" aria-describedby="fecha_nac_help" required>
                        <label for="input_fecha_nac" class="control-label">Fecha de nacimiento</label>
                        <div id="fecha_nac_help" class="form-text">En formato DD-MM-YYYY.</div>
                    </div>
                </div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="nombre" id="input_nombre" class="form-control" placeholder="Nombre" required>
                        <label for="input_nombre">Nombre</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_apellidos" name="apellidos" class="form-control" placeholder="Apellidos" required>
                        <label for="input_apellidos" class="control-label">Apellidos</label>
                    </div>
                </div>
                <br>

                <br>
				<div class="form-floating col-md-4">
                    <textarea id="input_direccion" name="direccion" class="form-control" placeholder="Dirección"></textarea>
					<label for="input_direccion" class="control-label">Dirección</label>
				</div>
                <br>
                <div class="row g-3 align-items-center">
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
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail" name="mail" class="form-control" placeholder="email@tucorreo.es" required>
					<label for="input_mail" class="control-label">Mail</label>
				</div>
                <br>
				<div class="form-floating col-md-3">
                    <input type="tel" id="input_telefono" name="telefono" class="form-control" placeholder="Teléfono" maxlength="9" required>
					<label for="input_telefono" class="control-label">Teléfono</label>
				</div>
                <br>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="number" id="input_nsegsocial" name="nsegsocial" class="form-control" placeholder="NUSS" maxlength="11" required>
                        <label for="input_nsegsocial" class="control-label">Núm. de la Seg. Social o Afiliación</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_cuenta" name="cuenta" class="form-control" placeholder="IBAN" maxlength="24" aria-describedby="cuenta_help">
                        <label for="input_cuenta" class="control-label">Cuenta Bancaria</label>
                        <div id="cuenta_help" class="form-text">Introduzca el IBAN de la cuenta con las 2 letra en mayúsculas y sin espacios.</div>
                    </div>
                </div>
                <hr>
                <br>
                <div class="form-check">
                    <input type="checkbox" id="input_tit_monitor" name="tit_monitor" class="form-check-input" value="true">
                    <label for="input_tit_monitor" class="form-check-label">Título de Monitor</label>
				</div>
                <br>
                <div class="form-check">
                    <input type="checkbox" id="input_tit_coordinador" name="tit_coordinador" class="form-check-input" value="true">
                    <label for="input_tit_coordinador" class="form-check-label">Título de Coordinador</label>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_tit_otros" name="tit_otros" class="form-control" placeholder="Otros títulos de interés" aria-describedby="tit_otros_help"></textarea>
					<label for="input_tit_otros" class="control-label">Otros títulos</label>
                    <div id="tit_otros_help" class="form-text">Introduzca el nombre del título y fecha entre paréntesis, separando cada título por una coma.</div>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="observaciones_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>			
			</form>
		</div>
    </div>
    <br>
    <hr>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../js/web_up.js" type="text/javascript"></script>
    <script>

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