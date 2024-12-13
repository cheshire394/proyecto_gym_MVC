<!-- verSocio.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Socio</title>
    <style>
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #FFA500; /* Naranja claro */
        }
    </style>
</head>
<body>

    <h2>Buscar Socio</h2>

    <fieldset>
    <!-- Formulario para buscar el DNI del socio -->
    <form action="socio.php" method="get">
        <label for="dni">DNI del Socio:</label>
        <input type="text" id="dni" name="dni" required>
        <br><br>
        <fieldset>
        <input type="submit" value="Buscar">
        </fieldset>
    </form>
    </fieldset>

    <?php
    // Mostrar mensajes de éxito o error si existen
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];
        if ($msg == 'modSocio') {
            echo "<p>El socio ha sido modificado correctamente.</p>";
        } elseif ($msg == 'socioNoEncontrado') {
            echo "<p>No se encontró el socio.</p>";
        } elseif ($msg == 'errorCamposVacios') {
            echo "<p>Por favor, complete todos los campos obligatorios.</p>";
        }
    }

    // Verificar si el DNI es proporcionado en la URL
    if (isset($_GET['dni'])) {
        $dni = $_GET['dni'];
        
        // Obtener los socios del archivo JSON
        $sociosJson = json_decode(file_get_contents('../data/socios.json'), true);

        // Buscar el socio por DNI
        $socioEncontrado = null;
        foreach ($sociosJson as $socio) {
            if ($socio['dni'] == $dni) {
                $socioEncontrado = $socio;
                break;
            }
        }

        // Si el socio se encuentra, mostrar los detalles
        if ($socioEncontrado !== null) {
            echo "<table>";
            echo "<tr><th>DNI</th><td>" . htmlspecialchars($socioEncontrado['dni']) . "</td></tr>";
            echo "<tr><th>Nombre</th><td>" . htmlspecialchars($socioEncontrado['nombre']) . "</td></tr>";
            echo "<tr><th>Apellidos</th><td>" . htmlspecialchars($socioEncontrado['apellidos']) . "</td></tr>";
            echo "<tr><th>Fecha de Nacimiento</th><td>" . htmlspecialchars($socioEncontrado['fecha_nac']) . "</td></tr>";
            echo "<tr><th>Teléfono</th><td>" . htmlspecialchars($socioEncontrado['telefono']) . "</td></tr>";
            echo "<tr><th>Email</th><td>" . htmlspecialchars($socioEncontrado['email']) . "</td></tr>";
            echo "<tr><th>Tarifa</th><td>" . htmlspecialchars($socioEncontrado['tarifa']) . "</td></tr>";
            echo "<tr><th>Fecha de Alta</th><td>" . htmlspecialchars($socioEncontrado['fecha_alta']) . "</td></tr>";
            echo "<tr><th>Fecha de Baja</th><td>" . htmlspecialchars($socioEncontrado['fecha_baja']) . "</td></tr>";
            echo "<tr><th>Reservas</th><td>" . htmlspecialchars($socioEncontrado['reservas_clases']) . "</td></tr>";
            echo "</table>";
        } else {
            echo "<p>No se encontró el socio con DNI: $dni.</p>";
        }
    }
    ?>

</body>
</html>
