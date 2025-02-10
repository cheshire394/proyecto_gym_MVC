<?php

require_once __DIR__ . '/../models/Persona.php';
require_once __DIR__ . '/../models/Trabajador.php';
require_once __DIR__ . '/../models/Recepcionista.php';
require_once __DIR__ . '/../models/Monitor.php';


class ControladorTrabajadores {


    public static function verMonitores() {


    try{


        $monitores = Monitor::verMonitores() ?? [];  
        return $monitores; 


    }catch(PDOException $e){

        $msg= $e->getMessage(); 
        header('Location: /proyecto_gym_MVC/view/trabajadores/verTrabajadores.php?msg=' . urldecode($msg));

    }catch(Exception $e){
        $msg= $e->getMessage(); 
        header('Location: /proyecto_gym_MVC/view/trabajadores/verTrabajadores.php?msg=' . urldecode($msg));

    }

}



public static function verRecepcionistas() {


    try{


        $recepcionistas = Recepcionista:: verRecepcionistas() ?? [];  
        return $recepcionistas; 

    }catch(PDOException $e){

        $msg= $e->getMessage(); 
        header('Location: /proyecto_gym_MVC/view/trabajadores/verTrabajadores.php?msg=' . urldecode($msg));

    }catch(Exception $e){
        $msg= $e->getMessage(); 
        header('Location: /proyecto_gym_MVC/view/trabajadores/verTrabajadores.php?msg=' . urldecode($msg));

    }


  

}


}