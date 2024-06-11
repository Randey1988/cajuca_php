<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../profiles/profiles_actividades.php?codigo=$_GET[codigo]");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Materiales de la Actividad</title>
 
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
			<h2>Datos de las actividades &raquo; Editar Materiales de la Actividad</h2>
			<br>
			<?php
            // cargar y actualizar los datos modificados de los materiales de la actividad
			if(isset($_POST['edit'])){
                $error_insert = true;
                $codigo_actividad	        = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));//Escanpando caracteres
                $codigos_materiales    = ($_POST["materiales"]);
                $cantidad_materiales   = ($_POST["cantidad_materiales"]);
                $db_delete = mysqli_query($conexion, "DELETE FROM materiales_actividades WHERE codigo_actividades='$codigo_actividad'") or die(mysqli_error($conexion));
                if($db_delete){
                    $error_insert = false;
                    $pos_array_cantidad_materiales = 0;
                    // Insertar una linea en materiales_actividades por cada monitor seleccionado
                    foreach ($codigos_materiales as $codigo_material){
                        // $codigo_monitor	= mysqli_real_escape_string($conexion,(strip_tags($_POST["actividades"],ENT_QUOTES)));//Escanpando caracteres 
                        $cantidad_material = $cantidad_materiales [$pos_array_cantidad_materiales];
                        $db_insert = mysqli_query($conexion, "INSERT INTO materiales_actividades (codigo_actividades, codigo_materiales, cantidad_materiales) VALUES('$codigo_actividad', '$codigo_material', '$cantidad_material')") or die(mysqli_error($conexion));
                        if($db_insert){
                        }else{
                            $error_insert = true; 
                        }
                        $pos_array_cantidad_materiales++;
                    }

                    // Mensajes y acciones en caso de éxito o errores
                    if($error_insert == true){
                        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
                    }else{    
                        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos de los materiales de la actividad han sido guardados con éxito.</div>';
                        if(isset($_GET['funcion']) == 'add' ){
                            header ("Location: ../edit/add_actividades.php'");
                            die;
                        }
                    }
                }else{
                    echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error al reiniciar el listado!!! No se pudo guardar los datos.</div>';
                }

            }

            // Obtener codigo evento
            if(isset($_GET['codigo'])){
                $codigo_actividad= $_GET["codigo"];
                $db_actividad = mysqli_query($conexion, "SELECT nombre FROM actividades WHERE codigo like '$codigo_actividad'");

            }else{
                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error en la URL!!! Vuelva a cargar desde el listado de eventos.</div>';
                header("Location: ../list/list_actividades.php");
            }
            if(mysqli_num_rows($db_actividad) == 0){
                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error en la DB!!! No se encuentra el evento con el código indicado.</div>';
            }else{
                $row_actividad = mysqli_fetch_assoc($db_actividad);
            }
			?>
            
            <h3>Actividad con código <?php echo $codigo_actividad.': '.$row_actividad ['nombre']; ?></h3>
            <hr>

            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">

                <!-- Acción -->
                <div class="row justify-content-between">
                    <div class= "form-group col-6"><h4>Seleccione los materiales de la actividad y su cantidad:</h4></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3">
                        <input type="submit" name="edit" class="btn btn-sm btn-success" value="Guardar datos">
                        <a href="../list/list_actividades.php" class="btn btn-sm btn-danger"><i class="material-icons">refresh</i> Regresar</a>
                    </div>
                </div>
                <br>

                <!-- Tabla de monitores -->
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <th></th>
                        <th>No</th>
                        <th>Código</th>
                        <th>Tipo y Subtipo Material</th>
                        <th>Nombre</th>
                        <th>Características</th>
                        <th>Cantidad Material</th>
                        <th>Estado</th>
                    </tr>
                    <?php
                    
                    $sql_materiales = mysqli_query($conexion, "SELECT * FROM materiales WHERE estado BETWEEN 1 AND 3 ORDER BY codigo ASC");
                    $sql_materiales_actividades = mysqli_query($conexion, "SELECT * FROM materiales_actividades WHERE codigo_actividades like'$codigo_actividad' ORDER BY codigo_materiales ASC");
                    $codigos_materiales_en_actividad=[];
                    $cantidades_materiales_en_actividad=[];
                    while($row_materiales_actividades = mysqli_fetch_assoc($sql_materiales_actividades)){
                        $codigos_materiales_en_actividad[]=$row_materiales_actividades['codigo_materiales'];
                        $cantidades_materiales_en_actividad[]=$row_materiales_actividades['cantidad_materiales'];
                    }
                    $pos_array_cantidad_materiales = 0;
                    $cantidad_material_en_actividad = 0;

   
                    if(mysqli_num_rows($sql_materiales) == 0){
                        echo '<tr><td colspan="7">No hay datos.</td></tr>';
                    }else{
                        $no = 1;
                        // Por cada linea de actividades existentes, hacer una fila en la tabla
                        while($row = mysqli_fetch_assoc($sql_materiales)){
                            $cantidad_material_en_actividad = 0;
                            echo '<tr>
                                <td>
                                <input type="checkbox" class="form-check-input" name="materiales[]" value="'.$row['codigo'].'" id="actividad_'.$row['codigo'].'"
                                onChange="comprobar'.$row['codigo'].'(this);"';

                            // Comprobar si la actividad esta ya seleccionado, y poner el check según el resultado
                            foreach ($codigos_materiales_en_actividad as $codigos_material_en_actividad){
                                if($row['codigo'] == $codigos_material_en_actividad){
                                    echo ' checked';
                                    $cantidad_material_en_actividad = $cantidades_materiales_en_actividad[$pos_array_cantidad_materiales];
                                    $pos_array_cantidad_materiales++;
                                }
                            }

                            echo '></td>
                                <td>'.$no.'</td>
                                <td><a href="../profiles/profiles_materiales.php?codigo='.$row['codigo'].'" title="Ver perfil del material" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</a></td>
                                <td>'.$row['tipo_material'].' - '.$row['subtipo_material'].'</td>
                                <td><a href="../profiles/profiles_materiales.php?codigo='.$row['codigo'].'" title="Ver perfil del material" target="_blank" rel="noopener noreferrer">'.$row['nombre'].'</a></td>
                                <td>'.$row['caracteristicas'].'</td>
                                <td><input type="number" class="form-control" name="cantidad_materiales[]" 
                                    value="'.$cantidad_material_en_actividad.'" id="cantidad_material_'.$row['codigo'].'"';
                            
                            if ($cantidad_material_en_actividad == 0){
                                echo ' disabled';
                            }
                            echo '/></td>';

                            echo '
                                <script>
                                function comprobar'.$row['codigo'].'(objeto){
                                    if (objeto.checked){
                                        document.getElementById("cantidad_material_'.$row['codigo'].'").disabled = false;
                                        document.getElementById("cantidad_material_'.$row['codigo'].'").value = "1";
                                    } else{
                                        document.getElementById("cantidad_material_'.$row['codigo'].'").disabled = true;
                                        document.getElementById("cantidad_material_'.$row['codigo'].'").value = "0";
                                    }
                                }
                                </script>';

                            echo '
                            <td>';
                            if($row['estado'] == '1'){
                                echo '<span class="label label-success">En Almacén</span>';
                            }
                            else if ($row['estado'] == '2' ){
                                echo '<span class="label label-info">Sin Stock</span>';
                            }
                            else if ($row['estado'] == '4' ){
                                echo '<span class="label label-warning">En Revisión</span>';
                            }
                            else if ($row['estado'] == '5' ){
                                echo '<span class="label label-info">Subcontratado</span>';
                            }
                            else if ($row['estado'] == '6' ){
                                echo '<span class="label label-warning">Deshabilitada</span>';
                            }
                            echo '
                                </td>
                            </tr>
                            ';
                            $no++;
                        }
                    }
                    ?>
                </table>
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