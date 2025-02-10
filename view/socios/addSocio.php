<!-- Página Añadir Socio -->

<?php

session_start();

// Verifica si existe una variable de sesión 'nombre'
if (!isset($_SESSION['nombre'])) {
       // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
       header('location: ../index.php');
       exit(); 
   }
 
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Añadir Socio</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    

<body>
  
    <fieldset>
        <legend>Añadir nuevo socio</legend>
        <form method="POST" action="router_socios.php?action=addSocio">

                <p>Ejemplos de dni válidos para probar el programa: 47432031M  // 38539428F  // 77391101B</p>
                <label for="dni">*DNI</label>
                <input type="text" id="dni" name="dni" required><br><br>

                <?php
                        // Mostrar mensaje de exito o de error (suelen ser por el dni, por eso lo ubico aqui)
                        if (isset($_GET['msg'])) {
                            echo '<p style=color:red;> <small>' . htmlspecialchars($_GET['msg']) . '</small></p>';
                        }
                       
                ?>
               

                <label for="nombre">*Nombre</label>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="apellidos">*Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" required><br><br>

                <label for="fecha_nac">*Fecha de Nacimiento</label>
                <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo date('2000-01-01');?>" required><br><br>

                <label for="tarifa">*Tarifa</label>
                <select id="tarifa" name="tarifa" required>
                    <option value="1">1 clase</option>
                    <option value="2">2 clases</option>
                    <option value="3">3 clases</option>
                    <option value="24">ilimitadas</option>
                </select><br><br>

                <label for="fecha_alta">*Fecha de Alta</label>
                <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo date('Y-m-d'); ?>" required>
                <br><br>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono"><br><br>

                <label for="email">Email</label>
                <input type="email" id="email" name="email"><br><br>
                 <br>

                <label for="cuenta_bancaria">Cuenta Bancaria</label>
                <input type="text" id="cuenta_bancaria" name="cuenta_bancaria"><br><br>
                <br>
                <p style=color:grey;> * campos obligatorios</p>
            
                <div>
                
                <button type="submit" style="margin-left: 60px;">añadir socio</button>
               <!--volver a bievenida-->
                          <!--volver a bienvenida-->
                 <div style="position: absolute; top: 94%; left: 50%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">

                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span style='color:white';>volver a página principal</span>
                                </a>
                                
                     </div>
                </div>
               
            
        </form>
    </fieldset>
   
</body>

</html>