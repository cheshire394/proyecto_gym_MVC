<?php

    session_start(); 
    if(isset($_SESSION['nombre'])) $nombre= $_SESSION['nombre'] ?? ""; 
    else{
        header('location: login_recepcionista.php'); 
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>bienvenida <?php echo $nombre?>,  aquí tienes disponible todas las tareas de gestión del gimnasio: </h1>
    
    
    <fieldset>
        <legend style="font-size: xx-large;">Gestión de clases o Disciplinas</legend>
        
        <ul>
            <li style="font-size: x-large;"><a href="/MVC2/view/clases/addClase.php">Añadir clase</a></li>
            <li style="font-size: x-large;"><a href="/MVC2/view/clases/sustituirMonitor.php">Sustituir monitor</a></li>
            <li style="font-size: x-large;"><a href="/MVC2/view/clases/verClases.php">Mostrar todas las clases</a></li>
            <li style="font-size: x-large;"><a href="/MVC2/view/clases/clasesFiltro.php">Mostrar clases con filtro</a></li>
            <li style="font-size: x-large;"><a href="/MVC2/view/clases/eliminarDisciplina.php">Eliminar disciplina</a></li>
        </ul>
    </fieldset>



    <form method="POST" action="../index.php?action=logout">
        <button type="submit">Cerrar Sesión</button>
    </form>
    
</body>
</html>