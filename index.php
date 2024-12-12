
<?php
/* The code snippet `ini_set('display_errors', 1);`, `ini_set('display_startup_errors', 1);`, and
`error_reporting(E_ALL);` is used to configure the error reporting settings in PHP. Here's what each
line does: */

//Visualización de errores para facilitar la depuración
ini_set('display_errors', 1); // Activa la visualización de errores
ini_set('display_startup_errors', 1); // Activa los errores de inicio de PHP
error_reporting(E_ALL); // Muestra todos los errores (notices, warnings, fatal errors)
//----------------------------------------------------------------------------------------------------

/* The code snippet you provided is a PHP script that serves as the main entry point for a web
application. Here's a breakdown of what the code does: */

// Incluye el archivo del controlador de Recepcionista
require_once 'controllers/controladorRecepcionista.php';

// Mensaje de depuración para confirmar que se cargó este archivo
echo "PRUEBA: estoy en index.php"; 

// Verifica si se ha pasado una acción mediante el método GET
if (isset($_GET['action'])) {
    $action = $_GET['action']; // Obtiene la acción desde el parámetro GET
    $controlador = new ControladorRecepcionista(); // Crea una instancia del controlado

    // Realiza una acción según el valor de 'action'
    switch ($action) {
        case 'login':
            $controlador->login();
            break;

        case 'registro':
            $controlador->registro();
            break;

        case 'logout': 
            $controlador->logout();
            break;

        default:
            // Mensaje por defecto si no reconoce el action
            echo "Acción no reconocida.";
            break;
    }
} else {
    // Mensaje si no se ha pasado ninguna acción
    echo "No hay ningún acción disponible";
}
?>