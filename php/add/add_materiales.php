<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../list/list_materiales.php");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Agregar Materiales</title>
 
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
			<h2>Datos de los materiales &raquo; Agregar datos</h2>
			<hr>
 
			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['add'])){
				$estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $tipo_material		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tipo_material"],ENT_QUOTES)));//Escanpando caracteres 
				$subtipo_material      = mysqli_real_escape_string($conexion,(strip_tags($_POST["subtipo_material"],ENT_QUOTES)));//Escanpando caracteres 
                $nombre           = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$caracteristicas        = mysqli_real_escape_string($conexion,(strip_tags($_POST["caracteristicas"],ENT_QUOTES)));//Escanpando caracteres 
				$observaciones	      = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 
				
				$db_query = mysqli_query($conexion, "SELECT * FROM materiales WHERE nombre='$nombre'");
				if(mysqli_num_rows($db_query) == 0){
						$db_insert = mysqli_query($conexion, "INSERT INTO materiales (estado, tipo_material, subtipo_material, nombre, caracteristicas, observaciones)
															VALUES('$estado', '$tipo_material', '$subtipo_material', '$nombre', '$caracteristicas', '$observaciones')") or die(mysqli_error($conexion));
						if($db_insert){
							echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos del nuevo material han sido guardados con éxito.</div>';
						}else{
							echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
						}
				}else{
					echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Material ya existente. Revisa los datos de nuevo.</div>';
				}
			}
			?>
            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">
                <div class="form-floating col-md-3">
                    <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                        <option value=""> Seleccione uno de los estados del material</option>
                        <option value="1">En Almacén</option>
                        <option value="2">Sin Stock</option>
                        <option value="4">En Revisión</option>
                        <option value="5">Subcontratado</option>
                    </select>
                    <label for="input_estado" class="select-label">Estado</label>
				</div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="tipo_material" id="input_tipo_material" class="form-control" placeholder="Indique el tipo de material que es." required>
                        <label for="input_tipo_material" class=" control-label">Tipo de material</label>
                    </div>
                    <div class="form-floating col-md-4">
                        <input type="text" name="subtipo_material" id="input_subtipo_material" class="form-control" placeholder="Indique el subtipo de material" required>
                        <label for="input_subtipo_material">Subtipo de material</label>
                    </div>
                </div>
                <hr>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="nombre" id="input_nombre" class="form-control" placeholder="Denominación común" required>
                        <label for="input_nombre">Nombre del material</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-2">
                        <input type="number" id="input_cantidad" name="cantidad" class="form-control" placeholder="Cantidad del material" maxlength="3">
                        <label for="input_cantidad" class="control-label">Cantidad en stock</label>
                    </div>
                </div>
                <br>
                <div class="form-floating col-md-6">
                    <textarea id="input_caracteristicas" name="caracteristicas" class="form-control" placeholder="Los detalles de como es" aria-describedby="caracteristicas_help" required></textarea>
                    <label for="input_caracteristicas" class="control-label">Características del material</label>
                    <div id="caracteristicas_help" class="form-text">Tamaño, color, forma, material, etc</div>
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
						<a href="../list/list_materiales.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
    </div>
    <br>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../js/web_up.js" type="text/javascript"></script>
    
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