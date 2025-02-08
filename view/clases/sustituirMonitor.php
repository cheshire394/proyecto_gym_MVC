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

   require_once '../../controllers/controladorClases.php';

   //Metodos para controlar los valores introducidos en el formulario
   $monitores = ControladorClases::get_monitores(); 
   $horas_ocupadas = ControladorClases::horas_ocupadas(); 
 
?>

<!-- Página Añadir Clase -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Añadir Clase</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    <style>
        body{
            background-image: url('../../img/fondo5.jpg');
            background-repeat: no-repeat;
            background-size:cover;
            
        }

        #sustituirMonitor{
            float: left;
        }
    </style>
</head>
<body>
   
   
    <fieldset id='sustituirMonitor'>
        <h1 style='text-align: center';>sustituir monitor</h1>
        <form method="POST" action="router_clases.php?action=sustituirMonitor">

            <label for="dni_monitor">DNI nuevo monitor</label>
            <select id="dni_monitor" name="dni_monitor" required>
                    <?php foreach ($monitores as $monitor) : ?>
                        <option value="<?= htmlspecialchars($monitor['dni']) ?>">
                            <?= htmlspecialchars($monitor['nombre']) . " --- ". htmlspecialchars($monitor['dni']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

           
                <label for="id_clase">Selecciona la clase</label>
              
                <select id="id_clase" name="id_clase" required <?= empty($horas_ocupadas) ? 'disabled' : '' ?>>
                    <?php if (empty($horas_ocupadas)) : ?>
                        <option value="">Ninguna clase libre disponible</option>
                    <?php else : ?>
                        <?php foreach ($horas_ocupadas as $id_clase) : ?>
                            <option value="<?= htmlspecialchars($id_clase) ?>">
                                <?= htmlspecialchars($id_clase) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <p style='color:grey;'><small>nota* las clases libres no aparecen para seleccionar</small></p>
                        
                            
            <div>
            <button type="submit" name='sustituir_monitor'>sustituir monitor</button>
           
                             <!--volver a bienvenida-->
                             <div style="position: absolute; top: 80%; left: 50%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">

                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span style='color:white';>volver a página principal</span>
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
    
   
</body>
</html>
