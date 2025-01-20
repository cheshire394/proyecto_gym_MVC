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
        <form method="post" action="index_clases.php?action=eliminarClase">
        <label for="dia_semana">Dia de la Semana</label>
        <select name='dia_semana' id='día_semana' require>
            <option value="lunes" selected>Lunes</option>
            <option value="martes">Martes</option>
            <option value="miercoles">Miércoles</option>
            <option value="jueves">Jueves</option>
            <option value="viernes">Viernes</option>
            <option value="sabado">Sábado</option>
        </select>
        <br>
        <label for='hora_inicio'>Hora de inicio</label>
        <select name='hora_inicio' id='hora_inicio' require>
            <option selected>10:00</option>
            <option>12:00</option>
            <option>16:00</option>
            <option>18:00</option>
        </select>
        <br>
        <div> 
                    <button type="submit">Eliminar clase</button>

                          <div id='divEnlace'>
                                  <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                                  <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                  </svg>
                                  <span>volver al menú principal</span>
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