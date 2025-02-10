<?php


require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';

class ControladorClases {

    
    
    public static function addClase() {



    if(isset($_POST['addClase'])){

        //recogemos los valores del formulario (son required, no es necesario comprobar que existan):


        $dni_monitor = $_POST['dni_monitor'];

        //corregimos la entrada para evitar inconsistencias de nombre el la BBDD
        if($_POST['nombre_actividad'] == 'mma' || $_POST['nombre_actividad'] == 'Mma' || $_POST['nombre_actividad'] == 'MMA') $nombre_actividad = 'MMA'; 
        else $nombre_actividad = trim(ucwords(strtolower($_POST['nombre_actividad'])));

        $id_clase = $_POST['id_clase'];
       

        $insertada = Clase::addClase($id_clase, $dni_monitor, $nombre_actividad);

        if($insertada){
             // Redirigir a verClases.php con mensaje de éxito
             $msg = "Clase de $nombre_actividad a las $id_clase registrada correctamente";
             header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=" . urlencode($msg));
             exit;

             
        }else{
            $msg = $insertada; // Aquí se espera que $insertada contenga el mensaje de error de tipo PDOException
            header("Location: /proyecto_gym_MVC/view/clases/addClase.php?msg=$msg");
            exit;

        }

    }
    

}


    public static function horario() {


    try{

            $horario =  Clase::horario() ?? [];

            return $horario; 



    } catch (PDOException $e) {
            $msg =  $e->getMessage();
          
    }catch (Exception $e) {

            $msg =  $e->getMessage();
          
    }


    header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=$msg");
    exit;
}



//Obtiene el dni y nombre de los monitores para el formulario de addCLase (aparece los dnis de lo monitores)
public static function get_monitores(){


    try {
        $monitores = Monitor::get_monitores(); 
        return $monitores;

    } catch (PDOException $e) {
       $msg= $e->getMessage();
    }

    header("Location: /proyecto_gym_MVC/view/clases/addClases.php?msg=$msg");
    exit;



} 


//obtiene el nombre de las disciplinas introducidad en el horario para el formulario
public static function get_disciplinas(){


    try {
        $disciplinas = Clase::get_disciplinas(); 
        return $disciplinas;

    } catch (PDOException $e) {
       $msg= $e->getMessage();
    }

    header("Location: /proyecto_gym_MVC/view/clases/eliminarDisciplina.php?msg=$msg");
    exit;



        
}


//obtiene la clases disponibles para cuando la recepcionista desea añadir una clase 
public static function horas_disponibles(){

    try {
        $horas_disponibles = Clase::horas_disponibles(); 
        return $horas_disponibles;

    } catch (PDOException $e) {
       $msg= $e->getMessage();
    }

    header("Location: /proyecto_gym_MVC/view/clases/addClases.php?msg=$msg");
    exit;




}


public static function horas_ocupadas(){

    try {
    $horas_ocupadas = Clase::horas_ocupadas(); 
        return $horas_ocupadas;

    } catch (PDOException $e) {
       $msg= $e->getMessage();
    }

    header("Location: /proyecto_gym_MVC/view/clases/sustituirMonitor.php?msg=$msg");
    exit;




}




public static function sustituirMonitor() {
    if (isset($_POST['sustituir_monitor'])) {
        try {
            $dni_monitor_new = $_POST['dni_monitor'];
            $id_clase = $_POST['id_clase'];

            $sustitucion_exitosa = Clase::sustituirMonitor($dni_monitor_new, $id_clase);

            if ($sustitucion_exitosa === true) {
                $msg = "Monitor sustituido correctamente";
                header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=" . urlencode($msg));
            } else {
                $msg = $sustitucion_exitosa; // Aquí se espera que $sustitucion_exitosa contenga el mensaje de error de tipo PDOException
                header("Location: /proyecto_gym_MVC/view/clases/sustituirMonitor.php?msg=" . urlencode($sustitucion_exitosa));
            }

            //Si el monitor que que deseamos sustituir es el mismo que hemos introducido en el formulario, el error será de tipo exceptión
        } catch (Exception $e) {
            $msg = $e->getMessage();
            header("Location: /proyecto_gym_MVC/view/clases/sustituirMonitor.php?msg=" . urlencode($msg));
        }
        exit; 
    }
}





    public static function eliminarDisciplina() {

         //Si se ha pulsado el bton eliminar_displina del formulario:
            if (isset($_POST['eliminar_diciplina'])) {
        
                $nombre_actividad = $_POST['nombre_actividad']; 
                $eliminadas = Clase::eliminarDisciplina($nombre_actividad);
    
                if ($eliminadas === true) {
                    $msg = "Eliminadas todas las clases de $nombre_actividad";
                    header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=$msg");
                } else {
                    $msg = $eliminadas; // Aquí se espera que $eliminadas contenga el mensaje de error de tipo PDOException
                    header("Location: /proyecto_gym_MVC/view/clases/eliminarClase.php?msg=$msg");
                    exit;
                }
          
           
        }

        
    }


 
    public static function eliminarClase() {


        //Si se ha pulsado el bton eliminar_clase del formulario:
        if (isset($_POST['eliminar_clase'])) {
        
                $id_clase = $_POST['id_clase']; 
                $eliminada = Clase::eliminarClase($id_clase);
    
                if ($eliminada === true) {
                    $msg = "La clase $id_clase ha sido eliminada con éxito";
                    header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=$msg");
                } else {
                    $msg = $eliminada; // Aquí se espera que $eliminada contenga el mensaje de error de tipo PDOException
                    header("Location: /proyecto_gym_MVC/view/clases/eliminarClase.php?msg=$msg");
                    exit;
                }
          
           
        }
    }
    

    public static function clasesSocios() {
        try {


            return Clase::clasesSocios();


        } catch (PDOException $e) {

             $msg = $e->getMessage(); 

        }catch(Exception $e){

            $msg = $e->getMessage(); 

        }

        header("Location: /proyecto_gym_MVC/view/clases/clasesSocios.php?msg=$msg");
        exit;


    }


   
  

     
}
