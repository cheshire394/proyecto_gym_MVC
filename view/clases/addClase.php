<!-- Página Añadir Clase -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Añadir Clase</title>
</head>
<body>
    <h1>Añadir Clase</h1>
    <p style="color:red"><b>*IMPORTANTE*</b></p>
    <p style="color:red"><b>Si existe otra clase que tenga asignado el mismo día de semana y hora, será reemplazada</b></p>
    <fieldset>
        <legend>Datos:</legend>
        <form method="POST" action="index_clases.php?action=addClase">

            <label for="dni_monitor">DNI del Monitor:</label>
            <select id="dni_monitor" name="dni_monitor" required>

                <?php
                /* This PHP code block is reading data from a JSON file that contains information about
                monitors.
                Es necesario para rescatar los dni, que tenemos en el json para que el usuario no pueda insertar cualquier DNI */
                $monitoresJson = "../../data/monitores.json";
                if (file_exists($monitoresJson)) {
                    $jsonData = file_get_contents($monitoresJson);
                    $monitores = json_decode($jsonData, true); // Decodificamos el contenido JSON a un array asociativo
                    foreach ($monitores as $monitor) {
                        echo '<option value="' . $monitor['dni'] . '">'
                            . $monitor['dni'] . ' - ' . $monitor['nombre']
                            . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No se encontraron monitores disponibles</option>';
                }
                ?>

            </select>
            <br><br>

            <label for="nombre_actividad">Nombre de la Actividad:</label>
            <input type="text" id="nombre_actividad" name="nombre_actividad" placeholder="ejemplos: MMA, muay thai..." required>
            <br><br>

            <label for="dia_semana">Día de la Semana:</label>
            <select id="dia_semana" name="dia_semana" required>
                <option value="lunes" selected>Lunes</option>
                <option value="martes">Martes</option>
                <option value="miercoles">Miércoles</option>
                <option value="jueves">Jueves</option>
                <option value="viernes">Viernes</option>
                <option value="sabado">Sábado</option>
            </select>
            <br><br>

            <label for="hora_inicio">Hora de Inicio:</label>
            <select id="hora_inicio" name="hora_inicio" required>
                <option value="10:00" selected>10:00</option>
                <option value="12:00">12:00</option>
                <option value="16:00">16:00</option>
                <option value="18:00">18:00</option>
            </select>
            <br>
            <button type="submit">Añadir Clase</button>
        </form>
    
    <br>
    <fieldset>
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
    <br>
    <?php
           if (isset($_GET['msg']) && $_GET['msg'] == 'erroraddClase') {
           
            echo "<p style='color: red;'><b>Error, la clase no ha sido añadida</b></p>";
        }

    ?>
</body>
</html>
