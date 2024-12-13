<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../../controllers/controladorSocios.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    $controladorSocios = new controladorSocios();

    switch ($action) {

        case 'addSocio':
            $controladorSocios->addSocio();
            break;

        case 'modifySocio': // Acción para modificar un socio
            $controladorSocios->modificarSocio();
            break;

        default:
            echo "Acción no reconocida.";
            break;
    }
} else {
    echo "No hay ninguna acción disponible";
}
?>
