<?php
 //Mostrar si hay errores
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 
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

<!-- Página Añadir Clase -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Añadir Clase</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    
    
</head>
<body>

        
        <p><b>*IMPORTANTE*</b></p>
        <p><b>Si existe otra clase que tenga asignado el mismo día de semana y hora, será reemplazada</b></p>
        <fieldset id='form_add_clase'>
        <legend>Añadir Clase</legend>
            <form method="POST" action="index_clases.php?action=addClase">

                <label for="dni_monitor">DNI monitor</label>
                <select id="dni_monitor" name="dni_monitor" required>

                    <?php
                    /* This PHP code block is reading data from a JSON file that contains information about
                    monitors.
                    Es necesario para rescatar los dni, que tenemos en el json para que el usuario no pueda insertar cualquier DNI */
                    $monitoresJson = "../../data/monitores.json";
                    if (file_exists($monitoresJson)) {
                        $jsonData = file_get_contents($monitoresJson);
                        $monitores = json_decode($jsonData, true); // Decodificamos el contenido JSON a un array asociativo
                        foreach ($monitores as $dni_monitor => $monitor) {
                            echo '<option value="' . $dni_monitor . '">'
                                . $dni_monitor . ' - ' . $monitor['nombre']
                                . '</option>';
                        }
                    } else {
                        echo '<option value="" disabled>No se encontraron monitores disponibles</option>';
                    }
                    ?>

                </select>
                <br><br>

                <label for="nombre_actividad">Nombre de la actividad</label>
                <input type="text" id="nombre_actividad" name="nombre_actividad" placeholder="ejemplos: MMA, muay thai..." required>
                <br><br>

                <label for="dia_semana">Día de la Semana</label>
                <select id="dia_semana" name="dia_semana" required>
                    <option value="lunes" selected>Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sabado">Sábado</option>
                </select>
                <br><br>

                <label for="hora_inicio">Hora de Inicio</label>
                <select id="hora_inicio" name="hora_inicio" required>
                    <option value="10:00" selected>10:00</option>
                    <option value="12:00">12:00</option>
                    <option value="16:00">16:00</option>
                    <option value="18:00">18:00</option>
                </select>
                <br>
                <div> 
                    <button type="submit">Añadir Clase</button>

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

                    //Imprime el mensaje de error si se ha capturado una excepción.

                    if (isset($_GET['msg'])) {
                    
                        echo "<p>".$_GET['msg']."</b></p>";
                    }

            ?>
            </form>
        
        <br>
        
        </fieldset>
       
        <br>
    
</body>
</html>
