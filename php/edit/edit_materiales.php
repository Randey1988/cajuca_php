<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../profiles/profiles_materiales.php?codigo=$_GET[codigo]");
        die;
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Materiales</title>
 
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
			// Preparar y extraer datos a editar
			$codigo = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));
			$db_query = mysqli_query($conexion, "SELECT * FROM materiales WHERE codigo='$codigo'");
			// Consultar y obtener los datos del monitor
            if(mysqli_num_rows($db_query) == 0){
				header("Location: ../list/list_materiales.php");
			}else{
				$row = mysqli_fetch_assoc($db_query);
			}
            ?>

			<h2>Datos de los materiales &raquo; Editar datos del material</h2>
			<br>

			<?php
            // cargar para insertar los datos nuevos de
			if(isset($_POST['save'])){
                $codigo_material			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["codigo_material"],ENT_QUOTES)));//Escanpando caracteres 
				$estado			  = mysqli_real_escape_string($conexion,(strip_tags($_POST["estado"],ENT_QUOTES)));//Escanpando caracteres 
                $tipo_material		  = mysqli_real_escape_string($conexion,(strip_tags($_POST["tipo_material"],ENT_QUOTES)));//Escanpando caracteres 
				$subtipo_material      = mysqli_real_escape_string($conexion,(strip_tags($_POST["subtipo_material"],ENT_QUOTES)));//Escanpando caracteres 
                $nombre           = mysqli_real_escape_string($conexion,(strip_tags($_POST["nombre"],ENT_QUOTES)));//Escanpando caracteres 
				$caracteristicas        = mysqli_real_escape_string($conexion,(strip_tags($_POST["caracteristicas"],ENT_QUOTES)));//Escanpando caracteres 
				$observaciones	      = mysqli_real_escape_string($conexion,(strip_tags($_POST["observaciones"],ENT_QUOTES)));//Escanpando caracteres 
				
				$db_query = mysqli_query($conexion, "SELECT * FROM materiales WHERE codigo='$codigo_material'");
				if(mysqli_num_rows($db_query) == 0){
                    echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Atención!!! Material no encontrado. Vuelvelo a intentar más tarde.</div>';
				}else{
					$db_update = mysqli_query($conexion, "UPDATE materiales SET estado='$estado', tipo_material='$tipo_material', subtipo_material='$subtipo_material', nombre='$nombre', caracteristicas='$caracteristicas', observaciones='$observaciones'
														     WHERE codigo='$codigo'") or die(mysqli_error($conexion));
                    if($db_update){
                        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos modificados han sido actualizados con éxito.</div>';
                    }else{
                        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo actualizar los datos.</div>';
                    }
				}
			}
			?>
            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">

                <!-- Acción -->
                <div class="row justify-content-between">
                    <div class= "form-group col-9"><h3>Material con código <?php echo $row['codigo'].': '.$row['nombre']; ?></h3></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3 der">
                        <input type="submit" name="save" class="btn btn-sm btn-success" value="Actualizar datos">
						<a href="../list/list_materiales.php" class="btn btn-sm btn-danger">Regresar</a>
                    </div>
                </div>
                <br>
                <hr>

                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-3">
                        <input type="text" id="input_codigo_material" name="codigo_material" value="<?php echo $row ['codigo']; ?>" class="form-control" readonly>
                        <label for="input_codigo_material" class="control-label">Codigo Material</label>
                    </div>
                    <br>
                    <div class="form-floating col-md-3">
                        <select id="input_estado" name="estado" class="form-select" aria-label="Seleccionar estado">
                            <option value=""> Seleccione uno de los estados del material</option>
                            <option value="1" <?php if ($row ['estado']==1){echo "selected";} ?>>En Almacén</option>
                            <option value="2" <?php if ($row ['estado']==2){echo "selected";} ?>>Sin Stock</option>
                            <option value="4" <?php if ($row ['estado']==4){echo "selected";} ?>>En Revisión</option>
                            <option value="5" <?php if ($row ['estado']==5){echo "selected";} ?>>Subcontratado</option>
                            <option value="6" <?php if ($row ['estado']==6){echo "selected";} ?>>Deshabilitado</option>
                        </select>
                        <label for="input_estado" class="select-label">Estado</label>
                    </div>
                </div>
                <hr>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="tipo_material" id="input_tipo_material" class="form-control" value="<?php echo $row ['tipo_material']; ?>" placeholder="Indique el tipo de material que es." required>
                        <label for="input_tipo_material" class=" control-label">Tipo de material</label>
                    </div>
                    <div class="form-floating col-md-4">
                        <input type="text" name="subtipo_material" id="input_subtipo_material" class="form-control" value="<?php echo $row ['subtipo_material']; ?>" placeholder="Indique el subtipo de material" required>
                        <label for="input_subtipo_material">Subtipo de material</label>
                    </div>
                </div>
                <hr>
                <br>
                <div class="row g-2 align-items-center">
                    <div class="form-floating col-md-4">
                        <input type="text" name="nombre" id="input_nombre" class="form-control" value="<?php echo $row ['nombre']; ?>" placeholder="Denominación común" required>
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
                    <textarea id="input_caracteristicas" name="caracteristicas" class="form-control" placeholder="Los detalles de como es" aria-describedby="caracteristicas_help" required><?php echo $row ['caracteristicas']; ?></textarea>
                    <label for="input_caracteristicas" class="control-label">Características del material</label>
                    <div id="caracteristicas_help" class="form-text">Tamaño, color, forma, material, etc</div>
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