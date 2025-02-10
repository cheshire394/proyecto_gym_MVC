<?php

session_start();

// Verifica si existe una variable de sesión 'nombre'
if (!isset($_SESSION['nombre'])) {
       // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
       header('location: ../index.php');
       exit(); 
   }

   require_once '../../controllers/controladorClases.php';

   //Metodo para controlar los valores introducidos en el formulario
   $horas_ocupadas = ControladorClases::horas_ocupadas(); 
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
</head>
<body>

    <fieldset>
        <legend>Selecciona la clase a eliminar </legend>

    <form  method="post" action="router_clases.php?action=eliminarClase">
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

                        <!--boton para eliminar la clase-->
                        <button type="submit" name='eliminar_clase'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 23 23">
                                <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2" 
                                    fill="none" 
                                    stroke="currentColor" 
                                    stroke-width="2" 
                                    stroke-linecap="round" 
                                    stroke-linejoin="round"/>
                                <line x1="10" y1="11" x2="10" y2="17" 
                                    stroke="currentColor" 
                                    stroke-width="2" 
                                    stroke-linecap="round"/>
                                <line x1="14" y1="11" x2="14" y2="17" 
                                    stroke="currentColor" 
                                    stroke-width="2" 
                                    stroke-linecap="round"/>
                            </svg>
                            Eliminar clase
                        </button>

                        <!--volver a bienvenida-->
                    <div style="position: absolute; top: 73%; left: 46%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">

                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span style='color:white';>volver a página principal</span>
                                </a>
                                
                     </div>
                     
                        <?php
                        //si la clase no se elimina correctamente mensaje de error lanzado desde la excepción:
                        if (isset($_GET['msg'])) {
                            $mensaje = htmlspecialchars($_GET['msg']);
                            echo "<p style='display: inline-block;'><b>$mensaje</b></p>";
                        }
                        ?>
      
        </div> 
        </form>
        
    </fieldset>
  
 
    
</body>
</html>