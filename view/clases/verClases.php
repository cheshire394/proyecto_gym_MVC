<?php

require_once '../../controllers/controladorClases.php';
// Obtener el horario organizado desde el controlador
$horario = ControladorClases::mostrar_todas_Clases();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horario del Gimnasio</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: rgb(100, 149, 237);
            color: white;
        }
        td {
            background-color: #f9f9f9;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr:hover {
            background-color: #ddd;
            
        }
        .hora {
            font-weight: bold;
            background-color: rgb(100, 149, 237);
            color:#f2f2f2; 
        }
        .libre {
            background-color:hsl(0, 85.30%, 86.70%);
        }
        .ocupada{
            background-color: #d2f5b9;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Horario del Gimnasio</h2>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Lunes</th>
                <th>Martes</th>
                <th>Miércoles</th>
                <th>Jueves</th>
                <th>Viernes</th>
                <th>Sábado</th>
                <th>Domingo</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $horas = ["10:00", "12:00", "16:00", "18:00"]; 
            $dias = ["lunes", "martes", "miercoles", "jueves", "viernes", "sabado", "domingo"];

            foreach ($horas as $hora) {
                echo "<tr>";
                echo "<td class='hora'>$hora</td>";

                foreach ($dias as $dia) {
                    if (isset($horario[$dia][$hora])) {
                        $clase = $horario[$dia][$hora];
                        echo "<td class='ocupada'>{$clase['nombre_actividad']}<br><small>Monitor: {$clase['dni_monitor']}</small></td>";
                    } else {
                        echo "<td class='libre'>Libre</td>";
                    }
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="../bienvenida_recepcionista.php">Volver a la página de Bienvenida</a>
    <br>
    <?php

            //Mensaje de exito de las acciones ejecutadas desde otras páginas: 
        if (isset($_GET['msg']) && $_GET['msg'] == 'addClase') {
           
            echo "<p style='color: green; font: size 23px;'><b>La clase ha sido añadida correctamente, el horario  está actualizado</b></p>";
        }


        if (isset($_GET['msg']) && $_GET['msg'] == 'sustituido') {
           
            echo "<p style='color: green; font: size 23px;'><b>El monitor de la clase ha sido sustituido correctamente, el horario está actualizado</b></p>";
            
            }

            
        if (isset($_GET['msg']) && $_GET['msg'] == 'eliminadaDisciplina') {
           
            echo "<p style='color: green; font: size 23px;'><b>La disciplina ha sido eliminada correctamente, el horario está actualizado</b></p>";
                
            }

        if (isset($_GET['msg']) && $_GET['msg'] == 'eliminarClase') {
           
            echo "<p style='color: green; font: size 23px;'><b>La clase ha sido eliminada correctamente, el horario está actualizado</b></p>";
                    
        }         

    ?>
</body>
</html>
