<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../controllers/controladorClases.php';

echo "PRUEBA: estoy en index.php"; 


if (isset($_GET['action'])) {
    $action = $_GET['action'];

    $controlador = new controladorClases();

    switch ($action) {

        case 'addClase':
            $controlador->addClase();
            break;

        /*case 'verClases':
            $controlador->verClases();
            break;
        
        case 'verClasesFiltradas': // Acción para cerrar sesión
            $controlador->verClasesFiltradas();
            break;

        case 'verClasesFiltradas': // Acción para cerrar sesión
            $controlador->verClasesFiltradas();
            break;

        case 'eliminarDiciplina': // Acción para cerrar sesión
            $controlador->eliminarDiciplina();
            break;

        case 'sustituirMonitor': // Acción para cerrar sesión
            $controlador->sustituirMonitor();
            break;*/

    
        default:
            echo "Acción no reconocida.";
            break;
    }
} else {
    echo "NO hay ningún acción disponible";
}
?>