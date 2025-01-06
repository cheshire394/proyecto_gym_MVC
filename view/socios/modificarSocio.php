<!-- Página Modificar Socio -->

<?php
$socio = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buscar_dni'])) {
    $dniBuscado = $_POST['buscar_dni'];

    // Cargar los datos de socios desde el archivo JSON.
    //******************************************************************* RUTAS ***************************************************************************
    $jsonFile = '../../data/socios.json';

    if (file_exists($jsonFile)) {
        $socios = json_decode(file_get_contents($jsonFile), true);

        // Buscar el socio por DNI.
        foreach ($socios as $s) {
            if ($s['dni'] === $dniBuscado) {
                $socio = $s;
                break;
            }
        }
    } else {
        echo "<p style='color:red'>Error: No se encontró el archivo de datos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Modificar Socio</title>
</head>
<style>
    .exito {
        color: green;
    }
</style>

<body>
    <h1>Modificar Socio</h1>

    <?php
    // Mostrar mensaje de éxito si existe
    if (isset($_GET['exito'])) {
        echo '<div class="exito">' . htmlspecialchars($_GET['exito']) . '</div>';
        echo '<br>';
    }
    ?>
    <!-- Formulario para buscar socio por DNI -->
    <fieldset>
        <legend>Buscar Socio:</legend>
        <form method="POST">
            <label for="buscar_dni">DNI del Socio:</label>
            <input type="text" id="buscar_dni" name="buscar_dni" required>
            <button type="submit">Buscar</button>
        </form>
    </fieldset>

    <br>

    <!-- Si el socio se encuentra, mostrar el formulario de modificación -->
    <?php if ($socio): ?>
        <p style="color:red"><b>*Modifique los campos necesarios</b></p>
        <fieldset>
            <legend>Datos:</legend>
            <form method="POST" action="index_socios.php?action=modificarSocio">
                <label for="dni">DNI:</label>
                <input type="text" id="dni" name="dni" value="<?php echo htmlspecialchars($socio['dni']); ?>" readonly><br><br>

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($socio['nombre']); ?>"><br><br>

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($socio['apellidos']); ?>"><br><br>

                <label for="fecha_nac">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo htmlspecialchars($socio['fecha_nac']); ?>"><br><br>

                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" value="<?php echo htmlspecialchars($socio['telefono']); ?>"><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($socio['email']); ?>"><br><br>

                <fieldset>
                    <legend>Datos adicionales:</legend>
                    <label for="tarifa">Tarifa:</label>
                    <select id="tarifa" name="tarifa">
                        <option value="">Seleccione</option>
                        <option value="1" <?php echo $socio['tarifa'] == '1' ? 'selected' : ''; ?>>1 clase</option>
                        <option value="2" <?php echo $socio['tarifa'] == '2' ? 'selected' : ''; ?>>2 clases</option>
                        <option value="3" <?php echo $socio['tarifa'] == '3' ? 'selected' : ''; ?>>3 clases</option>
                    </select><br><br>

                    <label for="fecha_alta">Fecha de Alta:</label>
                    <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo htmlspecialchars($socio['fecha_alta']); ?>"><br><br>

                    <label for="fecha_baja">Fecha de Baja:</label>
                    <input type="date" id="fecha_baja" name="fecha_baja" value="<?php echo htmlspecialchars($socio['fecha_baja']); ?>"><br><br>

                    <label for="cuenta_bancaria">Cuenta Bancaria:</label>
                    <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="<?php echo htmlspecialchars($socio['cuenta_bancaria']); ?>"><br><br>
                </fieldset>
                <br>
                <fieldset>
                    <button type="submit">Modificar Socio</button>
                </fieldset>
            </form>
        </fieldset>
    <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <p style="color:red">No se encontró ningún socio con el DNI proporcionado.</p>
    <?php endif; ?>
    <br>
    <fieldset>
        <!--******************************************************************* RUTAS *************************************************************************** -->
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>

</html>