<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


final class Monitor extends Trabajador {

    private $disciplinas = []; 
    private  $clases = []; 




    function __construct(
        
        $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,
        $cuenta_bancaria,$funcion='monitor',$sueldo = 1100, $horas_extra = 0, $jornada=40,
        $disciplinas = []) {
    
        // clases se rellenará cuando se cree un objeto clase, en el contructor de dicha clase, que llama al método asignarMonitor.
        $this->clases = []; 

        $this->funcion='monitor'; 
        $this->disciplinas = $disciplinas; //disciplinas se debe de dar cuando se crea el objeto, por eso está definifo en el constructor

        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,$cuenta_bancaria,$funcion, $sueldo, $horas_extra, $jornada); 
    }

    public function __set($name, $value) {

        if ($name == 'clases' || $name == 'disciplinas') {

            $this->$name = $value;

        }else {

            parent::__set($name, $value); 
        }
    }

    public function __get($name) {
        if ($name == 'clases' || $name == 'disciplinas') {
            return $this->$name; 
        } else {
            return parent::__get($name); 
        }
    }

   

    

    


  
    
}

?>