<?php
// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
session_start();

if (isset($_SESSION['usuario'])){
    header("Location: home.php" );    //Esto hace que se vuelva a la página inicial.
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>

    	<!-- Bootstrap -->
    <link href="css/login_style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- MaterializeCSS Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

    <section class="vh-100" style="background-color: #508bfc;">
        <div class="content container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">  
                        <div class="card-body p-5 text-center">
                            <!-- <center><h1>Login</h1><br><br></center>   -->
                            <a href ="home.php" title="Saltar acceso"><img src="media/img/grafismo.jpg" class="img-fluid" alt="Sample image"></a>
                            <br>
                            <br>
                            <form class="form-horizontal" action="php/login/login.php" method="POST">
                                <?php
                                    if (isset($_GET['error'])){
                                        echo '<div class="content">';
                                        if ($_GET['error'] == 'usuario_deshabilitado'){
                                            echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>El usuario está deshabilitado.</div>';
                                        }elseif ($_GET['error'] == 'usuario_inexistente'){
                                            echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>El usuario no existe.</div>';
                                        }elseif ($_GET['error'] == 'usuario_incorrecto'){
                                            echo '<div class="alert alert-warning alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Usuario o contraseña inválida.</div>';
                                        }elseif ($_GET['error'] == 'datos_invalidos'){
                                            echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Datos inválidos.</div>';
                                        }elseif ($_GET['error'] == 'usuario_no_logado'){
                                            echo '<div class="alert alert-danger alert-dismissible"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true"></button>Usuario no logado de forma correcta.</div>';
                                        }
                                        echo '</div>';
                                    }
                                ?>                                
                                <!-- Email input -->
                                <div data-mdb-input-init class="form-floating col-md-10">
                                    <input type="text" id="usuario" name="usuario" class="form-control form-control-lg"
                                    placeholder="Introduzca el usuario" required/>
                                    <label class="form-label" for="usuario">Usuario</label>
                                </div>
                                <br>
                                <!-- Password input -->
                                <div data-mdb-input-init class="form-floating col-md-10">
                                    <input type="password" id="contrasena" name="contrasena" class="form-control form-control-lg"
                                    placeholder="Introduzca la contraseña" />
                                    <label class="form-label" for="contrasena">Contraseña</label>
                                </div>



                                <div class="text-center text-lg-start mt-4 pt-2">
                                    <input type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg"
                                    style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Acceder">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <!-- Footer -->
        <footer>
            <?php include("include/footer.php");?>
        </footer>
    </section>


</body>
</html>
