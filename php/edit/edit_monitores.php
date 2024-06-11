<?php
	// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
	session_start();
	include("../../include/conexion.php");
	include_once ("../../include/session.php");

	if ($_SESSION ['permisos'] == 1){
        header ("Location: ../profiles/profiles_monitores.php?codigo=$_GET[codigo]");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Monitores</title>
 
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
			$db_query = mysqli_query($conexion, "SELECT * FROM monitores WHERE codigo='$codigo'");
			// Consultar y obtener los datos del monitor
            if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_monitores.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
			?>
			<h2>Datos de los monitores &raquo; Editar datos del monitor</h2>
			<hr>
			<?php
            // cargar y actualizar los datos modificados del monitor
			if(isset($_POST['save'])){
                $codigo_monitor	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["codigo_monitor"],ENT_QUOTES)));//Escanpando caracteres
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
				if(isset($_POST['tit_monitor'])){$tit_monitor=true;}else{$tit_monitor=false;}
				if(isset($_POST['tit_coordinador'])){$tit_coordinador=true;}else{$tit_coordinador=false;}
                // $tit_coordinador  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tit_coordinador"],ENT_QUOTES)));//Escanpando caracteres 
                $tit_otros		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tit_otros"],ENT_QUOTES)));//Escanpando caracteres  
				$observaciones	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 
                	
				$db_update = mysqli_query($conexion, "UPDATE monitores SET estado='$estado', DNI_NIF='$DNI_NIF', nombre='$nombre', apellidos='$apellidos', fecha_nac='$fecha_nac', direccion='$direccion', cpostal='$cpostal', poblacion='$poblacion', provincia='$provincia', mail='$mail', 
                    telefono='$telefono', nsegsocial='$nsegsocial', cuenta='$cuenta', tit_monitor='$tit_monitor', tit_coordinador='$tit_coordinador', tit_otros='$tit_otros', observaciones='$observaciones' WHERE codigo='$codigo_monitor'") or die(mysqli_error($conexion));
				if($db_update){

						// Si tiene titulo de coordinador/a y no está registrado, se registra
						if($tit_coordinador == true){
							$db_sql_tit_coordinador = mysqli_query($conexion, "SELECT DNI_NIF FROM coordinadores WHERE DNI_NIF = '$DNI_NIF'") or die(mysqli_error($conexion));
							if(mysqli_num_rows($db_sql_tit_coordinador) == 0){
								$db_insert = mysqli_query($conexion, "INSERT INTO coordinadores (DNI_NIF) VALUES ('$DNI_NIF')") or die(mysqli_error($conexion));
							}
						}
					// header("Location: edit_monitores.php?codigo=".$codigo_monitor."&funcion=exito");
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
                    <div class= "form-group col-9"><h3>Monitor con código  <?php echo $row['codigo'].': '.$row['nombre'].' '.$row['apellidos']; ?></h3></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3 der">
					<input type="submit" name="save" class="btn btn-sm btn-primary" value="Actualizar datos">
						<a href="../list/list_monitores.php" class="btn btn-sm btn-danger">Regresar</a>
                    </div>
                </div>
                <br>
                <hr>

				<div class="row g-2 align-items-center">
					<div class="form-floating col-md-3">
						<input type="text" id="input_codigo_monitor" name="codigo_monitor" value="<?php echo $row ['codigo']; ?>" class="form-control" readonly>
						<label for="input_codigo_monitor" class="control-label">Codigo Monitor</label>
					</div>
					<br>
					<div class="form-floating col-md-3">
						<select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
							<option value=""> Seleccione uno de los estados </option>
							<option value="1" <?php if ($row ['estado']==1){echo "selected";} ?>>Puesto Fijo</option>
							<option value="2" <?php if ($row ['estado']==2){echo "selected";} ?>>Ocupado</option>
							<option value="3" <?php if ($row ['estado']==3){echo "selected";} ?>>Libre</option>
							<option value="4" <?php if ($row ['estado']==4){echo "selected";} ?>>En Revisión</option>
							<option value="5" <?php if ($row ['estado']==5){echo "selected";} ?>>Temporal</option>
							<option value="6" <?php if ($row ['estado']==6){echo "selected";} ?>>Deshabilitado</option>
						</select>
						<label for="input_estado" class="select-label">Estado</label>
					</div>
				</div>
				<hr>
				<div class="row g-2 align-items-center">
					<div class="form-floating col-md-3">
						<input type="text" name="DNI_NIF" id="input_DNI_NIF" value="<?php echo $row ['DNI_NIF']; ?>" class="form-control" placeholder="Introduzca solo números y mayúsculas." aria-describedby="DNI_NIF_help" required>
						<label for="input_DNI_NIF" class=" control-label">DNI - NIF - NIE *</label>
						<div id="DNI_NIF_help" class="form-text">Sin espacios y en mayúscula.</div>
					</div>
					<div class="form-floating col-md-3">
						<input type="date" id="input_fecha_nac" name="fecha_nac" value="<?php echo $row ['fecha_nac']; ?>" class="input-group date form-control" date="" data-date-format="yyyy-mm-dd" placeholder="Año-mes-día" aria-describedby="fecha_nac_help" required>
						<label for="input_fecha_nac" class="control-label">Fecha de nacimiento *</label>
						<div id="fecha_nac_help" class="form-text">En formato DD-MM-YYYY.</div>
					</div>
				</div>
                <br>
				<div class="row g-2 align-items-center">
					<div class="form-floating col-md-4">
						<input type="text" name="nombre" id="input_nombre" value="<?php echo $row ['nombre']; ?>" class="form-control" placeholder="Nombre" required>
						<label for="input_nombre">Nombre *</label>
					</div>
					<div class="form-floating col-md-4">
						<input type="text" id="input_apellidos" name="apellidos" value="<?php echo $row ['apellidos']; ?>" class="form-control" placeholder="Apellidos" required>
						<label for="input_apellidos" class="control-label">Apellidos *</label>
					</div>
				</div>
                <br>
                <br>
				<div class="row g-2 align-items-center">
					<div class="form-floating col-md-4">
						<textarea id="input_direccion" name="direccion" class="form-control" placeholder="Dirección"><?php echo $row ['direccion']; ?></textarea>
						<label for="input_direccion" class="control-label">Dirección</label>
					</div>
					<br>
					<div class="form-floating col-md-2">
						<input type="number" id="input_cpostal" name="cpostal" value="<?php echo $row ['cpostal']; ?>" class="form-control" placeholder="Código Postal" maxlength="5">
						<label for="input_cpostal" class="control-label">Código Postal</label>
					</div>
				</div>
                <br>
				<div class="row g-2 align-items-center">
					<div class="form-floating col-md-4">
						<input type="text" id="input_poblacion" name="poblacion" value="<?php echo $row ['poblacion']; ?>" class="form-control" placeholder="Población - Localidad" required>
						<label for="input_poblacion" class="control-label">Población *</label>
					</div>
					<br>
					<div class="form-floating col-md-4">
						<input type="text" id="input_provincia" name="provincia" value="<?php echo $row ['provincia']; ?>" class="form-control" placeholder="Provincia" required>
						<label for="input_provincia" class="control-label">Provincia *</label>
					</div>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail" name="mail" value="<?php echo $row ['mail']; ?>" class="form-control" placeholder="email@tucorreo.es" required>
					<label for="input_mail" class="control-label">Mail *</label>
				</div>
                <br>
				<div class="form-floating col-md-3">
                    <input type="tel" id="input_telefono" name="telefono" value="<?php echo $row ['telefono']; ?>" class="form-control" placeholder="Teléfono" maxlength="9" required>
					<label for="input_telefono" class="control-label">Teléfono *</label>
				</div>
                <br>
                <hr>
				<div class="row g-2 align-items-center">
					<div class="form-floating col-md-4">
						<input type="number" id="input_nsegsocial" name="nsegsocial" value="<?php echo $row ['nsegsocial']; ?>" class="form-control" placeholder="NUSS" maxlength="11" aria-describedby="nsegsocial_help" required>
						<label for="input_nsegsocial" class="control-label">Núm. de la Seg. Social o Afiliación *</label>
						<div id="nsegsocial_help" class="form-text">Son todo números y sin espacios.</div>
					</div>
					<br>
					<div class="form-floating col-md-4">
						<input type="text" id="input_cuenta" name="cuenta" value="<?php echo $row ['cuenta']; ?>" class="form-control" placeholder="IBAN" maxlength="24" aria-describedby="cuenta_help">
						<label for="input_cuenta" class="control-label">Cuenta Bancaria</label>
						<div id="cuenta_help" class="form-text">Introduzca el IBAN de la cuenta con las 2 letra en mayúsculas y sin espacios.</div>
					</div>
				</div>
                <hr>
                <br>
                <div class="form-check">
					<input type="checkbox" id="input_tit_monitor" name="tit_monitor" class="form-check-input" value="true" <?php if ($row['tit_monitor']== True) {echo 'checked'; }?>>
                    <label for="input_tit_monitor" class="form-check-label">Título de Monitor</label>
				</div>
                <br>
                <div class="form-check">
					<input type="checkbox" id="input_tit_coordinador" name="tit_coordinador" class="form-check-input" value="true" <?php if ($row['tit_coordinador']== True) {echo 'checked'; }?>>
                    <label for="input_tit_coordinador" class="form-check-label">Título de Coordinador</label>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_tit_otros" name="tit_otros" class="form-control" placeholder="Otros títulos de interés" aria-describedby="tit_otros_help"><?php echo $row ['tit_otros']; ?></textarea>
					<label for="input_tit_otros" class="control-label">Otros títulos</label>
                    <div id="tit_otros_help" class="form-text">Introduzca el nombre del título y fecha entre paréntesis, separando cada título por una coma.</div>
				</div>
                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"><?php echo $row ['observaciones']; ?></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="tit_otros_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
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