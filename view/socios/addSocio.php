<!-- Página Añadir Socio -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Añadir Socio</title>
</head>
<body>
    <h1>Añadir Socio</h1>
    <p style="color:red"><b>*Sin terminar</b></p>
    
    <fieldset>
        <legend>Datos:</legend>
        <form method="POST" action="index_socios.php?action=addSocio">

        <fieldset>
        <label for="dni">DNI:</label>
        <input type="text" id="dni" name="dni" required><br><br>

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required><br><br>

        <label for="fecha_nac">Fecha de Nacimiento:</label>
        <input type="date" id="fecha_nac" name="fecha_nac" required><br><br>

        <label for="edad">Edad:</label>
        <input type="number" id="edad" name="edad" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="tel" id="telefono" name="telefono" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        </fieldset>
        <br>

        <!-- Dudas aquí -->
        <fieldset>
            <legend>Datos adicionales:</legend>
        <label for="tarifa">Tarifa:</label>
        <select id="tarifa" name="tarifa" required>
            <option value="1">1 clase</option>
            <option value="2">2 clases</option>
            <option value="3">3 clases</option>
        </select><br><br>

        <label for="fecha_alta">Fecha de Alta:</label>
        <input type="date" id="fecha_alta" name="fecha_alta" required><br><br>

        <label for="fecha_baja">Fecha de Baja (opcional):</label>
        <input type="date" id="fecha_baja" name="fecha_baja"><br><br>

        <label for="reservas_clases">Número de Reservas de Clases:</label>
        <input type="number" id="reservas_clases" name="reservas_clases" min="0" required><br><br>

        <label for="cuenta_bancaria">Cuenta Bancaria:</label>
        <input type="text" id="cuenta_bancaria" name="cuenta_bancaria"><br><br>
        </fieldset>        
    </fieldset>
    <br>
    <fieldset>
        <button type="submit">Añadir Socio</button>
    </fieldset>
    <br>
    <fieldset>
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>
</html>
