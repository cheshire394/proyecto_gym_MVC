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
            background-color: #FFA500;
        }
    </style>
</head>
<body>

    <h2>Buscar Socio</h2>
 
    <fieldset>
        <legend>Buscar por:</legend>
        <form method="POST" action="index_socios.php?action=verSocio">
            <select id="campo" name="campo" required>
                <option value="dni">DNI</option>
                <option value="nombre">Nombre</option>
                <option value="apellidos">Apellidos</option>
                <option value="fecha_nac">Fecha de Nacimiento</option>
                <option value="telefono">Teléfono</option>
                <option value="email">Email</option>
                <option value="tarifa">Tarifa</option>
                <option value="fecha_alta">Fecha de Alta</option>
                <option value="fecha_baja">Fecha de Baja</option>
                <option value="reservas_clases">Reservas de Clases</option>
            </select>
            <br><br>

            <fieldset>
                <label for="valor">Valor:</label>
                <input type="text" id="valor" name="valor" required>
                <br>
            </fieldset>

            <br>
            <fieldset>
                <input type="hidden" name="action" value="verSocio">
                <input type="submit" value="Buscar">
            </fieldset>
        </form>
    </fieldset>
 <br>
    <form method="POST" action="index_socios.php?action=mostrarTodos">
        <fieldset>
            <input type="submit" value="Mostrar Todos los Socios">
        </fieldset>
    </form>

    <!-- Aquí se muestran los resultados -->
    <!-- Busqueda concreta -->
    <?php 
    include __DIR__ . '/../view/socios/verSocio.php';

if (isset($sociosEncontrados) && !empty($sociosEncontrados)): ?>
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
                <th>Reservas de Clases</th>
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
                    <td><?php echo htmlspecialchars($socio['reservas_clases']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- Mostrar todos -->
    <?php if (isset($sociosJson) && !empty($sociosJson)): ?>
        <h2>Listado de todos los socios</h2>
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
                <th>Reservas de Clases</th>
            </tr>
            <?php foreach ($sociosJson as $socio): ?>
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
                    <td><?php echo htmlspecialchars($socio['reservas_clases']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
  


</body>
</html>
