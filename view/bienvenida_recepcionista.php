<!-- Página con el bienvenida/inicio de un recepcionista.
     Muestra el menú de lo que puede hacer -->

<!-- Código PHP -->
<?php
/* This PHP code block is responsible for managing the user session. Here's a breakdown of what it
    does: */
// Inicia una nueva sesión o reanuda la sesión existente
session_start();

// Verifica si existe una variable de sesión 'nombre'
if (isset($_SESSION['nombre'])) {
    // Si existe, asigna su valor a la variable $nombre (usando el operador de coalescencia nula por seguridad)
    $nombre = $_SESSION['nombre'] ?? "";
} else {
    // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
    // header('location: login_recepcionista.php'); 
}
?>


<!-- HTML con el formulario
    The provided code is an HTML section that includes a form for managing user sessions in a PHP
    application. Here's a breakdown of what it does: -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio recepción</title>
</head>

<body>
    <h1 style='color:rgb(75, 125, 218); font-size:43px;'>Bienvenido <?php echo $nombre ?></h1>
    <h3 style="color:grey">Aquí tienes disponible todas las tareas de gestión del gimnasio:</h3>

    <fieldset>
        <!-- Gestión Clases-->
        <legend style='color:rgb(75, 125, 218); font-size:25px;'><b>Gestión de las clases del gimnasio</b></legend>

        <fieldset>
            <legend style='color:rgb(75, 125, 218); font-size:25px;'>Añadir una clase o Modificar su monitor</legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/clases/addClase.php">Añadir clase</a></li>
                <li><a href="/proyecto_gym_MVC/view/clases/sustituirMonitor.php">Sustituir monitor</a></li>
            </ul>
        </fieldset>
        <fieldset>
            <legend style='color:rgb(75, 125, 218); font-size:25px;'>Horario del gimnasio</legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/clases/verClases.php">Mostrar todas las clases</a></li>
                <li><a href="/proyecto_gym_MVC/view/clases/clasesFiltro.php">Mostrar clases con filtro</a></li>
            </ul>
        </fieldset>
        <fieldset>
            <legend style='color:rgb(75, 125, 218); font-size:25px;'>Eliminar</legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/clases/eliminarDisciplina.php">Eliminar disciplina</a></li>
                <li><a href="/proyecto_gym_MVC/view/clases/eliminarClase.php">Eliminar una clase del horario</a></li>
            </ul>
        </fieldset>
    </fieldset>
    <br>
    <fieldset>
        <!-- Gestión Socios-->
        <legend style='color:rgb(75, 125, 218); font-size:25px;'><b>Gestión de Socios</b></legend>
        <fieldset>
            <legend style='color:rgb(75, 125, 218); font-size:25px;'>Añadir y Modificar</legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/socios/addSocio.php">Registrar nuevo socio</a></li>
                <li><a href="/proyecto_gym_MVC/view/socios/modificarSocio.php">Modificar socio</a></li>
            </ul>
        </fieldset>
        <fieldset>
            <legend style='color:rgb(75, 125, 218); font-size:25px;'>Buscar socio</legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/socios/verSocio.php">Buscar socio / socios</a></li>
            </ul>
        </fieldset>
        <fieldset>
            <legend style='color:rgb(75, 125, 218); font-size:25px;'>Eliminar</legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/socios/eliminarSocio.php">Eliminar socio</a></li>
            </ul>
        </fieldset>
    </fieldset>
    <br>
    <fieldset>
        <!-- Gestión Monitores-->

            <legend style='color:rgb(75, 125, 218); font-size:25px;'><b>Condiciones de los monitores</b></legend>
            <ul>
                <li><a href="/proyecto_gym_MVC/view/trabajadores/verTrabajadores.php">Ver los datos de los trabajadores</a></li>
                
            </ul>
       
    </fieldset>
    <!-- Botón para cerrar sesión -->
    <fieldset>

        <form method="POST" action="../index.php?action=logout">
            
            <button type="submit">Cerrar Sesión</button>
        </form>

    </fieldset>
</body>

</html>