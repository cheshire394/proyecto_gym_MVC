<!-- Página Añadir Socio -->

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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Añadir Socio</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    <style>
        .error {
            color: red;
        }

        .exito {
            color: green;
        }
    </style>

<body>
  
    <fieldset>
        <legend>Añadir nuevo socio</legend>
        <form method="POST" action="index_socios.php?action=addSocio">

                <p>Ejemplos de dni válidos para probar el programa: 47432031M  // 38539428F  // 77391101B</p>
                <label for="dni">DNI</label>
                <input type="text" id="dni" name="dni" required><br><br>
               

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" required><br><br>

                <label for="fecha_nac">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nac" name="fecha_nac" required><br><br>

                <label for="telefono">Teléfono</label>
                <input type="tel" id="telefono" name="telefono" required><br><br>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required><br><br>
                 <br>
                <label for="tarifa">Tarifa</label>
                <select id="tarifa" name="tarifa" required>
                    <option value="1">1 clase</option>
                    <option value="2">2 clases</option>
                    <option value="3">3 clases</option>
                </select><br><br>

                <?php
                // Obtener la fecha actual para poner por defecto el dia que se vaya añadir
                $fechaActual = date('Y-m-d');
                ?>

                <label for="fecha_alta">Fecha de Alta</label>
                <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo $fechaActual; ?>"><br><br>

                <label for="fecha_baja">Fecha de Baja (opcional)</label>
                <input type="date" id="fecha_baja" name="fecha_baja"><br><br>

                <label for="cuenta_bancaria">Cuenta Bancaria</label>
                <input type="text" id="cuenta_bancaria" name="cuenta_bancaria"><br><br>
                <br>

                <?php
                        // Mostrar mensaje de error si existe
                        if (isset($_GET['error'])) {
                            echo '<div class="error">' . htmlspecialchars($_GET['error']) . '</div>';
                        }
                        // Mostrar mensaje de éxito si existe
                        if (isset($_GET['exito'])) {
                            echo '<div class="exito">' . htmlspecialchars($_GET['exito']) . '</div>';
                        }
                ?>
                <div>
                
                <button type="submit" style="margin-left: 60px;">añadir socio</button>
               
                <div id='divEnlace'>
                        <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                        <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>volver al menú principal</span>
                        </a>
                </div>
                </div>
    </fieldset>
   
</body>

</html>