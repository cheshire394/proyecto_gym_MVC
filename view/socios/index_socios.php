<!-- Página Index Socio -->

<?php

//******************************************************************* RUTAS ***************************************************************************
require_once '../../controllers/controladorSocios.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    $controladorSocios = new controladorSocios();

    switch ($action) {

        case 'addSocio':
            $controladorSocios->addSocio();
            break;

        case 'modificarSocio':
            $controladorSocios->modificarSocio();
            break;

        case 'verSocio':

            $sociosEncontrados= $controladorSocios->verSocio();
            break; 
            

        case 'eliminarSocio':
            $controladorSocios->eliminarSocio();
            break;

        default:
            echo "Acción no reconocida.";
            break;
    }
} else {
    echo "No hay ninguna acción disponible";
}
