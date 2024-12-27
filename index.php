
<?php


/* The code snippet you provided is a PHP script that serves as the main entry point for a web
application. Here's a breakdown of what the code does: */

// Incluye el archivo del controlador de Recepcionista
require_once 'controllers/controladorRecepcionista.php';


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