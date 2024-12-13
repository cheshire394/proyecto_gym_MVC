<!-- mostrarSocios.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mostrar Todos los Socios</title>
    <style>
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color:rgb(250, 220, 163); /* Naranja claro */
        }
    </style>
</head>
<body>

    <h2>Lista de Todos los Socios</h2>

    <?php
    // Mostrar mensajes de éxito o error
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
    ?>

    <table>
        <thead>
            <tr>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Fecha de Nacimiento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Tarifa</th>
                <th>Fecha de Alta</th>
                <th>Fecha de Baja</th>
                <th>Reservas</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Obtener los socios del archivo JSON
        $sociosJson = json_decode(file_get_contents('../data/socios.json'), true);

        // Comprobar si se obtuvieron datos
        if ($sociosJson !== null && !empty($sociosJson)) {
            foreach ($sociosJson as $socio) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($socio['dni']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['nombre']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['apellidos']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['fecha_nac']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['telefono']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['email']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['tarifa']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['fecha_alta']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['fecha_baja']) . "</td>";
                echo "<td>" . htmlspecialchars($socio['reservas_clases']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No hay socios registrados.</td></tr>";
        }
        ?>
        </tbody>
    </table>

</body>
</html>
