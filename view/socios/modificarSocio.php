<!-- Página Modificar Socio -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Modificar Socio</title>
</head>
<body>
    <h1>Modificar Socio</h1>
    <p style="color:red"><b>*Modifique los campos necesarios</b></p>
    
    <fieldset>
        <legend>Datos:</legend>
        <form method="POST" action="index_socios.php?action=modificarSocio">
            <label for="dni">DNI:</label>
            <input type="text" id="dni" name="dni"><br><br>

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre"><br><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos"><br><br>

            <label for="fecha_nac">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nac" name="fecha_nac"><br><br>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad"><br><br>

            <label for="telefono">Teléfono:</label>
            <input type="tel" id="telefono" name="telefono"><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" re><br><br>

            <fieldset>
                <legend>Datos adicionales:</legend>
                <label for="tarifa">Tarifa:</label>
                <select id="tarifa" name="tarifa">
                    <option value="">Seleccione</option>
                    <option value="1">1 clase</option>
                    <option value="2">2 clases</option>
                    <option value="3">3 clases</option>
                </select><br><br>

                <?php
                // Obtener la fecha actual en formato YYYY-MM-DD
                $fechaActual = date('Y-m-d');
                ?>

                <label for="fecha_alta">Fecha de Alta:</label>
                <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo $fechaActual; ?>"><br><br>

                <label for="fecha_baja">Fecha de Baja:</label>
                <input type="date" id="fecha_baja" name="fecha_baja"><br><br>

                <label for="cuenta_bancaria">Cuenta Bancaria:</label>
                <input type="text" id="cuenta_bancaria" name="cuenta_bancaria"><br><br>
            </fieldset>
            <br>
            <fieldset>
            <button type="submit">Modificar Socio</button>
            </fieldset>
        </form>
    </fieldset>

    <br>
    <fieldset>
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>
</html>
