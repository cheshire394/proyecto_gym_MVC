<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Socio</title>
</head>
<body>

    <h2>Buscar Socio</h2>
 
    <fieldset>
        <legend>Buscar por:</legend>
        <form method="POST" action="index_socios.php?action=verSocio">
            <select id="campo" name="campo" required>
                <option value="dni">DNI</option>
                <option value="nombre">Nombre</option>
                <option value="apellidos">Apellidos</option>
                <option value="fecha_nac">Fecha de Nacimiento</option>
                <option value="telefono">Teléfono</option>
                <option value="email">Email</option>
                <option value="tarifa">Tarifa</option>
                <option value="fecha_alta">Fecha de Alta</option>
                <option value="fecha_baja">Fecha de Baja</option>
                <option value="reservas_clases">Reservas de Clases</option>
            </select>
            <br><br>

            <fieldset>
                <label for="valor">Valor:</label>
                <input type="text" id="valor" name="valor" required>
                <br>
            </fieldset>

            <br>
            <fieldset>
                <input type="hidden" name="action" value="verSocio">
                <input type="submit" value="Buscar">
            </fieldset>
        </form>
    </fieldset>
 <br>
    <form method="POST" action="index_socios.php?action=mostrarTodos">
    <fieldset>
    <input type="submit" value="Mostrar Todos los Socios" style="margin: 0;">
    </fieldset>

    </form>   
    <br>
    <fieldset>
        <a href="../bienvenida_recepcionista.php">Página de Bienvenida</a>
    </fieldset>
</body>
</html>
