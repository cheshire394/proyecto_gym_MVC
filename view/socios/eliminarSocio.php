<!-- Página Eliminar Socio -->

<!-- Si no está registrado en la sesión el recepcionista-->
<?php
// Inicia una nueva sesión o reanuda la sesión existente
session_start();
// Verifica si existe una variable de sesión 'nombre'
if (isset($_SESSION['nombre'])) {
    // Si existe, asigna su valor a la variable $nombre (usando el operador de coalescencia nula por seguridad)
    $nombre = $_SESSION['nombre'] ?? "";
} else {
    // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
    //******************************************************************* RUTAS ***************************************************************************
    header('Location: ../login_recepcionista.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Socio</title>
</head>
<style>
    .error {
        color: red;
    }

    .exito {
        color: green;
    }
</style>

<body>
    <h1>Eliminar Socio</h1>

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
        <form method="POST" action="index_socios.php?action=eliminarSocio">

            <label for="campo-seleccion">Busqueda:</label>
            <select id="campo-seleccion" name="campo">
                <option value="dni">DNI</option>
                <option value="telefono">Teléfono</option>
                <option value="email">Email</option>
            </select><br><br>

            <label for="valor">Socio:</label>
            <input type="text" id="valor" name="valor" required><br><br>

            <button type="submit">Eliminar</button>
        </form>
    </fieldset>
    <br>
    <fieldset>
        <!--******************************************************************* RUTAS *************************************************************************** -->
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>

</html>