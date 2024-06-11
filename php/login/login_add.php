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
	<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agregar usuario</title>
 
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

			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['add'])){
				$estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $usuario		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["usuario"],ENT_QUOTES)));//Escanpando caracteres 
                $nombre           = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$apellidos        = mysqli_real_escape_string($conexion,(strip_tags($_POST["apellidos"],ENT_QUOTES)));//Escanpando caracteres 
				$permisos        = mysqli_real_escape_string($conexion,(strip_tags($_POST["permisos"],ENT_QUOTES)));//Escanpando caracteres 
				$mail   		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail"],ENT_QUOTES)));//Escanpando caracteres 
				$observaciones	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 		
                
                if($_POST["contrasena_auto"] == true){
                    $contrasena	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["contrasena"],ENT_QUOTES)));//Escanpando caracteres 
                    if ($_POST['contrasena'] == $_POST['contrasena2']){
                        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                    }                
                }else{
                    $contrasena	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail"],ENT_QUOTES)));//Escanpando caracteres 
                    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                }
                if(isset($contrasena_hash)){
                    $db_query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario'");
                    if(mysqli_num_rows($db_query) == 0){
                            $db_insert = mysqli_query($conexion, "INSERT INTO usuarios (estado, usuario, contrasena, nombre, apellidos, permisos, mail, observaciones)
                                                                VALUES('$estado', '$usuario', '$contrasena_hash', '$nombre', '$apellidos', '$permisos', '$mail', '$observaciones')") or die(mysqli_error($conexion));
                            if($db_insert){
                                echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos del nuevo usuario han sido guardados con éxito.</div>';
                            }else{
                                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
                            }
                    }else{
                        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! El usuario ya existe. Revisa los datos de nuevo.</div>';
                    }
                }else{
                    echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>La contraseña esta mal escrita, por favor revisa que ambos cuadros tengan la misma contraseña.</div>';
                }
			}
			?>

            <h2>Datos de los usuarios &raquo; Agregar Nuevo Usuario</h2>
			<hr>

            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                            <option value=""> Seleccione uno de los estados </option>
                            <option value="1">Activo</option>
                            <option value="2">Renovar Contraseña</option>
                            <option value="4">En Revisión</option>
                            <option value="5">Temporal</option>
                        </select>
                        <label for="input_estado" class="select-label">Estado</label>
                    </div>
                    <div class="form-floating col-md-3">
                        <select id="input_permisos" name="permisos" class="form-select" aria-label="Seleccionar tipo de cuenta">
                            <option value=""> Seleccione un tipo de cuenta </option>
                            <option value="1">Básica</option>
                            <option value="2">Avanzada</option>
                            <option value="3">Administrador</option>
                        </select>
                        <label for="input_estado" class="select-label">Tipo de Cuenta</label>
                    </div>
                </div>
                <hr>
				<div class="form-floating col-md-3">
                    <input type="text" id="input_usuario" name="usuario" class="form-control" placeholder="Introduzca su usuario de la empresa." required>
                    <label for="input_usuario" class=" control-label">Usuario</label>
				</div>
                <br>
                <div class="form-check">
                    <input type="checkbox" id="input_contrasena_auto" name="contrasena_auto" class="form-check-input" onChange="comprobar(this);" value="true" checked>
                    <label for="input_contrasena_auto" class="form-check-label">Quiero personalizar la contraseña.</label>
				</div>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="password" id="input_contrasena" name="contrasena" class="form-control" placeholder="Introduzca su contrasena." alt="strongPass" required>
                        <label for="input_contrasena" class=" control-label">Contraseña</label>
                    </div>
                    <div class="form-floating col-md-3">
                        <input type="password" id="input_contrasena2" name="contrasena2" class="form-control" placeholder="Introduzca su contrasena." alt="strongPass" required>
                        <label for="input_contrasena2" class=" control-label">Repetir contraseña</label>
				    </div>
                    <div id="cuenta_help" class="form-text">Utilice al menos un numero y una letra, con una longitud de 12 caracteres minimo.</div>
                </div>
                <hr>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_nombre" name="nombre" class="form-control" placeholder="Nombre" required>
                        <label for="input_nombre">Nombre</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_apellidos" name="apellidos" class="form-control" placeholder="Apellidos" required>
                        <label for="input_apellidos" class="control-label">Apellidos</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail" name="mail" class="form-control" placeholder="email@tucorreo.es" required>
					<label for="input_mail" class="control-label">Mail</label>
				</div>

                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="tit_otros_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>			

                <!-- Botones de accion -->
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="../login/login_list.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
    </div>
    <br>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        function comprobar(objeto){
            if (objeto.checked){
                document.getElementById('input_contrasena').readOnly = false;
                document.getElementById('input_contrasena2').readOnly = false;
                document.getElementById('input_contrasena').value = "";
                document.getElementById('input_contrasena2').value = "";
            } else{
                document.getElementById('input_contrasena').readOnly = true;
                document.getElementById('input_contrasena2').readOnly = true;
                document.getElementById('input_contrasena').value = "********";
                document.getElementById('input_contrasena2').value = "********";
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

        