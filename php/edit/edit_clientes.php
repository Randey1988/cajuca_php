<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../profiles/profiles_clientes.php?codigo=$_GET[codigo]");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Clientes</title>
 
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
			$db_query = mysqli_query($conexion, "SELECT * FROM clientes WHERE codigo='$codigo'");
			// Consultar y obtener los datos del monitor
            if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_clientes.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
            ?>

			<h2>Datos de los clientes &raquo; Editar datos del cliente</h2>
			<br>

            <?php
            // cargar y actualizar los datos modificados del monitor
			if(isset($_POST['save'])){
                $codigo_cliente	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["codigo_cliente"],ENT_QUOTES)));//Escanpando caracteres
                $estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $NIF_CIF		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["NIF_CIF"],ENT_QUOTES)));//Escanpando caracteres 
				$RazonSocial      = mysqli_real_escape_string($conexion,(strip_tags($_POST["RazonSocial"],ENT_QUOTES)));//Escanpando caracteres 
                $nombre           = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$apellidos        = mysqli_real_escape_string($conexion,(strip_tags($_POST["apellidos"],ENT_QUOTES)));//Escanpando caracteres 
				$direccion	      = mysqli_real_escape_string($conexion,(strip_tags($_POST["direccion"],ENT_QUOTES)));//Escanpando caracteres 
				$cpostal		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["cpostal"],ENT_QUOTES)));//Escanpando caracteres 
                $poblacion		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["poblacion"],ENT_QUOTES)));//Escanpando caracteres 
                $provincia		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["provincia"],ENT_QUOTES)));//Escanpando caracteres 
                $mail   		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail"],ENT_QUOTES)));//Escanpando caracteres 
                $telefono_1		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["telefono_1"],ENT_QUOTES)));//Escanpando caracteres 
                $telefono_2		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["telefono_2"],ENT_QUOTES)));//Escanpando caracteres 
                $observaciones	  = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 	
                
                if(isset($_POST["cliente_fac"]) == true){
                    $NIF_CIF_fac		 = $NIF_CIF;
                    $RazonSocial_fac	 = $RazonSocial;
                    $nombrecompleto_fac	 = $nombre.' '.$apellidos;
                    $direccion_fac		 = $direccion;
                    $cpostal_fac	     = $cpostal;
                    $poblacion_fac		 = $poblacion;
                    $provincia_fac		 = $provincia;
                    $mail_fac	   		 = $mail; 
                }else{
                    $NIF_CIF_fac		 = mysqli_real_escape_string($conexion,(strip_tags($_POST["NIF_CIF_fac"],ENT_QUOTES)));//Escanpando caracteres 
                    $RazonSocial_fac	 = mysqli_real_escape_string($conexion,(strip_tags($_POST["RazonSocial_fac"] ,ENT_QUOTES)));//Escanpando caracteres 
                    $nombrecompleto_fac	 = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombrecompleto_fac"],ENT_QUOTES)));//Escanpando caracteres 
                    $direccion_fac		 = mysqli_real_escape_string($conexion,(strip_tags($_POST["direccion_fac"],ENT_QUOTES)));//Escanpando caracteres 
                    $cpostal_fac	     = mysqli_real_escape_string($conexion,(strip_tags($_POST["cpostal_fac"],ENT_QUOTES)));//Escanpando caracteres 
                    $poblacion_fac		 = mysqli_real_escape_string($conexion,(strip_tags($_POST["poblacion_fac"],ENT_QUOTES)));//Escanpando caracteres 
                    $provincia_fac		 = mysqli_real_escape_string($conexion,(strip_tags($_POST["provincia_fac"],ENT_QUOTES)));//Escanpando caracteres 
                    $mail_fac	   		 = mysqli_real_escape_string($conexion,(strip_tags($_POST["mail_fac"],ENT_QUOTES)));//Escanpando caracteres 
                }	

				$db_update = mysqli_query($conexion, "UPDATE clientes SET estado='$estado', NIF_CIF='$NIF_CIF', RazonSocial='$RazonSocial', nombre='$nombre', apellidos='$apellidos', direccion='$direccion', cpostal='$cpostal', poblacion='$poblacion', provincia='$provincia', mail='$mail', 
                    telefono_1='$telefono_1', telefono_2='$telefono_2', NIF_CIF_fac='$NIF_CIF_fac', RazonSocial_fac='$RazonSocial_fac', nombrecompleto_fac='$nombrecompleto_fac', direccion_fac='$direccion_fac', cpostal_fac='$cpostal_fac', poblacion_fac='$poblacion_fac', provincia_fac='$provincia_fac', mail_fac='$mail_fac', observaciones='$estado' WHERE codigo='$codigo_cliente'") or die(mysqli_error($conexion));
				if($db_update){
					echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos modificados han sido actualizados con éxito.</div>';
				}else{
					echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo actualizar los datos.</div>';
				}
			}
			// Mensaje de exito si se realiza correctamente
			if(isset($_GET['funcion']) == 'exito'){
				
			}
			?>
            <!-- Formulario -->
            <form class="form-horizontal" action="" method="post">

                <!-- Acción -->
                <div class="row justify-content-between">
                    <div class= "form-group col-9"><h3>Cliente con código <?php echo $row['codigo'].': '.$row['nombre'].' '.$row['apellidos']; ?></h3></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3 der">
                        <input type="submit" name="save" class="btn btn-sm btn-success" value="Actualizar datos">
						<a href="../list/list_clientes.php" class="btn btn-sm btn-danger">Regresar</a>
                    </div>
                </div>
                <br>
                <hr>

                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" id="input_codigo_cliente" name="codigo_cliente" value="<?php echo $row ['codigo']; ?>" class="form-control" readonly>
                        <label for="input_codigo_cliente" class="control-label">Codigo Cliente</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                            <option value=""> Seleccione uno de los estados de los clientes</option>
                            <option value="1" <?php if ($row ['estado']==1){echo "selected";} ?>>Activo</option>
                            <option value="2" <?php if ($row ['estado']==2){echo "selected";} ?>>Inactivo</option>
                            <option value="4" <?php if ($row ['estado']==4){echo "selected";} ?>>En revisión</option>
                            <option value="5" <?php if ($row ['estado']==5){echo "selected";} ?>>Subcontrata</option>
                            <option value="6" <?php if ($row ['estado']==6){echo "selected";} ?>>Deshabilitado</option>
                        </select>
                        <label for="input_estado" class="select-label">Estado</label>
                    </div>
                </div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" name="NIF_CIF" id="input_NIF_CIF" class="form-control" value="<?php echo $row ['NIF_CIF']; ?>" placeholder="Introduzca solo números y mayúsculas." required>
                        <label for="input_NIF_CIF" class=" control-label">NIF - CIF</label>
                    </div>
                    <div class="form-floating col-md-4">
                        <input type="text" name="RazonSocial" id="input_RazonSocial" class="form-control" value="<?php echo $row ['RazonSocial']; ?>" placeholder="Razon Social" required>
                        <label for="input_RazonSocial">Razon Social de Empresa</label>
                    </div>
                </div>
                <hr>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="nombre" id="input_nombre" class="form-control" value="<?php echo $row ['nombre']; ?>" placeholder="Nombre" required>
                        <label for="input_nombre">Nombre</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_apellidos" name="apellidos" class="form-control" value="<?php echo $row ['apellidos']; ?>" placeholder="Apellidos" required>
                        <label for="input_apellidos" class="control-label">Apellidos</label>
                    </div>
                </div>
                <hr>
				<div class="form-floating col-md-8">
                    <textarea id="input_direccion" name="direccion" class="form-control" value="<?php echo $row ['direccion']; ?>" placeholder="Dirección"></textarea>
					<label for="input_direccion" class="control-label">Dirección</label>
				</div>
                <br>
                <div class="row g-3 align-items-center">
                    <div class="form-floating col-md-2">
                        <input type="number" id="input_cpostal" name="cpostal" class="form-control" value="<?php echo $row ['cpostal']; ?>" placeholder="Código Postal" maxlength="5">
                        <label for="input_cpostal" class="control-label">Código Postal</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_poblacion" name="poblacion" class="form-control" value="<?php echo $row ['poblacion']; ?>" placeholder="Población - Localidad">
                        <label for="input_poblacion" class="control-label">Población</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_provincia" name="provincia" class="form-control" value="<?php echo $row ['provincia']; ?>" placeholder="Provincia">
                        <label for="input_provincia" class="control-label">Provincia</label>
                    </div>
                </div>
                <hr>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail" name="mail" class="form-control" value="<?php echo $row ['mail']; ?>" placeholder="email@tucorreo.es" required>
					<label for="input_mail" class="control-label">Mail</label>
				</div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="number" id="input_telefono_1" name="telefono_1" class="form-control" value="<?php echo $row ['telefono_1']; ?>" placeholder="Teléfono" maxlength="9" required>
                        <label for="input_telefono_1" class="control-label">Teléfono</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <input type="number" id="input_telefono_2" name="telefono_2" class="form-control" value="<?php echo $row ['telefono_2']; ?>" placeholder="Teléfono 2" maxlength="9" required>
                        <label for="input_telefono_2" class="control-label">Teléfono</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" value="<?php echo $row ['observaciones']; ?>" placeholder="Otros datos de interés" aria-describedby="observaciones_help"></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="tit_otros_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>
                <br>
                <hr>
                <h3>DATOS DE FACTURACIÓN</h3>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" name="NIF_CIF_fac" id="input_NIF_CIF_fac" class="form-control" value="<?php echo $row ['NIF_CIF_fac']; ?>" placeholder="Introduzca solo números y mayúsculas.">
                        <label for="input_NIF_CIF_fac" class=" control-label">NIF - CIF</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" name="RazonSocial_fac" id="input_RazonSocial_fac" class="form-control" value="<?php echo $row ['RazonSocial_fac']; ?>" placeholder="Nombre ">
                        <label for="input_RazonSocial_fac">Razon Social</label>
                    </div>
                </div>
                <br>
				<div class="form-floating col-md-4">
                    <input type="text" id="input_nombrecompleto_fac" name="nombrecompleto_fac" class="form-control" value="<?php echo $row ['nombrecompleto_fac']; ?>" placeholder="Apellidos">
					<label for="input_nombrecompleto_fac" class="control-label">Apellidos</label>
				</div>
                <hr>
				<div class="form-floating col-md-8">
                    <textarea id="input_direccion_fac" name="direccion_fac" class="form-control" value="<?php echo $row ['direccion_fac']; ?>" placeholder="Dirección"></textarea>
					<label for="input_direccion_fac" class="control-label">Dirección</label>
				</div>
                <br>
                <div class="row g-3 align-items-center">
                    <div class="form-floating col-md-2">
                        <input type="number" id="input_cpostal_fac" name="cpostal_fac" class="form-control" value="<?php echo $row ['cpostal_fac']; ?>" placeholder="Código Postal" maxlength="5">
                        <label for="input_cpostal_fac" class="control-label">Código Postal</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_poblacion_fac" name="poblacion_fac" class="form-control" value="<?php echo $row ['poblacion_fac']; ?>" placeholder="Población - Localidad">
                        <label for="input_poblacion_fac" class="control-label">Población</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_provincia_fac" name="provincia_fac" class="form-control" value="<?php echo $row ['provincia_fac']; ?>" placeholder="Provincia">
                        <label for="input_provincia_fac" class="control-label">Provincia</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail_fac" name="mail_fac" class="form-control" value="<?php echo $row ['mail_fac']; ?>" placeholder="email@tucorreo.es">
					<label for="input_mail_fac" class="control-label">Mail</label>
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