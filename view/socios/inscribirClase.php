<?php

session_start();

// Verifica si existe una variable de sesión 'nombre'
if (!isset($_SESSION['nombre'])) {
       // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
       header('location: ../index.php');
       exit(); 
   }

 
   if(isset($_POST['dni_socio'])){

   require_once '../../controllers/controladorClases.php';

   //Metodo para controlar los valores introducidos en el formulario
   $horas_ocupadas = ControladorClases::horas_ocupadas(); 

   $dni_socio = $_POST['dni_socio']; 

   }
 
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
        <legend>incribir socio en una clase</legend>

    <form  method="post" action="router_socios.php?action=inscribirClase">

        <label for='dni_socio'>dni socio</label>
        <!--Recibir el dni por campo oculto-->
        <input type='text' value=<?php echo $dni_socio?> name='dni_socio' required readonly>
        <label for="id_clase">Selecciona la clase</label>
              
              <select id="id_clase" name="id_clase" required <?= empty($horas_ocupadas) ? 'disabled' : '' ?>>
                  <?php if (empty($horas_ocupadas)) : ?>
                      <option value="">Ninguna clase disponible</option>
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

                    
                        <button type="submit" name='inscribir'>
                            <!-- Icono incrbirse -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 3l4 4m0 0l-10 10H7v-4L17 3z"/>
                            </svg>
                            Inscribir socio
                        </button>

                        <!--volver a gestion de socios-->
                    <div style="position: absolute; top: 78%; left: 60%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                             <a href="verSocios.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                    
                                    <span style='color:white';>volver a socios</span>
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