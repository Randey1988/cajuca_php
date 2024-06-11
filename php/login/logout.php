<?php
// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
session_start();

if (isset($_SESSION['usuario'])){
    include_once '../../include/conexion.php';

    $usuario = $_SESSION['usuario'];
    $sql = mysqli_query ($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario'");

    // Verificar si se encontraron resultados
    if (mysqli_num_rows($sql) == 0) {
    }else{
        $usuario_completo = mysqli_fetch_assoc($sql);
        if ($usuario_completo['estado'] == 5){
            $estado = 6;
            $db_update = mysqli_query($conexion, "UPDATE usuarios SET estado='$estado'") or die(mysqli_error($conexion));
        }
    }
}

// Destruimos todos los datos registrados en la sesión actual
// Esto incluye borrar todas las variables de sesión y eliminar la cookie de sesión asociada al cliente
session_destroy();

if (isset($_GET['error'])){
    $error = $_GET['error'];
    $usuario_error = $_GET['usuario'];
    // Enviamos una cabecera HTTP para redirigir al usuario a la página de inicio del sitio web (index.php)
    header("Location: ../../index.php?error=$error&usuario=$usuario_error");
}else{
    // Enviamos una cabecera HTTP para redirigir al usuario a la página de inicio del sitio web (index.php)
    header('Location: ../../index.php');
}

// Cerrar conexión
$conexion->close();

// Detenemos la ejecución del script de forma inmediata
exit;
?>
