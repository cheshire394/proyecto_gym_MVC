<!-- Página que muestra el socio/socios de la busqueda, debajo de la página de verSocio  -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    th,
    td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }

    th {
        background-color: rgb(164, 254, 141);
    }
</style>

<body>
    <?php
    //Mostrar si hay errores
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    /* Este bloque de código en PHP muestra los resultados de la búsqueda de socios. Si se encuentran socios, 
   se muestra una tabla con sus datos; si no se encuentran, se muestra un mensaje indicando que no hubo resultados. */
    if (isset($sociosEncontrados)): ?>
        <?php if (!empty($sociosEncontrados)): ?>
            <h2>Resultados de la búsqueda</h2>
            <table>
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
                </tr>
                <?php foreach ($sociosEncontrados as $socio): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($socio['dni']); ?></td>
                        <td><?php echo htmlspecialchars($socio['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($socio['apellidos']); ?></td>
                        <td><?php echo htmlspecialchars($socio['fecha_nac']); ?></td>
                        <td><?php echo htmlspecialchars($socio['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($socio['email']); ?></td>
                        <td><?php echo htmlspecialchars($socio['tarifa']); ?></td>
                        <td><?php echo htmlspecialchars($socio['fecha_alta']); ?></td>
                        <td><?php echo htmlspecialchars($socio['fecha_baja']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="color:red">No se encontraron socios con los datos proporcionados.</p>
        <?php endif; ?>
    <?php endif; ?>

</body>

</html>