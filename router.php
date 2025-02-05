
<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Al inicio del script
var_dump($_POST);
var_dump($_GET);

// Incluye el archivo del controlador de Recepcionista
require_once 'controllers/controladorRecepcionista.php';


// Verifica si se ha pasado una acción mediante el método GET
if (isset($_GET['action'])) {
    $action = $_GET['action']; // Obtiene la acción desde el parámetro GET
    

    // Realiza una acción según el valor de 'action'
    switch ($action) {
        case 'login':
            ControladorRecepcionista::login();
            break;

        case 'registro':
            ControladorRecepcionista::registro();
            include('/proyecto_gym_MVC/view/login_recepcionista.php'); 
            break;

        case 'logout':
            ControladorRecepcionista::logout();
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