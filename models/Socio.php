<?php

   final class Socio extends Persona{

        private $tarifa; //2 clases, //3 clases // indefinidas (a la semana); hay que implementarlo en el registro
        private $fecha_alta; 
        private $fecha_baja; 
        private $reservas_clases=[]; 
        
        private static $contador_socios=0;
        private static $socios=[]; 
        //VER QUE HACER CON LA CUENTA BANCARIA
        private static $bajas_socios=[];




        function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,$tarifa="2")
        {   
            self::$contador_socios++;
            $this->fecha_alta= date('d-m-Y'); 
            $this->fecha_baja='desconocida'; 
            $this->tarifa=$tarifa;
            
            parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);   

            self::$socios[$this->dni]=$this; 
        }


        public function __set($name, $value) {

            if($name == 'fecha_alta'){
                throw new datosIncorrectos("ERROR DESDE EL SETTER SOCIO: NO ES POSIBLE MODIFICAR LA FECHA DE ALTA DE UN SOCIO EN EL REGISTRO");
            }


            if (property_exists($this, $name)) {
                $this->$name = $value;

            
            }else {
                throw new Exception("ERROR DESDE EL SETTER SOCIO: La propiedad '$name' no existe en la clase Socio.");
            }
        }
    
        public function __get($name) {

            if (property_exists($this, $name)) {
                return $this->$name;
            } else {
                throw new Exception("ERROR DESDE EL GETTER SOCIO: La propiedad '$name' no existe en la clase Socio.");
            }
        }


    public static function mostrarTodosSocios(){

        foreach(self::$socios as $dni => $obj_socio){

            $propiedades = get_object_vars($obj_socio); 

            echo "<b>DATOS DEL SOCIO $dni:</b><br>"; 
            foreach($propiedades as $propiedad => $value){

                if(is_array($value))continue; 
                echo $propiedad ." => ".$value . "<br>"; 
            }
            
            echo "<br>"; 
        }
       


    }

    public function mostrarDatosSocio(){

        $propiedades= get_object_vars($this);

        foreach($propiedades as $propiedad => $value){

            if(is_array($value))continue; 

            echo $propiedad ." => ".$value . "<br>"; 
        }

    }


    //IMPLEMENTAR CON CONTROLLERS Y VIEW
    public function reservarClase(){
        
        require_once('Clase.php'); 
        $clases= Clase::getHorario_gym(); 

        
        }

      //IMPLEMENTAR CON CONTROLLERS Y VIEW
      public function desapuntarseClase(){

      }
    
      

    public function darBajaSocio(){
        
        //A MEDIAS DE IMPLEMENTAR: 
        $this->__set('fecha_baja', date('d-m-Y'));
        self::$contador_socios--; 

        self::$bajas_socios[$this->dni]=$this; //añadimos a los socios que se han dado de baja

        //eliminamos del array de socios: 
        unset(self::$socios[$this->dni]); 
        

    }



       
    }


?>