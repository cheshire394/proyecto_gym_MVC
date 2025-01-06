<!-- Página que muestra todos los Socios, debajo de la página de verSocio -->
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
    <title>Listado de Socios</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: rgb(149, 175, 247);
        }

        .error {
            color: red;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid red;
            background-color: #fee;
        }
    </style>
</head>

<body>
    <h2>Listado de Todos los Socios</h2>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($sociosJson)): ?>
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
                </tr>
            </thead>
            <tbody>
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
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color:red">No hay socios registrados.</p>
    <?php endif; ?>

</body>

</html>