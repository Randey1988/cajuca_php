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

			<?php
            // escaping, additionally removing everything that could be (html/javascript-) code
            $usuario = $_GET['usuario'];
			$db_query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario'");
			// Consultar y obtener los datos del monitor
            if(mysqli_num_rows($db_query) == 0){
				header("Location: login_list.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
            ?>
            <h2>Datos de los usuarios &raquo; Editar datos de <?php echo $row['usuario'].' - Codigo '.$row['codigo']; ?></h2>
			<hr>
            <!--  var_dump($_POST["contrasena_check"]); ?> -->
			<?php
            // cargar para insertar los datos nuevos de
			if (isset($_POST['save'])){
                if ($_SESSION['permisos'] == 3){
                    $estado			    = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                    $permisos           = mysqli_real_escape_string($conexion,(strip_tags($_POST["permisos"],ENT_QUOTES)));//Escanpando caracteres
                }

                $usuario_editado    = mysqli_real_escape_string($conexion,(strip_tags($_POST["usuario"],ENT_QUOTES)));//Escanpando caracteres 
                $nombre             = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$apellidos          = mysqli_real_escape_string($conexion,(strip_tags($_POST["apellidos"],ENT_QUOTES)));//Escanpando caracteres 
				$mail   		    = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail"],ENT_QUOTES)));//Escanpando caracteres 
				$observaciones	    = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 		
                
                if (isset($_POST["contrasena_check"]) && $_POST["contrasena_check"] == true){
                    $error_contrasena = false;
                    if ($_POST['contrasena'] == $_POST['contrasena2']){
                        $contrasena	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["contrasena"],ENT_QUOTES)));//Escanpando caracteres 
                        // var_dump($contrasena);
                        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                        $error_contrasena = true;
                        // var_dump($contrasena_hash);
                    }
                }else{
                    $error_contrasena = true;
                }

                if ($error_contrasena == false){
                    echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! La contraseña no se ha introducido correctamente, vuelva a comprobarlo.</div>';
                }else{
                    $db_query = mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario_editado'");
                    if (mysqli_num_rows($db_query) == 0){
                        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! El usuario NO existe o no se encuentra. Revisa los datos de nuevo y si es necesario cree un nuevo usuario.</div>';
                    }else{

                        if (isset($_POST["contrasena_check"]) && $_POST["contrasena_check"] = true){
                            $db_update = mysqli_query($conexion, "UPDATE usuarios SET nombre='$nombre', contrasena='$contrasena_hash',
                                apellidos='$apellidos', mail='$mail', observaciones='$observaciones' WHERE usuario='$usuario_editado'") or die(mysqli_error($conexion));
                        }else{
                            $db_update = mysqli_query($conexion, "UPDATE usuarios SET nombre='$nombre', 
                                apellidos='$apellidos', mail='$mail', observaciones='$observaciones' WHERE usuario='$usuario_editado'") or die(mysqli_error($conexion));
                        }
                        if ($_SESSION['permisos'] == 3){
                            $db_update = mysqli_query($conexion, "UPDATE usuarios SET estado='$estado', permisos='$permisos' WHERE usuario='$usuario_editado'") or die(mysqli_error($conexion));
                        }elseif ($row['estado'] == 2){
                            $db_update = mysqli_query($conexion, "UPDATE usuarios SET estado=1 WHERE usuario='$usuario_editado'") or die(mysqli_error($conexion));
                        }

                        if ($db_update){
                            echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos modificados han sido actualizados con éxito.</div>';
                            // header ("Location: login_edit.php");
                            // die;
                        }else{
                            echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
                        }
                    }
                }
			}

            if (isset($_SESSION['inicio']) == "renovar"){
                // $_SESSION['inicio'] = true;
                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Es necesario que ponga una nueva contraseña.</div>';
                $estado = 1;
            }else{
                $estado = $row ['estado'];
            }

			?>
            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado" required
                            <?php if ($_SESSION['permisos'] != 3){echo ' disabled';} ?>>
                            <option value=""> Seleccione uno de los estados </option>
                            <option value="1" <?php if ($estado==1){echo "selected";} ?>>Activo</option>
                            <option value="2" <?php if ($estado==2){echo "selected";} ?>>Renovar contraseña</option>
                            <option value="4" <?php if ($estado==4){echo "selected";} ?>>En Revisión</option>
                            <option value="5" <?php if ($estado==5){echo "selected";} ?>>Temporal</option>
                            <option value="6" <?php if ($estado==6){echo "selected";} ?>>Deshabilitado</option>
                        </select>
                        <label for="input_estado" class="select-label">Estado</label>
                    </div>
                    <div class="form-floating col-md-3">
                        <select id="input_permisos" name="permisos" class="form-select" aria-label="Seleccionar tipo de cuenta" required
                            <?php if ($_SESSION['permisos'] != 3){echo ' disabled';} ?>>
                            <option value=""> Seleccione un tipo de cuenta </option>
                            <option value="1" <?php if ($row ['permisos']==1){echo "selected";} ?>>Básica</option>
                            <option value="2" <?php if ($row ['permisos']==2){echo "selected";} ?>>Avanzada</option>
                            <option value="3" <?php if ($row ['permisos']==3){echo "selected";} ?>>Administrador</option>
                        </select>
                        <label for="input_estado" class="select-label">Tipo de Cuenta</label>
                    </div>
                </div>
                <hr>
				<div class="form-floating col-md-3">
                    <input type="text" id="input_usuario" name="usuario" class="form-control" placeholder="Introduzca su usuario de la empresa." value="<?php echo $row ['usuario']; ?>" required readonly>
                    <label for="input_usuario" class=" control-label">Usuario</label>
				</div>
                <br>
                <div class="form-check">
                    <?php
                    echo '<input type="checkbox" id="input_contrasena_check" name="contrasena_check" class="form-check-input" onChange="comprobar(this);" value="true"';
                    if (isset($_SESSION['inicio']) == "renovar"){
                        echo ' checked';
                    }
                    echo '>';
                    ?>
                    <label for="input_contrasena_check" class="form-check-label">Modificar la contraseña.</label>
				</div>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">

                    <?php
                    echo '<input type="password" id="input_contrasena" name="contrasena" class="form-control" placeholder="Introduzca su contrasena." alt="strongPass" required';
                        if (isset($_SESSION['inicio']) != "renovar"){
                            echo ' readonly';
                        }
                    echo '>';
                    ?>
                        <label for="input_contrasena" class=" control-label">Contraseña</label>
                    </div>
                    <div class="form-floating col-md-3">

                    <?php
                    echo '<input type="password" id="input_contrasena2" name="contrasena2" class="form-control" placeholder="Introduzca su contrasena." alt="strongPass" required';
                        if (isset($_SESSION['inicio']) != "renovar"){
                            echo ' readonly';
                        }
                    echo '>';
                    ?>
                    <label for="input_contrasena2" class=" control-label">Repetir contraseña</label>
                    </div>
                    <div id="cuenta_help" class="form-text">Utilice al menos un numero y una letra, con una longitud de 12 caracteres minimo.</div>
                </div>
                <hr>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_nombre" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo $row ['nombre']; ?>" required>
                        <label for="input_nombre">Nombre</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_apellidos" name="apellidos" class="form-control" placeholder="Apellidos" value="<?php echo $row ['apellidos']; ?>" required>
                        <label for="input_apellidos" class="control-label">Apellidos</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail" name="mail" class="form-control" placeholder="email@tucorreo.es" value="<?php echo $row ['mail']; ?>" required>
					<label for="input_mail" class="control-label">Mail</label>
				</div>

                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"><?php echo $row ['observaciones']; ?></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="tit_otros_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>			

                <!-- Botones de accion -->
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="save" class="btn btn-sm btn-primary" value="Guardar datos">
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

    <?php
        if (isset($_SESSION['inicio']) == "renovar"){
            $_SESSION['inicio'] = null;
        }
    ?>

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