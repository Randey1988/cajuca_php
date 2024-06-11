<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary nav_color">
  <div class="container-fluid">
    <a class="navbar-brand" href="/home.php">
        <img src="/media/img/grafismo.jpg" alt="CAJUCA" width="250" height="50">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse nav-filling" id="navbarNavDropdown">
      <ul class="navbar-nav nav-filling .nav-justified me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="/home.php">Inicio</a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Eventos
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/php/list/list_eventos.php">Ver listado</a></li>
            <?php    
              if ($_SESSION ['permisos'] > 1){
                echo '<li><a class="dropdown-item" href="/php/add/add_eventos.php">Agregar Nuevos Eventos</a></li>';
              }
            ?>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Monitores
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/php/list/list_monitores.php">Ver listado</a></li>
            <?php    
              if ($_SESSION ['permisos'] > 1){
                echo '<li><a class="dropdown-item" href="/php/add/add_monitores.php">Agregar Nuevos Monitores</a></li>';
              }
            ?>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Clientes
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/php/list/list_clientes.php">Ver listado</a></li>
            <?php    
              if ($_SESSION ['permisos'] > 1){
                echo '<li><a class="dropdown-item" href="/php/add/add_clientes.php">Agregar Nuevos Clientes</a></li>';
              }
            ?>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Actividades
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/php/list/list_actividades.php">Ver listado</a></li>
            <?php    
              if ($_SESSION ['permisos'] > 1){
                echo '<li><a class="dropdown-item" href="/php/add/add_actividades.php">Agregar Nuevas Actividades</a></li>';
              }
            ?>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Materiales
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/php/list/list_materiales.php">Ver listado</a></li>
            <?php    
              if ($_SESSION ['permisos'] > 1){
                echo '<li><a class="dropdown-item" href="/php/add/add_materiales.php">Agregar Nuevos Materiales</a></li>';
              }
            ?>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Utilidades
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/php/tools/fechas_monitores.php">Monitores en activo</a></li>
            <li><a class="dropdown-item" href="/php/tools/informe_eventos.php">Informe de eventos</a></li>
            <li><a class="dropdown-item" href="/php/tools/fechas_eventos.php">Período de eventos</a></li>
          </ul>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Usuario: <?php echo $_SESSION['usuario']; ?>
          </a>
          <ul class="dropdown-menu">
            <?php    
              if ($_SESSION ['permisos'] == 3){
                echo '<li><a class="dropdown-item" href="/php/login/login_list.php">Listado de usuarios</a></li>';
              }
            ?>
            <li><a class="dropdown-item" href="/php/login/login_edit.php?usuario=<?php echo $_SESSION['usuario']; ?>">Editar Datos</a></li>
            <li><a class="dropdown-item" href="/php/login/logout.php">Cerrar Sesion</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>
