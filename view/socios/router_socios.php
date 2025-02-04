<?php
require_once '../../controllers/controladorSocios.php';
//Mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'eliminarSocio':
            controladorSocios::eliminarSocio();
            break;
        
        case 'mostrarFormularioModificar':
            controladorSocios::mostrarFormularioModificar();
            break;

        case 'modificarSocio':
            controladorSocios::modificarSocio();
            break;
        
        case 'filtrar_socios':

            
            $socios_filtrados = controladorSocios::filtrarSocios(); 
            include('verSocios.php');

            break; 
        
        case 'addSocio':
            
            controladorSocios::addSocio(); 
            break; 


        default:
            echo "Acción no reconocida.";
            break;
    }
} else {
    echo "No hay ninguna acción disponible";
}
?>
