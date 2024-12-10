<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';

class ControladorClases {

  
    public function addClase() {
        
        // Obtener los datos del formulario
        $dni_monitor = $_POST['dni_monitor'];
        $nombre_actividad = $_POST['nombre_actividad'];
        $dia_semana = $_POST['dia_semana'];
        $hora_inicio = $_POST['hora_inicio'];
    
        $exitoso = Clase::addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio); 
    
        if($exitoso){

            // registro  correcto: redirigir a mostrar Clases
            header('Location: /MVC2/view/clases/verClases.php?msg=addClase');
            exit;
        }else{
            header('Location: /MVC2/view/clases/addClase.php?msg=errorAddClase');
            exit;
        }
    
        
    }
    

    

  
}


