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
    <style>
        .error {
            color: red;
        }

        .exito {
            color: green;
        }
    </style>

<body>
    <h1>Añadir Socio</h1>

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

    <fieldset>
        <legend>Datos:</legend>
        <form method="POST" action="index_socios.php?action=addSocio">

            <fieldset>
                <label for="dni">DNI:</label>
                <p style='color:grey'>Ejemplos de dni válidos para probar el programa: 47432031M    //   38539428F  //   77391101B</p>
                <input type="text" id="dni" name="dni" required><br><br>
               

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required><br><br>

                <label for="fecha_nac">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nac" name="fecha_nac" required><br><br>

                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>
            </fieldset>
            <br>

            <fieldset>
                <legend>Datos adicionales:</legend>
                <label for="tarifa">Tarifa:</label>
                <select id="tarifa" name="tarifa" required>
                    <option value="1">1 clase</option>
                    <option value="2">2 clases</option>
                    <option value="3">3 clases</option>
                </select><br><br>

                <?php
                // Obtener la fecha actual para poner por defecto el dia que se vaya añadir
                $fechaActual = date('Y-m-d');
                ?>

                <label for="fecha_alta">Fecha de Alta:</label>
                <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo $fechaActual; ?>"><br><br>

                <label for="fecha_baja">Fecha de Baja (opcional):</label>
                <input type="date" id="fecha_baja" name="fecha_baja"><br><br>

                <label for="cuenta_bancaria">Cuenta Bancaria:</label>
                <input type="text" id="cuenta_bancaria" name="cuenta_bancaria"><br><br>
            </fieldset>
    </fieldset>
    <br>
    <fieldset>
        <button type="submit">Añadir Socio</button>
    </fieldset>
    <br>
    <fieldset>
        <!--******************************************************************* RUTAS *************************************************************************** -->
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>

</html>