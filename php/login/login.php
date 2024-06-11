<!-- https://localhost/www/asir05.test/ACTIV10_CARRITO_COMPRAS/INCLUDES/login.php -->
<?php
// Iniciamos o reanudamos la sesión actual
session_start();

include_once '../../include/conexion.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Datos Personales del Usuario</title>

    <link href="../../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>
<body>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {

        // Se inicia aplicación de sistema para evitar CÓDIGO INYECTADO
        $usuario = htmlspecialchars($_POST['usuario']);
        $contrasena = htmlspecialchars($_POST['contrasena']);   
        // Consulta SQL para verificar el usuario y la contraseña
        $sql = mysqli_query ($conexion, "SELECT * FROM usuarios WHERE usuario='$usuario'");

        // Verificar si se encontraron resultados
        if (mysqli_num_rows($sql) == 0) {

        }else{
            $usuario_completo = mysqli_fetch_assoc($sql);
            $contrasena_hash_almacenado = $usuario_completo['contrasena'];
            if ($usuario == "cajuca_temp"){
                $contrasena_hash_almacenado = password_hash($contrasena_hash_almacenado, PASSWORD_DEFAULT);
            }
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Se verifica si el hash de la contraseña introducida coincide con el hash almacenado
            if (password_verify($contrasena, $contrasena_hash_almacenado)) {

                // Devuelve True: Inicio de sesión exitoso
                // Se registra en $_SESSION el nombre del usuario
                $_SESSION['usuario'] = $usuario;

                if ($usuario_completo['estado'] == 2){
                    $_SESSION['inicio'] = "renovar";
                    
                    echo '<h2><a href = "logout.php?error=usuario_incorrecto" title="Modificar contraseña">Pinche aquí si no se le redirecciona correctamente</a></h2>';
                    header("Location: login_edit.php?usuario=$usuario" );    //Esto hace que se vuelva a la página inicial.
                    die;
                }else{
                    // Se recopila la información del usuario
                    $_SESSION['inicio'] = true;
                    
                    echo '<h2><a href = "../../home.php?login='$usuario'" title="Home">LOGIN CORRECTO. Pinche aquí si no se le redirecciona correctamente</a></h2>';
                    header("Location: ../../home.php?login='$usuario'" );    //Esto hace que se vuelva a la página inicial.
                    die;
                }
            }else{
                $_SESSION['usuario'] = 0;
                session_destroy();
                // Inicio de sesión fallido
                // echo "<h2>Error: Usuario o contraseña incorrectos</h2>";
                echo '<h2><a href = "logout.php?error=usuario_incorrecto" title="Index: Error Contraseña">ERROR EN CONTRASEÑA. Pinche aquí si no se le redirecciona correctamente</a></h2>';
                header("Location: logout.php?error=usuario_incorrecto");
                die;
            }
        }
    } else {
        // echo "<p>No se recibieron datos válidos.</p>";
        header("Location: logout.php?error=datos_invalidos");
        die;
    }
}else {
    echo "<p>No se recibieron datos de POST.</p>";
    echo '<br><br>';
    echo '<h3><a href = "../../index.php" title="Volver a Inicio"></a></h3>';
}

// Se añade un enlace al index por si sale un mensaje de error.
echo '</body>';
echo '</html>';

// Cerrar conexión
$conexion->close();
?>
