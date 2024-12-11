<!--  Página de registro de recepcionistas. -->

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$recepcionista1 = [
    'dni' => '16280029P',
    'nombre' => 'Nuria',
    'apellidos' => 'Gonzalez Lopez',
    'fecha_nac' => '1975-08-23',
    'telefono' => '677123456',
    'email' => 'nuria_lopez@gmail.com',
    'cuenta_bancaria' => 'ES9345678921244368890123',
    'funcion' => 'recepcionista',
    'sueldo' => 1150,
    'horas_extra' => 0,
    'jornada' => 40
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Recepcionista</title>
</head>
<body>
    <h1>Registrar Recepcionista: </h1>
    <p>*Datos de solo lectura para facilitar la prueba de la aplicación. Únicamente es necesario añadir la contraseña.</p>
    
    <form method="POST" action="../index.php?action=registro">
        <label for="dni">DNI:</label><br>
        <input type="text" id="dni" name="dni" value="<?= $recepcionista1['dni'] ?>" readonly><br><br>
        
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" value="<?= $recepcionista1['nombre'] ?>" readonly><br><br>
        
        <label for="apellidos">Apellidos:</label><br>
        <input type="text" id="apellidos" name="apellidos" value="<?= $recepcionista1['apellidos'] ?>" readonly><br><br>
        
        <label for="fecha_nac">Fecha de Nacimiento:</label><br>
        <input type="date" id="fecha_nac" name="fecha_nac" value="<?= $recepcionista1['fecha_nac'] ?>" readonly><br><br>
        
        <label for="telefono">Teléfono:</label><br>
        <input type="tel" id="telefono" name="telefono" value="<?= $recepcionista1['telefono'] ?>" readonly><br><br>
        
        <label for="email">Correo Electrónico:</label><br>
        <input type="email" id="email" name="email" value="<?= $recepcionista1['email'] ?>" readonly><br><br>
        
        <label for="cuenta_bancaria">Cuenta Bancaria:</label><br>
        <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="<?= $recepcionista1['cuenta_bancaria'] ?>" readonly><br><br>
        
        <label for="funcion">Función:</label><br>
        <input type="text" id="funcion" name="funcion" value="<?= $recepcionista1['funcion'] ?>" readonly><br><br>
        
        <label for="sueldo">Sueldo:</label><br>
        <input type="number" id="sueldo" name="sueldo" value="<?= $recepcionista1['sueldo'] ?>" readonly><br><br>
        
        <label for="horas_extra">Horas Extra:</label><br>
        <input type="number" id="horas_extra" name="horas_extra" value="<?= $recepcionista1['horas_extra'] ?>" readonly><br><br>
        
        <label for="jornada">Jornada:</label><br>
        <input type="number" id="jornada" name="jornada" value="<?= $recepcionista1['jornada'] ?>" readonly><br><br>
        
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit" name='registrar'>Registrar</button>
    </form>
    <br>
    <a href="login_recepcionista.php">Ir al login recepcionista</a>

    <?php
    
    require_once('../controllers/controladorRecepcionista.php'); 
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        switch ($error) {
            case 'dni_existente':
                echo '<p style="color: red;">La recepcionista ya está registrada, redirigiendo al login</p>';
                header('Refresh:3 login_recepcionista.php?dni'); 
                break;
               
            default:
                echo '<p style="color: red;">Ha ocurrido un error</p>';
        }
    }
    ?>
</body>
</html>