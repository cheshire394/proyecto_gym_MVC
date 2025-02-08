<?php
 //Mostrar si hay errores
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);
 

// Verifica si existe una variable de sesión 'nombre'
session_start();
if (!isset($_SESSION['nombre'])) {
       // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
       header('location: ../login_recepcionista.php');
       exit(); 
   }


   require_once '../../controllers/controladorClases.php';

   //Metodos para controlar los valores introducidos en el formulario
   $monitores = ControladorClases::get_monitores(); 
   $horas_disponibles = ControladorClases::horas_disponibles(); 
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

        

        <fieldset>
        <legend>Añadir Clase</legend>
            <form method="POST" action="router_clases.php?action=addClase">

                <label for="dni_monitor">DNI monitor</label>
                <select id="dni_monitor" name="dni_monitor" required>
                    <?php foreach ($monitores as $monitor) : ?>
                        <option value="<?= htmlspecialchars($monitor['dni']) ?>">
                            <?= htmlspecialchars($monitor['nombre']) . " --- ". htmlspecialchars($monitor['dni']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <br><br>

                <label for="nombre_actividad">Nombre de la actividad</label>
                <input type="text" id="nombre_actividad" name="nombre_actividad" placeholder="ejemplos: MMA, muay thai..." required>
                <br><br>
           
                <label for="id_clase">Horario Disponible</label>
        
                <select id="id_clase" name="id_clase" required <?= empty($horas_disponibles) ? 'disabled' : '' ?>>
                    <?php if (empty($horas_disponibles)) : ?>
                        <option value="">Ninguna clase libre disponible</option>
                    <?php else : ?>
                        <?php foreach ($horas_disponibles as $id_clase) : ?>
                            <option value="<?= htmlspecialchars($id_clase) ?>">
                                <?= htmlspecialchars($id_clase) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                        
                <br>
                <div> 
                    <button type="submit" name='addClase'>Añadir Clase</button>

                              <!--volver a bienvenida-->
                            <div style="position: absolute; top: 73%; left: 52%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">

                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span style='color:white';>volver a página principal</span>
                                </a>
                                
                            </div>
      
                      </div> 

            <br>
            </form>
        
        <br>
        
        </fieldset>


        <?php

            //Imprime el mensaje de error si se ha capturado una excepción.

            if (isset($_GET['msg'])) {

                echo "<p>".$_GET['msg']."</b></p>";
            }

        ?>
       
        <br>
    
</body>
</html>
