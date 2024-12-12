
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario para Sustituir Monitor</title>
</head>
<body>
    <form action="index_clases.php?action=sustituir_monitor" method="post">
        <fieldset>
            <legend>Sustitución de Monitor</legend>

            <label for="dia">Selecciona el día de la semana:</label>
            <select id="dia" name="dia">
                <option value="lunes">Lunes</option>
                <option value="martes">Martes</option>
                <option value="miercoles">Miércoles</option>
                <option value="jueves">Jueves</option>
                <option value="viernes">Viernes</option>
                <option value="sabado">Sábado</option>
            </select>

            <br><br>

            <label for="hora">Selecciona la hora de inicio de la clase:</label>
            <select id="hora" name="hora">
                <option value="10:00">10:00</option>
                <option value="12:00">12:00</option>
                <option value="16:00">16:00</option>
                <option value="18:00">18:00</option>
            </select>

            <br><br>
            <label for="dni_monitor">selecciona el DNI del Monitor sustituto:</label>
            <select id="dni_monitor" name="dni_monitor" required>
            
                <?php
                //Es necesario para rescatar los dni, que tenemos en el json para que el usuario no pueda insertar cualquier dni
                $monitoresJson = "../../data/monitores.json";
                if (file_exists($monitoresJson)) {
                    $jsonData = file_get_contents($monitoresJson);
                    $monitores = json_decode($jsonData, true); // DecodificaMOS el contenido JSON a un array asociativo
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

            <button type="submit">Enviar</button>
            <br>
            <?php

                if(isset($_GET['msg']) && $_GET['msg'] == 'errorAddSustituido'){

                    echo "<p style='color:red'><b>Error al tratar de sustituir el monitor</b></p>";
        
                }

            ?>
            <br>
            <a href="../bienvenida_recepcionista.php">Volver a la página de Bienvenida</a>
        </fieldset>
    </form>
</body>
</html>

