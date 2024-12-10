<?php
    session_start(); 
    if(isset($_SESSION['dni']))$dni_registrado= $_SESSION['dni']; 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
</head>
<body>
    <h1>Inicio de Sesión</h1>

    <form method="POST" action="../index.php?action=login">
        <label for="dni">DNI:</label><br>
        <input type="text" id="dni" name="dni" value="<?= htmlentities($dni_registrado) ?>"><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" name="login">Iniciar Sesión</button>
    </form>
   

</body>
</html>


