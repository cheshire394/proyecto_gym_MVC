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
    <title>Horario del Gimnasio</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
    
</head>
<body>

    <h1>Horario del Gimnasio con clases Filtradas</h1>
    <fieldset>
        <legend>Establecer filtro</legend>

        <form method="POST" action="index_clases.php?action=verClasesFiltradas">
            <label for="propiedad_filtrada">Tipo filtro</label>
            <select id="propiedad_filtrada" name="propiedad_filtrada" required>
                <option value="dni_monitor">DNI del Monitor</option>
                <option value="nombre_actividad" selected>Nombre de la Actividad</option>
                <option value="dia_semana">Día de la Semana</option>
                <option value="hora_inicio">Hora de Inicio</option>
                <option value="hora_fin">Hora de Fin</option>
            </select>
            <br><br>
            <label for="valor_filtro">Valor del filtro</label>
            <div id="campo_filtro" style="display: inline-block; vertical-align: middle;">
                <input type="text" id="valor_filtro" name="valor_filtrado" placeholder="Escribe el valor..." required>
            </div>
            <br>

            <div>
                
            <button type="submit" style="margin-left: 10px;">Aplicar Filtro</button>
           
            <div id='divEnlace'>
                    <a href="../bienvenida_recepcionista.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>volver al menú principal</span>
                    </a>
            </div>
            </div>
            <br>
            <?php
            //Si la clases no se filtran correctamente, mostramos el mensaje de error capturado en la excepcion: 
            if (isset($_GET['msg'])) {
                $mensaje = htmlspecialchars($_GET['msg']);
                echo "<p style='display: inline-block;'><b>$mensaje</b></p>";
            }
            ?>

    </fieldset>
            <br>
          
        </form>
    <br>
   
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
