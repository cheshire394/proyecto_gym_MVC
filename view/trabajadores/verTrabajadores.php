<?php

//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// Verifica si existe una variable de sesión 'nombre'
if (!isset($_SESSION['nombre'])) {
       // Si la sesión no contiene 'nombre', redirige al usuario a la página de inicio de sesión
       header('location: ../login_recepcionista.php');
       exit(); 
   }
 
?>

<?php

require_once '../../controllers/controladorTrabajadores.php';
// Obtener todos los monitores o un array vacio si no hay:
$monitores = ControladorTrabajadores::verMonitores(); 
$recepcionistas = ControladorTrabajadores::verRecepcionistas(); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Monitores</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
</head>
<body>
    <h1>Listado de Monitores</h1>

    <?php if (!empty($monitores)) { ?>
        <table>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Jornada</th>
                    <th>Sueldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monitores as $monitor) { ?>
                    <tr>
                        <td><?= htmlspecialchars($monitor->__get('dni')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('nombre')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('apellidos')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('fecha_nac')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('telefono')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('email')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('jornada')) ?> horas</td>
                        <td><?= htmlspecialchars(number_format($monitor->__get('sueldo'), 2)) ?> €</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p>Nota* La jornada, el sueldo, las clases y las disciplinas se van actualizando según se manipulan las clases que ejercen</p>
    <?php } else { ?>
        <p>No hay monitores disponibles en el registro</p>
    <?php } ?>
    <br>
    <br>
    <hr>
 
    <h1>Listado de recepcionistas</h1>

    <?php if (!empty($recepcionistas)) { ?>

    
        <table>
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Jornada</th>
                    <th>Sueldo</th>
                   
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recepcionistas as $recepcionista) { ?>
                    <tr>
                        <td><?= htmlspecialchars($recepcionista->__get('dni')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('nombre')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('apellidos')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('fecha_nac')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('telefono')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('email')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('jornada')) ?> horas</td>
                        <td><?= htmlspecialchars(number_format($recepcionista->__get('sueldo'), 2)) ?> €</td>
                      
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No hay recepcionistas disponibles en el registro</p>
    <?php } ?>
   


        <br>

        <div id='divEnlace'>
                    <a href="../bienvenida_recepcionista.php" style="display: inline-block; align-items: center; text-decoration: none; color: inherit;">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>volver al menú principal</span>
                    </a>
            </div>
            </div>
</body>
</html>
