<?php
    // Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
    session_start();
    include("../../include/conexion.php");
    include_once ("../../include/session.php");

    if ($_SESSION ['permisos'] == 1){
        header ("Location: ../profiles/profiles_eventos.php?codigo=$_GET[codigo]");
        die;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Actividades del Evento</title>
 
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
			<h2>Datos de los eventos &raquo; Editar Actividades del Evento</h2>
			<br>

			<?php
            // Si se ha enviado los cambios del formulario para guardarlos, se ejecuta:
            if(isset($_POST['edit'])){
                $error_insert = true;
                $codigo_evento	        = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));//Escanpando caracteres
                $codigos_actividades    = ($_POST["actividades"]);
                $cantidad_actividades   = ($_POST["cantidad_actividades"]);
                $db_delete = mysqli_query($conexion, "DELETE FROM eventos_actividades WHERE codigo_eventos='$codigo_evento'") or die(mysqli_error($conexion));
                if($db_delete){
                    $error_insert = false;
                    $pos_array_cantidad_actividad = 0;
                    // Insertar una linea en eventos_actividades por cada monitor seleccionado
                    foreach ($codigos_actividades as $codigo_actividad){
                        // $codigo_monitor	= mysqli_real_escape_string($conexion,(strip_tags($_POST["actividades"],ENT_QUOTES)));//Escanpando caracteres 
                        $cantidad_actividad = $cantidad_actividades [$pos_array_cantidad_actividad];
                        $db_insert = mysqli_query($conexion, "INSERT INTO eventos_actividades (codigo_eventos, codigo_actividades, cantidad_actividades) VALUES('$codigo_evento', '$codigo_actividad', '$cantidad_actividad')") or die(mysqli_error($conexion));
                        if($db_insert){
                        }else{
                            $error_insert = true; 
                        }
                        $pos_array_cantidad_actividad++;
                    }

                    // Mensajes y acciones en caso de éxito o errores
                    if($error_insert == true){
                        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
                    }else{    
                        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos de las actividades del evento han sido guardadas con éxito.</div>';
                        if(isset($_GET['funcion']) == 'add' ){
                            header ("Location: ../edit/add_eventos.php'");
                            die;
                        }
                    }
                }else{
                    echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error al reiniciar el listado!!! No se pudo guardar los datos.</div>';
                }

            }

            // Obtener codigo evento
            if(isset($_GET['codigo'])){
                $codigo_evento= $_GET["codigo"];
                $db_eventos = mysqli_query($conexion, "SELECT nombre_evento FROM eventos WHERE codigo like '$codigo_evento'");

            }else{
                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error en la URL!!! Vuelva a cargar desde el listado de eventos.</div>';
                header("Location: ../list/list_eventos.php");
            }
            if(mysqli_num_rows($db_eventos) == 0){
                echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error en la DB!!! No se encuentra el evento con el código indicado.</div>';
            }else{
                $row_evento = mysqli_fetch_assoc($db_eventos);
            }
			?>

            <h3>Evento con código <?php echo $codigo_evento.': '.$row_evento ['nombre_evento']; ?></h3>
            <hr>

            <!-- Formulario -->
			<form class="form-horizontal" action="" method="post">

                <!-- Acción -->
                <div class="row justify-content-between">
                    <div class= "form-group col-6"><h4>Seleccione las Actividades del evento:</h4></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3">
                        <!-- <a href="../list/list_eventos.php" class="btn btn-sm btn-info"><i class="material-icons">refresh</i> Regresar</a>     -->
                        <input type="submit" name="edit" class="btn btn-sm btn-success" value="Guardar datos">
						<a href="../list/list_eventos.php" class="btn btn-sm btn-danger"><i class="material-icons">refresh</i> Regresar</a>
                    </div>
                </div>
                <br>

                <!-- Tabla de Actividades -->
                <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <th></th>
                        <th>No</th>
                        <th>Código</th>
                        <th>Tipo y Subtipo Actividad</th>
                        <th>Nombre</th>
                        <th>Jugadores</th>
                        <th>Precio/Hora</th>
                        <th>Cantidad en el Evento</th>
                        <th>Estado</th>
                    </tr>
                    <?php
                    
                    $sql_actividades = mysqli_query($conexion, "SELECT * FROM actividades WHERE estado BETWEEN 1 AND 5 ORDER BY codigo ASC");
                    $sql_eventos_actividades = mysqli_query($conexion, "SELECT * FROM eventos_actividades WHERE codigo_eventos='$codigo_evento' ORDER BY codigo_actividades ASC");
                    $codigos_actividades_en_evento=[];
                    $cantidades_actividades_en_evento=[];
                    while($row_eventos_actividades = mysqli_fetch_assoc($sql_eventos_actividades)){
                        $codigos_actividades_en_evento[]=$row_eventos_actividades['codigo_actividades'];
                        $cantidades_actividades_en_evento[]=$row_eventos_actividades['cantidad_actividades'];
                    }
                    $pos_array_cantidad_actividades = 0;
                    $cantidad_actividad_en_evento = 0;

                    if(mysqli_num_rows($sql_actividades) == 0){
                        echo '<tr><td colspan="11">No hay datos.</td></tr>';
                    }else{
                        $no = 1;
                        // Por cada linea de actividades existentes, hacer una fila en la tabla
                        while($row = mysqli_fetch_assoc($sql_actividades)){
                            $cantidad_actividad_en_evento = 0;
                            echo '<tr>
                                <td>
                                <input type="checkbox" class="form-check-input" name="actividades[]" value="'.$row['codigo'].'" id="actividad_'.$row['codigo'].'"
                                onChange="comprobar'.$row['codigo'].'(this);"';

                            // Comprobar si la actividad esta ya seleccionado, y poner el check según el resultado
                            foreach ($codigos_actividades_en_evento as $codigo_actividad_en_evento){
                                if($row['codigo'] == $codigo_actividad_en_evento){
                                    echo ' checked';
                                    $cantidad_actividad_en_evento = $cantidades_actividades_en_evento[$pos_array_cantidad_actividades];
                                    $pos_array_cantidad_actividades++;
                                }
                            }

                            echo '></td>
                                <td>'.$no.'</td>
                                <td><a href="../profiles/profiles_actividades.php?codigo='.$row['codigo'].'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</a></td>
                                <td>'.$row['tipo'].' - '.$row['subtipo'].'</td>
                                <td><a href="../profiles/profiles_actividades.php?codigo='.$row['codigo'].'" title="Ver perfil de la actividad" target="_blank" rel="noopener noreferrer"><i class="material-icons">golf_course</i>'.$row['nombre'].'</a></td>
                                <td>'.$row['jugadores'].'</td>
                                <td>'.$row['precio_hora'].' €/h</td>
                                <td><input type="number" class="form-control" name="cantidad_actividades[]" 
                                    value="'.$cantidad_actividad_en_evento.'" id="cantidad_actividad_'.$row['codigo'].'"';
                            
                            if ($cantidad_actividad_en_evento == 0){
                                echo ' disabled';
                            }
                            echo '/></td>';
                            echo '
                                <script>
                                function comprobar'.$row['codigo'].'(objeto){
                                    if (objeto.checked){
                                        document.getElementById("cantidad_actividad_'.$row['codigo'].'").disabled = false;
                                        document.getElementById("cantidad_actividad_'.$row['codigo'].'").value = "1";
                                    } else{
                                        document.getElementById("cantidad_actividad_'.$row['codigo'].'").disabled = true;
                                        document.getElementById("cantidad_actividad_'.$row['codigo'].'").value = "0";
                                    }
                                }
                                </script>';
                            echo '
                                <td>';
                                if($row['estado'] == '1'){
                                    echo '<span class="label label-success">Activo</span>';
                                }
                                else if ($row['estado'] == '2' ){
                                    echo '<span class="label label-info">Inactivo</span>';
                                }
                                else if ($row['estado'] == '4' ){
                                    echo '<span class="label label-warning">En Revisión</span>';
                                }
                                else if ($row['estado'] == '5' ){
                                    echo '<span class="label label-info">Subcontratada</span>';
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