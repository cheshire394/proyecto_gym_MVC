<!-- Página para el incio de sesión d elos recepcionistas
     HTML con el fmormulario de acceso, que pide DNI y la contraseña para acceder, y para finalizar el botón -->

<!-- Código PHP -->
<?php
    // Inicio o reanudación de una sesión existente.
    session_start(); 

    // Verifica si existe un DNI almacenado en la sesión y lo asigna a una variable.
    if (isset($_SESSION['dni'])) {
        $dni_registrado = $_SESSION['dni']; 
    }

    // Comprueba si se pasó un error de credenciales incorrectas a través de la URL.
    if (isset($_GET['error']) && $_GET['error'] == 'credenciales_incorrectas') {
    // Muestra un mensaje de error y redirige automáticamente al login después de 2 segundos.
    echo "<p style='color: red;'> Error, las credenciales no son correctas, redirigiendo al registro automáticamente</p>";
    header('Refresh: 2 login_recepcionista.php'); 
    }
?>


<!-- HTML con el formulario -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h1>Inicio de Sesión Recepcionista</h1>

    <form method="POST" action="../index.php?action=login">
        <!-- Acceso -->
        <fieldset>
            <legend>Acceso:</legend>

            <label for="dni">DNI:</label><br>
            <input type="text" id="dni" name="dni" value="<?= htmlentities($dni_registrado) ?>"><br><br>

            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br>
        </fieldset>

        <br>

        <!-- Botón de inicio de sesión -->
        <fieldset>
            <button type="submit" name="login">Iniciar Sesión</button>
        </fieldset>
    </form>

    <br>

    <!-- Enlace a registro de recepcionista-->
    <fieldset>
        <a href="registro_recepcionista.php">Registrar Recepcionista</a>
    </fieldset>
</body>
</html>