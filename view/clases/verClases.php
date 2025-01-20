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

<?php

require_once '../../controllers/controladorClases.php';
// Obtener el horario organizado desde el controlador
$horario = ControladorClases::mostrar_todas_Clases();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario del Gimnasio</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    
</head>
<body>
   
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
                <th>Domingo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $horas = ["10:00", "12:00", "16:00", "18:00"]; 
            $dias = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];

            foreach ($horas as $hora) {
                echo "<tr>";
                echo "<td class='hora'>$hora</td>";

                foreach ($dias as $dia) {
                    if (isset($horario[$dia][$hora])) {
                        $clase = $horario[$dia][$hora];
                        echo "<td class='ocupada'>{$clase['nombre_actividad']}<br><small>Monitor: {$clase['dni_monitor']}</small></td>";
                    } else {
                        echo "<td class='libre'>Libre</td>";
                    }
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
   
    

                          <div id='divEnlace'>
                                  <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                                  <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                  </svg>
                                  <span>volver al menú principal</span>
                                  </a>
                          </div>
      
                     
    <?php

            //Mensaje de exito de las acciones ejecutadas desde otras páginas: 
        if (isset($_GET['msg']) && $_GET['msg'] == 'addClase') {
           
            echo "<p style='color: green; font: size 23px;'><b>La clase ha sido añadida correctamente, el horario  está actualizado</b></p>";
        }


        if (isset($_GET['msg']) && $_GET['msg'] == 'sustituido') {
           
            echo "<p style='color: green; font: size 23px;'><b>El monitor de la clase ha sido sustituido correctamente, el horario está actualizado</b></p>";
            
            }

            
        if (isset($_GET['msg']) && $_GET['msg'] == 'eliminadaDisciplina') {
           
            echo "<p style='color: green; font: size 23px;'><b>La disciplina ha sido eliminada correctamente, el horario está actualizado</b></p>";
                
            }

        if (isset($_GET['msg']) && $_GET['msg'] == 'eliminarClase') {
           
            echo "<p style='color: green; font: size 23px;'><b>La clase ha sido eliminada correctamente, el horario está actualizado</b></p>";
                    
        }         

    ?>
</body>
</html>
