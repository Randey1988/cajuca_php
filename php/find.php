<?php
	// Iniciamos una sesión PHP o reanudamos la sesión actual si existe una
	session_start();

        if ($_SERVER["REQUEST_METHOD"] == "GET") {

        if (isset($_GET['categoria']) && isset($_GET['buscar'])) {
            $categoria = $_GET['categoria'];
            $buscar = $_GET['buscar'];
            if($categoria == "Actividad"){
                header("Location: list/list_actividades.php?buscar=$buscar"); //Redirige a logout
                die;
            }elseif($categoria == "Cliente"){
                header("Location: list/list_clientes.php?buscar=$buscar"); //Redirige a logout
                die;
            }elseif($categoria == "Evento"){
                header("Location: list/list_eventos.php?buscar=$buscar"); //Redirige a logout
                die;
            }elseif($categoria == "Monitor"){
                header("Location: list/list_monitores.php?buscar=$buscar"); //Redirige a logout
                die;
            }

        }
    }
    header("Location: ../home.php"); //Redirige a logout
    die;
?>