<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificación de sesión
session_start(); 
if (!isset($_SESSION['nombre'])) {
    header('location: ../login_recepcionista.php');
    exit(); 
}

require_once '../../controllers/controladorClases.php';

// Obtener todas las clases con gestión de excepciones
$clases = controladorClases::clasesSocios();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Clases</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">

</head>

<body>
    <!-- Mensajes de éxito o error -->
    <?php if(isset($_GET['msg'])) { echo "<p>" . htmlspecialchars($_GET['msg']) . "</p>"; } ?>

  

    <?php if (!empty($clases)) { ?>



        <h1>Clases del gimnasio</h1>

        <!--volver a bienvenida-->
        <div style="position: absolute; top: 27%; left: 24%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
            <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">

                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span style='color:white';>volver a página principal</span>
            </a>
            
        </div>

        
        <!-- Flecha para bajar la página -->
        <div id="scroll-to-bottom" style="text-align: center; margin: 30px 0;">
            <a href="#bottom" class="scroll-button">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19 9l-7 7-7-7"/>
                </svg>
            </a>
           
        </div>

        <ul>
            <?php foreach ($clases as $clase) { ?>
                <li>
                    <h2><?= htmlspecialchars($clase['id_clase'] . " - " . $clase['nombre_actividad'] . " (Monitor: " . $clase['nombre_monitor'] . ")") ?></h2>
                    <ol>
                        <?php if (!empty($clase['socios'])) { 
                            foreach ($clase['socios'] as $socio) { ?>
                                <li><?= htmlspecialchars($socio) ?></li>
                        <?php } 
                        } else { ?>
                            <li>No hay socios inscritos</li>
                        <?php } ?>
                    </ol>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No hay clases disponibles</p>
    <?php } ?>

    <br>

 
<!-- Flecha para subir al inicio - colocada al final del contenido -->
<div id="scroll-to-top"  style="text-align: center; margin: 30px 0;">
    <a href="#top" class="scroll-button">
        <svg class="scroll-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </a>
</div>

<div id="bottom"></div>


  
</div>
</body>
</html>
