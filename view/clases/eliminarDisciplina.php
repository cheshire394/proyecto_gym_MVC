<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Disciplina</title>
</head>
<body>


    <fieldset>
        <legend style="font-size: xx-large;">Selecciona la disciplina que deseas eliminar: </legend>
        <form method="POST" action="index_clases.php?action=eliminarDisciplina">
            <label for="disciplina">Disciplina:</label>
            <select id="disciplina" name="nombre_actividad" required>
                <option value="taekwondo">Taekwondo</option>
                <option value="karate">Karate</option>
                <option value="boxeo">Boxeo</option>
                <option value="MMA">MMA</option>
                <option value="kickboxing">Kickboxing</option>
                <option value="moay thai">Muay Thai</option>
                <option value="capoeira">Capoeira</option>
                <option value="judo">Judo</option>
                <option value="aikido">Aikido</option>
            </select>
            <br><br>
            <button type="submit">Eliminar Disciplina</button>
        </form>
    </fieldset>
    <br>
    <p style="color:red"><b>importante* Esta página no está diseñada para eliminar una clase del horario, su fin es eliminar todas las clases que forman parte de la disciplina elegida</b></p>
    <br>
    <a href="../bienvenida_recepcionista.php">Volver a la página de Bienvenida</a>
</body>
</html>
