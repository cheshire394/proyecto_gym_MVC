<?php

require_once '../../controllers/controladorClases.php';

if (isset($_GET['action'])) {
    $action = $_GET['action'];

    

    switch ($action) {

            case 'addClase':
                controladorClases::addClase();
                break;


            case 'verClasesFiltradas':
                $horario = ControladorClases::mostrar_clases_filtradas();
                require 'clasesFiltro.php'; // Cargamos la vista con los datos procesados
                break;

             case 'eliminarDisciplina': 
                ControladorClases::eliminarDisciplina();
                break;

            case 'sustituirMonitor': 
                ControladorClases::sustituirMonitor();
                break;

            case 'eliminarClase':
                ControladorClases::eliminarClase(); 
                break; 

        
            default:
                echo "Acción no reconocida.";
                break;
        }
} else {
    echo "NO hay ningún acción disponible";
}
?>