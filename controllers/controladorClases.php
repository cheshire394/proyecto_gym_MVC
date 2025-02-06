<?php


require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Monitor.php';
require_once __DIR__ . '/../models/Clase.php';

class ControladorClases {

    
    
    public static function addClase() {



    if(isset($_POST['addClase'])){

        //recogemos los valores del formulario (son required, no es necesario comprobar que existan):
        $dni_monitor = $_POST['dni_monitor'];

        //corregimos la entrada
        if($_POST['nombre_actividad'] == 'mma' || $_POST['nombre_actividad'] == 'MMA') $nombre_actividad = 'MMA'; 
        else $nombre_actividad = trim(strtolower($_POST['nombre_actividad']));

        $id_clase = $_POST['id_clase'];
       

        Clase::addClase($id_clase, $dni_monitor, $nombre_actividad);

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


    if(isset($_POST['sustituir_monitor'])){

        //recogemos los valores del formulario: 
        $dni_monitor_new = $_POST['dni_monitor'];
        $id_clase = $_POST['id_clase'];
        

        Clase::sustituirMonitor($dni_monitor_new, $id_clase);

    }

      


    }




    public static function eliminarDisciplina() {

        $nombre_actividad = $_POST['nombre_actividad'];

        try {
            $exitoso = Clase::eliminarDisciplina($nombre_actividad);

            if ($exitoso) {
                header('Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=eliminadaDisciplina');
                exit;
            }


            //La excepción saltará si no existe ninguna clase guardada el json, o no hay ninguna clase con el nombre de actividad seleccionado
        } catch (PDOException $e) {
            $mensaje = urlencode($e->getMessage());
            header("Location: /proyecto_gym_MVC/view/clases/eliminarDisciplina.php?msg=$mensaje");
            exit;
        }
    }


    /**
     * The function `eliminarClase` in PHP collects form values, constructs an `id_clase` to delete,
     * attempts to delete the class using the `Clase` class, and redirects based on success or failure.
     */
    public static function eliminarClase() {

        //recogemos los valores del formulario
        $dia = $_POST['dia_semana'];
        $hora = $_POST['hora_inicio']; 

        //construimod el id_clase a eliminar: 
        $id_clase=$dia."-".$hora; 

        try {

            $exitoso = Clase::eliminarClase($id_clase);

            if ($exitoso) {
                header('Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=eliminarClase');
                exit;
            }

            //La excepción saltará si no existe ninguna clase guardada el json, o bien no hay ninguna clase con ese id
        } catch (PDOException $e) {
            $mensaje = urlencode($e->getMessage());
            header("Location: /proyecto_gym_MVC/view/clases/eliminarClase.php?msg=$mensaje");
            exit;
        }
    }
}
