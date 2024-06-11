<?php

session_start();
include_once ("include/conexion.php");

// include ("include/conexion.php");
$error_login = false;

// Si ha he iniciado sesión, existirá $_SESSION['usuario']
if (isset($_SESSION['usuario'])){

    $_SESSION['URL'] = $_SERVER['REQUEST_URI']; //Recuerda la URL actual para acciones futuras. Si se accede desde aqui a ciertas funciones
    $usuario = $_SESSION['usuario'];
    $db_usuarios = mysqli_query ($conexion, "SELECT permisos, estado FROM usuarios WHERE usuario='$usuario'");     //Se consulta los datos referentes al usuario actual

    // Verificar si se encontraron resultados
    if (mysqli_num_rows($db_usuarios) == 0) {
        header("Location: /php/login/logout.php?error=usuario_inexistente"); //Redirige a logout
        die;
    }else{
        $usuario_completo = mysqli_fetch_assoc($db_usuarios);
    
        if ($usuario_completo['estado'] == 6){      //Verfificar si el usuario esta deshabilitado
            header("Location: /php/login/logout.php?error=usuario_deshabilitado"); //Redirige a logout
            die;
        }else{
            $_SESSION ['permisos'] = $usuario_completo['permisos'];
            $error_login = true;
        }
    }
    
}else{

    header("Location: /php/login/logout.php?error=usuario_no_logado"); //Redirige a logout
    die;
}

?>
