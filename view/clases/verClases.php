<?php
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
$horario = ControladorClases::horario(); 

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



<h1>Horario del gimnasio</h1>   

<!--enlace a bienvenida-->
<div id='divEnlace'>
        <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>volver al menú principal</span>
        </a>
</div>

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
            // Definir las horas y los días para las filas y columanas de la tabla
            $horas = ["10:00", "12:00", "16:00", "18:00"]; 
            $dias = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];

            foreach ($horas as $hora) {
                echo "<tr>";
                echo "<td class='hora'>$hora</td>";
            
                foreach ($dias as $dia) {
                    echo "<td>";
            
                    //// Convertir a formato HH:MM:SS, porque en la BBDD se registran así
                    $hora = date("H:i:s", strtotime(trim($hora))); 
            
        
                    // Verificar si la clave existe
                    if (isset($horario[$dia][$hora])) {
                       
                        echo "<p>".$horario[$dia][$hora]->nombre_actividad."<br><small>Monitor: ".$horario[$dia][$hora]->dni_monitor."</small></p>";
                    } else {
                        echo "<p>Libre</p>";
                    }
            
                    echo "</td>";
                }
                echo "</tr>";
            }
            
            ?>
        </tbody>
    </table>
   
  
      
    <?php
    // Mensajes de éxito o error de las acciones ejecutadas desde otras páginas:
    if (isset($_GET['msg'])) {
      
            echo "<p>".$_GET['msg']."</p>";
        
    }
    ?>
</body>
</html>
