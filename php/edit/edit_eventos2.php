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
	<title>Editar Trabajadores del Evento</title>
 
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
			<h2>Datos de los eventos &raquo; Editar Trabajadores del Evento</h2>
			<br>
 
			<?php

            // cargar y actualizar los datos editados de los monitores trabajadores
			if(isset($_POST['edit'])){
                $error_insert = true;
                
                $codigo_evento	    = mysqli_real_escape_string($conexion,(strip_tags($_GET["codigo"],ENT_QUOTES)));//Escanpando caracteres
                $fecha_evento	    = mysqli_real_escape_string($conexion,(strip_tags($_GET["fecha_evento"],ENT_QUOTES)));//Escanpando caracteres
                $codigos_monitores	= ($_POST["monitores"]);
                $db_delete = mysqli_query($conexion, "DELETE FROM eventos_monitores WHERE codigo_eventos='$codigo_evento'") or die(mysqli_error($conexion));
                if($db_delete){
                    $error_insert = false;
                    // Insertar una linea en eventos_monitores por cada monitor seleccionado
                    foreach ($codigos_monitores as $codigo_monitor){
                        // $codigo_monitor	= mysqli_real_escape_string($conexion,(strip_tags($_POST["monitores"],ENT_QUOTES)));//Escanpando caracteres 
                        
                        $db_insert = mysqli_query($conexion, "INSERT INTO eventos_monitores (codigo_eventos, codigo_monitores, fecha_inicio_evento) VALUES('$codigo_evento', '$codigo_monitor', '$fecha_evento')") or die(mysqli_error($conexion));
                        if($db_insert){

                        }else{
                            $error_insert = true; 
                        }
                    }

                    // Mensajes y acciones en caso de éxito o errores
                    if($error_insert == true){
                        echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>¡¡¡Error!!! No se pudo guardar los datos.</div>';
                    }else{    
                        echo '<div class="alert alert-success alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Los datos de los monitores del evento han sido guardados con éxito.</div>';
                        if(isset($_GET['funcion']) == 'add' ){
                            header ("Location: ../edit/edit_eventos3.php?funcion='add'&codigo='.$codigo_evento'");
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
                $db_eventos = mysqli_query($conexion, "SELECT * FROM eventos WHERE codigo like '$codigo_evento'");

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
                    <div class= "form-group col-6"><h4>Seleccione los trabajadores del evento:</h4></div>
                <!-- Botones de accion -->
                    <div class="form-group col-3">
                        <input type="submit" name="edit" class="btn btn-sm btn-success" value="Guardar datos">
                        <a href="../list/list_eventos.php" class="btn btn-sm btn-danger"><i class="material-icons">refresh</i> Regresar</a>
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
                        <th>Nombre completo</th>
                        <th>Fecha de nacimiento</th>
                        <th>Población</th>
                        <th>Teléfono</th>
                        <th>Titulo Monitor</th>
                        <th>Titulo Coordinador</th>
                        <th>Estado</th>
                    </tr>
                    <?php
                    
                    $sql_monitores = mysqli_query($conexion, "SELECT * FROM monitores WHERE estado BETWEEN 1 AND 3 ORDER BY codigo ASC");
                    $sql_eventos_monitores = mysqli_query($conexion, "SELECT codigo_monitores FROM eventos_monitores WHERE codigo_eventos like'$codigo_evento' ORDER BY codigo_monitores ASC");
                    $codigos_monitores_en_evento=[];
                    while($row_eventos_monitores = mysqli_fetch_assoc($sql_eventos_monitores)){
                        $codigos_monitores_en_evento[]=$row_eventos_monitores['codigo_monitores'];
                    }

                    if(mysqli_num_rows($sql_monitores) == 0){
                        echo '<tr><td colspan="11">No hay datos.</td></tr>';
                    }else{
                        $no = 1;
                        // Por cada linea de monitores existentes, hacer una fila en la tabla
                        while($row = mysqli_fetch_assoc($sql_monitores)){

                            echo '<tr>
                            <td>
                            <input type="checkbox" class="form-check-input" name="monitores[]" value="'.$row['codigo'].'" id="monitor_'.$row['codigo'].'"
                            ';
                            // Comprobar si el monitor esta ya seleccionado, y poner el check según el resultado
                            foreach ($codigos_monitores_en_evento as $codigo_monitor_en_evento){
                                if($row['codigo'] == $codigo_monitor_en_evento){
                                    echo ' checked';
                                }
                            }

                            echo '></td>
                                <td>'.$no.'</td>
                                <td><a href="../profiles/profiles_monitores.php?codigo='.$row['codigo'].'" title="Ver Perfil del Monitor" target="_blank" rel="noopener noreferrer">'.$row['codigo'].'</td>
                                <td><a href="../profiles/profiles_monitores.php?codigo='.$row['codigo'].'" title="Ver Perfil del Monitor" target="_blank" rel="noopener noreferrer">'.$row['nombre'].' '.$row['apellidos'].'</a></td>
                                <td>'.$row['fecha_nac'].'</td>
                                <td>'.$row['poblacion'].'</td>
                                <td>'.$row['telefono'].'</td>
                                <td><span class="label ';

                                if($row['tit_monitor'] == false){
                                    echo 'label-warning">No';
                                }else if ($row['tit_monitor'] == true ){
                                    echo 'label-success">Si';
                                }
                            echo '</span></td>
                                <td><span class="label ';

                                if($row['tit_coordinador'] == false){
                                    echo 'label-warning">No';
                                }
                                else if ($row['tit_coordinador'] == true ){
                                    echo 'label-success">Si';
                                }
                            echo '</span></td>
                                <td>';
                                if($row['estado'] == '1'){
                                    echo '<span class="label label-success">Fijo</span>';
                                }
                                else if ($row['estado'] == '2' ){
                                    echo '<span class="label label-info">En evento</span>';
                                }
                                else if ($row['estado'] == '3' ){
                                    echo '<span class="label label-success">Libre</span>';
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
    <script>
    // $('.date').datepicker({
    //     format: 'yyyy-mm-dd',
    // })
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