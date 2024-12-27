<!--  Página de registro de recepcionistas.
      HTML con un formulario que recoge los datos que necesitamos de los recepcionistas -->

<!-- Código PHP -->
<?php
/* This PHP code block is setting up the environment to display all PHP errors, which is helpful for
debugging during development. It uses the `ini_set` function to configure PHP settings for
displaying errors and sets the error reporting level to `E_ALL`, which includes all types of errors. */

//Configura el entorno para mostrar todos los errores de PHP, lo que es útil para depuración durante el desarrollo.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/* The `` array is storing the data of a receptionist for example purposes. Each key in
the array represents a specific piece of information about the receptionist, such as their ID number
(`dni`), name (`nombre`), last name (`apellidos`), date of birth (`fecha_nac`), phone number
(`telefono`), email address (`email`), bank account number (`cuenta_bancaria`), role (`funcion`),
salary (`sueldo`), extra hours worked (`horas_extra`), and working hours per week (`jornada`). */

//Se define un array asociativo $recepcionista1 con los datos de un recepcionista de ejemplo. Estos datos son de solo lectura para el formulario.
$recepcionista1 = [
    'dni' => '16280029P',
    'nombre' => 'Iago',
    'apellidos' => 'Fernandez Pereira',
    'fecha_nac' => '1988-08-23',
    'telefono' => '677123456',
    'email' => 'Iago@gmail.com',
    'cuenta_bancaria' => 'ES9345678921244368890123',
    'funcion' => 'recepcionista',
    'sueldo' => 1150,
    'horas_extra' => 0,
    'jornada' => 40
];
?>

<!--HTML con el formulario
The provided HTML code is creating a registration form for a
receptionist. Here's a breakdown of what each part of the code is doing: -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Recepcionista</title>
</head>
<body>
    <h1>Registro Recepcionista: </h1>
    <p style="color:orangered">*Datos de solo lectura para facilitar la prueba de la aplicación. Únicamente es necesario añadir la contraseña.</p>
    
    <!--Enviará los datos mediante el método POST al archivo index.php con un parámetro action=registro.-->
    <fieldset>
    <form method="POST" action="../index.php?action=registro">
    <fieldset>
        <form method="POST" action="../index.php?action=registro">

            <!-- Datos del usuario -->
            <fieldset>
                <legend>Datos usuario:</legend>

                <label for="nombre">Nombre:</label><br>
                <input type="text" id="nombre" name="nombre" value="<?= $recepcionista1['nombre'] ?>" readonly><br><br>

                <label for="apellidos">Apellidos:</label><br>
                <input type="text" id="apellidos" name="apellidos" value="<?= $recepcionista1['apellidos'] ?>" readonly><br><br>

                <label for="dni">DNI:</label><br>
                <input type="text" id="dni" name="dni" value="<?= $recepcionista1['dni'] ?>" readonly><br><br>

                <label for="fecha_nac">Fecha de Nacimiento:</label><br>
                <input type="date" id="fecha_nac" name="fecha_nac" value="<?= $recepcionista1['fecha_nac'] ?>" readonly><br><br>

                <label for="telefono">Teléfono:</label><br>
                <input type="tel" id="telefono" name="telefono" value="<?= $recepcionista1['telefono'] ?>" readonly><br><br>

                <label for="email">Correo Electrónico:</label><br>
                <input type="email" id="email" name="email" value="<?= $recepcionista1['email'] ?>" readonly><br><br>
            </fieldset>

            <br>

            <!-- Datos adicionales -->
            <fieldset>
                <legend>Datos adicionales:</legend>

                <label for="cuenta_bancaria">Cuenta Bancaria:</label><br>
                <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="<?= $recepcionista1['cuenta_bancaria'] ?>" readonly><br><br>

                <label for="funcion">Función:</label><br>
                <input type="text" id="funcion" name="funcion" value="<?= $recepcionista1['funcion'] ?>" readonly><br><br>

                <label for="sueldo">Sueldo:</label><br>
                <input type="number" id="sueldo" name="sueldo" value="<?= $recepcionista1['sueldo'] ?>" readonly><br><br>

                <label for="horas_extra">Horas Extra:</label><br>
                <input type="number" id="horas_extra" name="horas_extra" value="<?= $recepcionista1['horas_extra'] ?>" readonly><br><br>

                <label for="jornada">Jornada:</label><br>
                <input type="number" id="jornada" name="jornada" value="<?= $recepcionista1['jornada'] ?>" readonly><br><br>
            </fieldset>

            <br>

            <!-- Datos de acceso -->
            <fieldset>
                <legend>Acceso:</legend>

                <label for="password">Contraseña:</label><br>
                <input type="password" id="password" name="password" required><br><br>
            </fieldset>

            <br>

            <!-- Botón de enviar -->
            <button type="submit" name="registrar">Registrar</button>
        </form>
    </fieldset>

    <br>

    <!-- Enlace para ir al login -->
    <fieldset>
        <a href="login_recepcionista.php">Login Recepción</a>
    </fieldset>

    <!-- Código PHP -->
    <?php
    //Incluye el archivo controladorRecepcionista.php, donde se encuentra la lógica para procesar los datos del formulario.
    require_once('../controllers/controladorRecepcionista.php'); 

        /* The `if` statement you provided is checking if a specific condition is met in the URL
        parameters. Let's break it down: */
        if (isset($_GET['error']) && $_GET['error'] === 'dni_existente') {
                echo '<p style="color: red;">La recepcionista ya está registrada, redirigiendo al login</p>';
                header('Refresh:3; url=login_recepcionista.php?dni');
            } 
?>

   
</body>
</html>