<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../list/list_clientes.php");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agregar Cliente</title>
 
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
			<h2>Datos de los clientes &raquo; Agregar datos</h2>
			<hr>
 
			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['add'])){
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
                if($_POST["cliente_fac"] == true){
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
				$db_query = mysqli_query($conexion, "SELECT * FROM clientes WHERE NIF_CIF='$NIF_CIF'");
				if(mysqli_num_rows($db_query) == 0){
						$db_insert = mysqli_query($conexion, "INSERT INTO clientes (estado, NIF_CIF, RazonSocial, nombre, apellidos, direccion, cpostal, poblacion, provincia, mail, telefono_1, telefono_2, NIF_CIF_fac, RazonSocial_fac, nombrecompleto_fac, direccion_fac, cpostal_fac, poblacion_fac, provincia_fac, mail_fac, observaciones)
															VALUES('$estado', '$NIF_CIF', '$RazonSocial', '$nombre', '$apellidos', '$direccion', '$cpostal', '$poblacion', '$provincia', '$mail', '$telefono_1', '$telefono_2', '$NIF_CIF_fac', '$RazonSocial_fac', '$nombrecompleto_fac', '$direccion_fac', '$cpostal_fac', '$poblacion_fac', '$provincia_fac', '$mail_fac', '$observaciones')") or die(mysqli_error($conexion));
						if($db_insert){
							echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos del nuevo cliente han sido guardados con éxito.</div>';
						}else{
							echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
						}
				}else{
					echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Cliente ya existente. Revisa los datos de nuevo.</div>';
				}
			}
			?>
            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">
                <div class="form-floating col-md-3">
                    <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                        <option value=""> Seleccione uno de los estados </option>
                        <option value="1">Activo</option>
                        <option value="2">Inactivo</option>
                        <option value="4">En Revisión</option>
                        <option value="5">Subcontrata</option>
                    </select>
                    <label for="input_estado" class="select-label">Estado</label>
				</div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" name="NIF_CIF" id="input_NIF_CIF" class="form-control" placeholder="Introduzca solo números y mayúsculas." required>
                        <label for="input_NIF_CIF" class=" control-label">NIF - CIF</label>
                    </div>
                    <div class="form-floating col-md-4">
                        <input type="text" name="RazonSocial" id="input_RazonSocial" class="form-control" placeholder="Razon Social" required>
                        <label for="input_RazonSocial">Razon Social de Empresa</label>
                    </div>
                </div>
                <hr>
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
                <hr>
				<div class="form-floating col-md-8">
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
                        <input type="text" id="input_provincia" name="provincia" class="form-control" placeholder="Provincia">
                        <label for="input_provincia" class="control-label">Provincia</label>
                    </div>
                </div>
                <hr>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail" name="mail" class="form-control" placeholder="email@tucorreo.es" required>
					<label for="input_mail" class="control-label">Mail</label>
				</div>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="tel" id="input_telefono_1" name="telefono_1" class="form-control" placeholder="Teléfono" maxlength="9" required>
                        <label for="input_telefono_1" class="control-label">Teléfono</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <input type="tel" id="input_telefono_2" name="telefono_2" class="form-control" placeholder="Teléfono 2" maxlength="9" required>
                        <label for="input_telefono_2" class="control-label">Teléfono</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <textarea id="input_observaciones" name="observaciones" class="form-control" placeholder="Otros datos de interés" aria-describedby="observaciones_help"></textarea>
					<label for="input_observaciones" class="control-label">Observaciones</label>
                    <div id="observaciones_help" class="form-text">Se pueden introducir cualquier observación que se considere util.</div>
				</div>
                <br>
                <hr>
                <h3>DATOS DE FACTURACIÓN</h3>
                <hr>
                <div class="form-check">
                    <input type="checkbox" id="input_cliente_fac" name="cliente_fac" class="form-check-input" onChange="comprobar(this);" value="true" checked>
                    <label for="input_cliente_fac" class="form-check-label">Son los mismos datos que el cliente</label>
				</div>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" id="input_NIF_CIF_fac" name="NIF_CIF_fac" class="form-control" placeholder="Introduzca solo números y mayúsculas.">
                        <label for="input_NIF_CIF_fac" class=" control-label">NIF - CIF</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_RazonSocial_fac" name="RazonSocial_fac" class="form-control" placeholder="Nombre ">
                        <label for="input_RazonSocial_fac">Razon Social</label>
                    </div>
                </div>
                <br>
				<div class="form-floating col-md-4">
                    <input type="text" id="input_nombrecompleto_fac" name="nombrecompleto_fac" class="form-control" placeholder="Apellidos">
					<label for="input_nombrecompleto_fac" class="control-label">Apellidos</label>
				</div>
                <hr>
				<div class="form-floating col-md-8">
                    <textarea id="input_direccion_fac" name="direccion_fac" class="form-control" placeholder="Dirección"></textarea>
					<label for="input_direccion_fac" class="control-label">Dirección</label>
				</div>
                <br>
                <div class="row g-3 align-items-center">
                    <div class="form-floating col-md-2">
                        <input type="number" id="input_cpostal_fac" name="cpostal_fac" class="form-control" placeholder="Código Postal" maxlength="5">
                        <label for="input_cpostal_fac" class="control-label">Código Postal</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_poblacion_fac" name="poblacion_fac" class="form-control" placeholder="Población - Localidad">
                        <label for="input_poblacion_fac" class="control-label">Población</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-4">
                        <input type="text" id="input_provincia_fac" name="provincia_fac" class="form-control" placeholder="Provincia">
                        <label for="input_provincia_fac" class="control-label">Provincia</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-4">
                    <input type="email" id="input_mail_fac" name="mail_fac" class="form-control" placeholder="email@tucorreo.es">
					<label for="input_mail_fac" class="control-label">Mail</label>
				</div>
                <br>
                <br>		

                <!-- Botones de accion -->
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="../list/list_monitores.php" class="btn btn-sm btn-danger">Cancelar</a>
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