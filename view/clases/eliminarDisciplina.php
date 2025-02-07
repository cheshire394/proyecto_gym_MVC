<?php
/* This PHP code block is responsible for managing the user session. Here's a breakdown of what it
    does: */
// Inicia una nueva sesión o reanuda la sesión existente
session_start();

// Verifica si existe una variable de sesión 'nombre'
if (!isset($_SESSION['nombre'])) {
       // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
       header('location: ../login_recepcionista.php');
       exit(); 
   }
 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Disciplina</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
</head>
<body>

<p><small>importante* Esta página no está diseñada para eliminar una clase del horario, elimina todas las clases que forman parte de la disciplina elegida</small></p>

    <fieldset>
        <legend>Selecciona la disciplina que deseas eliminar </legend>
        <form method="POST" action="router_clases.php?action=eliminarDisciplina">
            <label for="disciplina">nombre actividad</label>
            <select id="disciplina" name="nombre_actividad" required>
                <option value="taekwondo">Taekwondo</option>
                <option value="karate">Karate</option>
                <option value="boxeo">Boxeo</option>
                <option value="MMA">MMA</option>
                <option value="kickboxing">Kickboxing</option>
                <option value="moay thai">Moay Thai</option>
                <option value="capoeira">Capoeira</option>
                <option value="judo">Judo</option>
                <option value="aikido">Aikido</option>
            </select>
            <br>
            <div>
                
            <button type="submit" name='eliminar_diciplina'>Eliminar disciplina</button>
           
            <div id='divEnlace'>
                    <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>volver al menú principal</span>
                    </a>
            </div>
            </div>
            <br>
            <?php
            //Si la clases no se filtran correctamente, mostramos el mensaje de error capturado en la excepcion: 
            if (isset($_GET['msg'])) {
                $mensaje = htmlspecialchars($_GET['msg']);
                echo "<p style='display: inline-block;'><b>$mensaje</b></p>";
            }
            ?>
        </form>
    </fieldset>
    <br>
   
    
</body>
</html>
