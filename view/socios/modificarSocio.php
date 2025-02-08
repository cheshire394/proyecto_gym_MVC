<?php
session_start();
if (!isset($_SESSION['socio'])) {
    header('Location: /proyecto_gym_MVC/view/socios/verSocios.php?msg=Error: No se encontró el socio.');
    exit;
}

$socio = $_SESSION['socio'];
unset($_SESSION['socio']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario Modificar Socio</title>
    <link rel="stylesheet" href="/proyecto_gym_MVC/stylos/form_stylos.css">
</head>
<body>
    <fieldset>
        <legend>Modificar socio</legend>
        <form method="POST" action="router_socios.php?action=modificarSocio">

            <input type="hidden" name="dni" value="<?php echo htmlspecialchars($socio['dni']); ?>">

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($socio['nombre']); ?>" required><br><br>

            <label for="apellidos">Apellidos:</label>
            <input type="text" id="apellidos" name="apellidos" value="<?php echo htmlspecialchars($socio['apellidos']); ?>" required><br><br>

            <label for="fecha_nac">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nac" name="fecha_nac" value="<?php echo htmlspecialchars($socio['fecha_nac']); ?>" required><br><br>

            <label for="telefono">Teléfono:</label>
            <input type="number" id="telefono" name="telefono" value="<?php echo htmlspecialchars($socio['telefono'] ?? ''); ?>" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($socio['email'] ?? 'no aportado'); ?>" required><br><br>

            <label for="tarifa">Tarifa:</label>
            <select id="tarifa" name="tarifa" required>
                <option value="1" <?php echo ($socio['tarifa'] ?? '') == '1' ? 'selected' : ''; ?>>1 clase</option>
                <option value="2" <?php echo ($socio['tarifa'] ?? '') == '2' ? 'selected' : ''; ?>>2 clases</option>
                <option value="3" <?php echo ($socio['tarifa'] ?? '') == '3' ? 'selected' : ''; ?>>3 clases</option>
            </select><br><br>

            <label for="fecha_alta">Fecha de Alta:</label>
            <input type="date" id="fecha_alta" name="fecha_alta" value="<?php echo htmlspecialchars($socio['fecha_alta']); ?>" required><br><br>

            <label for="cuenta_bancaria">Cuenta Bancaria:</label>
            <input type="text" id="cuenta_bancaria" name="cuenta_bancaria" value="<?php echo htmlspecialchars($socio['cuenta_bancaria'] ?? 'no aportada'); ?>" required><br><br>
            
            <button type="submit">Modificar Socio</button>

            <!--BOTON volver a socios-->
            <div  style="position: absolute; top: 91%; left: 51%; margin: 10px; padding: 10px 15px; background-color: #2f5b96; border: 1px solid #2f5b96; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                <a href="verSocios.php" style="display: inline-flex; align-items: center; text-decoration: none; color: inherit;">
                                <svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                    
                                    <span style='color:white';>volver a socios</span>
                                </a>
                                
            </div>
        </form>
    </fieldset>
</body>
</html>