
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'controllers/controladorRecepcionista.php';

echo "PRUEBA: estoy en index.php"; 


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controlador = new ControladorRecepcionista();

    switch ($action) {
        case 'login':
            $controlador->login();
            break;

        case 'registro':
            $controlador->registro();
            break;
        
        case 'logout': // Acción para cerrar sesión
            $controlador->logout();
            break;

        default:
            echo "Acción no reconocida.";
            break;
    }
} else {
    echo "NO hay ningún acción disponible";
}
?>

