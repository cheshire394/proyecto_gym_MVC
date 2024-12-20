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
            color: #f2f2f2; 
        }
        .libre {
            background-color: #eaeaea;
        }
        .noFiltro {
            background-color: #fca9b4;
        }
        .filtrada {
            background-color: #d1fca9;
        }
    </style>
</head>
<body>
    <h1>Horario del Gimnasio con clases Filtradas</h1>
    <fieldset>
        <legend style="font-size: large;">Filtro:</legend>
        <form method="POST" action="index_clases.php?action=verClasesFiltradas">
            <label for="propiedad_filtrada" style="margin-left: 10px; display: inline-block; vertical-align: middle;"></label>Tipo filtro:</label>
            <select id="propiedad_filtrada" name="propiedad_filtrada" required>
                <option value="dni_monitor">DNI del Monitor</option>
                <option value="nombre_actividad" selected>Nombre de la Actividad</option>
                <option value="dia_semana">Día de la Semana</option>
                <option value="hora_inicio">Hora de Inicio</option>
                <option value="hora_fin">Hora de Fin</option>
            </select>
            <br><br>
            <label for="valor_filtro" style="margin-left: 10px; display: inline-block; vertical-align: middle;">Valor del filtro:</label>
            <div id="campo_filtro" style="display: inline-block; vertical-align: middle;">
                <input type="text" id="valor_filtro" name="valor_filtrado" placeholder="Escribe el valor..." required>
            </div>
            </fieldset>
            <br>
            <fieldset> 
                <button type="submit" style="margin-left: 10px;">Aplicar Filtro</button>
            </fieldset>
        </form>
    <br>
    <fieldset>
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
                    if ($dia === "domingo") {
                        echo "<td class='libre'>Libre</td>";
                    } elseif (isset($horario[$dia][$hora])) {
                        // Mostramos la clase si existe en el horario filtrado
                        $clase = $horario[$dia][$hora];
                        echo "<td class='filtrada'>{$clase['nombre_actividad']}<br><small>Monitor: {$clase['dni_monitor']}</small></td>";
                    } else {
                        // Dejamos vacía si no cumple el filtro
                        echo "<td class='noFiltro'></td>";
                    }
                }

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    </fieldset>
    <br>
    <fieldset>
    <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>

    <script>
        //Este código ha sido desarrollado por la IA para hacer más atractiva la tabla y el formulario (no evaluable en esta práctica el aspecto estético): 
        document.getElementById("propiedad_filtrada").addEventListener("change", function() {
            var filtroSeleccionado = this.value;
            var campoFiltro = document.getElementById("campo_filtro");

            // Si el filtro seleccionado es hora de inicio o fin, se convierte en un select con opciones de horas
            if (filtroSeleccionado === "hora_inicio" || filtroSeleccionado === "hora_fin") {
                campoFiltro.innerHTML = `
                    <select id="valor_filtro" name="valor_filtrado" required>
                        <option value="10:00">10:00</option>
                        <option value="12:00">12:00</option>
                        <option value="14:00">14:00</option>
                        <option value="16:00">16:00</option>
                        <option value="18:00">18:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                `;
            } else {
                campoFiltro.innerHTML = `<input type="text" id="valor_filtro" name="valor_filtrado" placeholder="Escribe el valor..." required>`;
            }
        });
    </script>
</body>
</html>
