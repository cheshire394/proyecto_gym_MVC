<!-- Página Eliminar Socio -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Socio</title>
</head>

<body>
    <h1>Eliminar Socio</h1>

    <fieldset>
        <legend>Datos:</legend>
        <form method="POST" action="index_socios.php?action=eliminarSocio">

            <label for="campo-seleccion">Busqueda:</label>
            <select id="campo-seleccion" name="campo">
                <option value="dni">DNI</option>
                <option value="telefono">Teléfono</option>
                <option value="email">Email</option>
            </select><br><br>

            <label for="valor">Socio:</label>
            <input type="text" id="valor" name="valor" required><br><br>

            <button type="submit">Eliminar</button>
        </form>
    </fieldset>
    <br>
    <fieldset>
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>

</html>