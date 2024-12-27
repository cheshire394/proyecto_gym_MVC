<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <fieldset>
        <legend style='color:rgb(75, 125, 218); font-size:33px';>Selecciona la clase a eliminar: </legend>
        <form method="post" action="index_clases.php?action=eliminarClase">
        <label for="dia_semana">Dia de la Semana: </label>
        <select name='dia_semana' id='día_semana' require>
            <option value="lunes" selected>Lunes</option>
            <option value="martes">Martes</option>
            <option value="miercoles">Miércoles</option>
            <option value="jueves">Jueves</option>
            <option value="viernes">Viernes</option>
            <option value="sabado">Sábado</option>
        </select>
        <br>
        <label for='hora_inicio'>Hora de inicio: </label>
        <select name='hora_inicio' id='hora_inicio' require>
            <option selected>10:00</option>
            <option>12:00</option>
            <option>16:00</option>
            <option>18:00</option>
        </select>
        <br>
        <button type='submit'>Eliminar clase</button>
        </form>
        
    </fieldset>
    <br>
    <br>
    <a href="../bienvenida_recepcionista.php">Volver a la página de Bienvenida</a>
    <br>
    <?php
    //si la clase no se elimina correctamente mensaje de error lanzado desde la excepción:
     if (isset($_GET['msg'])) {
         $mensaje = htmlspecialchars($_GET['msg']);
         echo "<p style='color: red; font: size 23px;'><b>$mensaje</b></p>";
     }
     ?>
    
</body>
</html>