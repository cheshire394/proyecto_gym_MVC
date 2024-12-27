<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
    <style>
        details{
            margin:10px; 
            padding: 10px;
        }
        h1{
            text-align: center;
            color:rgb(75, 125, 218);
            border-color: #c0cef8;
            font-weight: bold;
            font-size: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            background-color: #eef1fb;
        }
        th {
            background-color:rgb(75, 125, 218);
            color:white; 
            font-weight: bold;
          
        }
        tr:hover {
            background-color: #f9f9f9;
        }

        button{
            background-color:rgb(75, 125, 218);
            border: solid white; 
            border-radius: 4px;
            color:white; 
            padding: 7px;
            margin-top: 5px;
        }
        button:hover{
            transform: translateY(3px);
            background-color:#1e50f9; 
        }
    </style>
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
                    <th>edad</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Disciplinas</th>
                    <th>Clases</th>
                    <th>Jornada</th>
                    <th>Sueldo</th>
                    <th>Horas extra</th>
                    <th>Modificar horas extra</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($monitores as $dni => $monitor) { ?>
                    <tr>
                        <td><?= htmlspecialchars($dni) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('nombre')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('apellidos')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('edad')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('fecha_nac')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('telefono')) ?></td>
                        <td><?= htmlspecialchars($monitor->__get('email')) ?></td>
                        <td>
                            <?= htmlspecialchars(implode(', ', $monitor->__get('disciplinas'))) ?>
                        </td>
                        <td>
                            <?= htmlspecialchars(implode(', ', array_keys($monitor->__get('clases')))) ?>
                        </td>
                        <td><?= htmlspecialchars($monitor->__get('jornada')) ?> horas</td>
                        <td><?= htmlspecialchars(number_format($monitor->__get('sueldo'), 2)) ?> €</td>
                        <td><?= htmlspecialchars($monitor->__get('horas_extra'))?></td>
                        <td>

                       
                        <form method="post" action="../../controllers/controlador_horas.php">

                            <input type='number' name='horas_extra' min='0'  maxlenght='2' require>
                            <input type='hidden' name='dni' value=<?php echo $dni?>>
                            <button type='submit' name='add_horas'>añadir horas extra</button>

                        
                        </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <p style='color:rgb(75, 125, 218); font-size:23px';>Nota* La jornada, el sueldo, las clases y las disciplinas se van actualizando según se manipulan las clases que ejercen</p>
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
                    <th>edad</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Jornada</th>
                    <th>Sueldo</th>
                    <th>Horas extra</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recepcionistas as $dni => $recepcionista) { ?>
                    <tr>
                        <td><?= htmlspecialchars($recepcionista->__get('dni')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('nombre')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('apellidos')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('edad')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('fecha_nac')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('telefono')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('email')) ?></td>
                        <td><?= htmlspecialchars($recepcionista->__get('jornada')) ?> horas</td>
                        <td><?= htmlspecialchars(number_format($recepcionista->__get('sueldo'), 2)) ?> €</td>
                        <td><?= htmlspecialchars($recepcionista->__get('horas_extra'))?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p>No hay recepcionistas disponibles en el registro</p>
    <?php } ?>
        <br>

    <fieldset>
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>
</html>
