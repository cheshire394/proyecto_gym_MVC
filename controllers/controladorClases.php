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
            //mostrar con mensaje de error
            header('Location: /MVC2/view/clases/addClase.php?msg=errorAddClase');
            exit;
        }
    
        
    }

    public static function mostrar_todas_Clases() {
        $clases = Clase::getHorario_gym();
        $horario = [];
    
        // Organizar las clases por día y hora
        foreach ($clases as $id_clase => $obj_Clase) {
            $claseArray = $obj_Clase->toArray(); // Conversión a array para trabajar con los datos
            $horario[$claseArray['dia_semana']][$claseArray['hora_inicio']] = $claseArray;
        }
    
        // Devolver los datos organizados
        return $horario;
    }
    
    public static function mostrar_clases_filtradas(){

        $propiedad_filtrada = $_POST['propiedad_filtrada']; 

        //si el formulario filtra por DNI, pasar a mayusculas el string, pero sino pasar a misnusculas.
        if($propiedad_filtrada == 'dni_monitor') $valor_filtrado = htmlentities(strtoupper(trim($_POST['valor_filtrado']))); 
        else $valor_filtrado = htmlentities(strtolower(trim($_POST['valor_filtrado'])));
        
        
        //recuperramos el array con las clases filtradas
        $clases_filtradas=Clase::Clases_filtradas($propiedad_filtrada, $valor_filtrado);

        if(empty($clases_filtradas)) return []; 

        $horario = [];
        // Organizar las clases por día y hora
        foreach ($clases_filtradas as $id_clase => $obj_Clase) {
            $claseArray = $obj_Clase->toArray(); // Conversión a array para trabajar con los datos
            $horario[$claseArray['dia_semana']][$claseArray['hora_inicio']] = $claseArray;
        }
        // Devolver los datos organizados
        return $horario;


   }

   public function sustituirMonitor(){

    $dia = $_POST['dia']; 
    $hora =$_POST['hora']; 
    $dni_sustituto = $_POST['dni_monitor']; 

    $exitoso= Clase::sustituirMonitor($dni_sustituto, $dia, $hora); 

    if($exitoso){

        // registro  correcto: redirigir a mostrar Clases
        header('Location: /MVC2/view/clases/verClases.php?msg=sustituido');
        exit;
    }else{
        //mostrar con mensaje de error
        header('Location: /MVC2/view/clases/sustituirMonitor.php?msg=errorAddSustituido');
        exit;
    }


    
    
   }

   

    
    

    

  
}


