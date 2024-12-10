<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('datosIncorrectos.php'); 
require_once('Persona.php'); 
require_once('Trabajador.php'); 
require_once('Monitor.php');
  

    final class Clase{

        
        

        private $dni_monitor;
        private $nombre_actividad;
        private $dia_semana;
        private $hora_inicio;
        private $hora_fin;
        private $id_clase; 
        private static $horario_gym = []; 
        public const  DURACION_CLASE =2; 

        function __construct($dni_monitor, $nombre_actividad, $dia_semana,$hora_inicio)
        {
            $this->dni_monitor=$dni_monitor;
            $this->nombre_actividad=$nombre_actividad;
            $this->dia_semana=$dia_semana; 
            $this->hora_inicio= $hora_inicio; 
            $this->hora_fin= $this->horaFinalClase($hora_inicio);

            //NOTA* -> este id de clase, solamente será válido mientras que el gym tenga una única sala, si en un futuro
            //la empresa creciera y ampliara el número de salas, tendremos que buscar otro id para que puedan existir dos o mas clases al mismo tiempo.
            $this->id_clase= $this->dia_semana."-".$this->hora_inicio;

            //Esta método, sirve para que los monitores puedan rellenar su array de clases, cuando se crea una clase.
            //pero es importante, porque si el monitor no existe o no ejerce esta disciplina que se está creando, el objeto 
            //no se va a llegar a crear, porque las excepciones personalizadas lo van a impedir.
            $this->asignarMonitor($this->dni_monitor); 

            self::$horario_gym[$this->id_clase] = $this; 

        }

        
        //LAS CLASES, SON MUY IMPORTANTES PARA LA GESTIÓN DEL NEGOCIO, CUALQUIER CAMBIO EN UNA PROPIEDAD DE OBJETO, VA A AFECTAR
        //A LA CLASE MONITORES Y ADEMÁS SI EL GIMNASIO SOLO TIENE UNA SALA, NO TIENE SENTIDO QUE POR EJEMPLO PUEDA MODIFICAR LA HORA
        //DE LA CLASE CON UN SETTER Y DUPLICAR UNA CLASE A LA MISMA HORA (PORQUE FISICAMENTE ESE ESPACIO NO EXISTE EN EL GIMNASIO)
        //POR ESE MOTIVO UNA VEZ EL OBJETO CLASE ES CREADO, LA UNICA PROPIEDAD MODIFICABLE ES EL MONITOR, Y SIEMPRE Y CUANDO CUMPLA LOS
        //REQUISITOS DEL METODO ASGINAR MONITOR:        
             
        public function sustituirMonitor(string $dni_new_monitor)
        {           
                    $this->asignarMonitor($dni_new_monitor); 
                    $this->dni_monitor = $dni_new_monitor;
                   
                    return $this;
        } 



        public function __get($name){
            if(property_exists($this, $name)) return $this->$name; 
            else throw new Exception("ERROR EN EL GETTER DE LA CLASE 'CLASES': SE HA TRATADO DE OBTENER UNA PROPIEDAD QUE NO EXISTE"); 
        }



        private function horaFinalClase($hora_inicio){

            $hora_inicio = substr($hora_inicio, 0, 2); 
            $hora_fin= strval(intval($hora_inicio) + self::DURACION_CLASE);  //cada clase durará dos horas
            
            return "$hora_fin:00";   
        }

        
        private function asignarMonitor($dni_monitor){

           //obtenems todos los monitores
           $monitores = Trabajador:: getTrabajadoresMonitores(); 
           
           if(isset($monitores[$dni_monitor])){

           if(isset($monitores[$dni_monitor])){
            $monitor = $monitores[$dni_monitor]; //obtenemos el objeto de monitores que corresponde a ese dni.

            //Comprobamos que el monitor asignado, además de existir, en su array disciplinas pueda ejercer como monitor en esa disciplina.
            if(!in_array($this->nombre_actividad, $monitor->__get('disciplinas'))){

                throw new datosIncorrectos('<b>ERROR: EN LA CREACIÓN DE UN OBBJETO CLASE, EL MONITOR ASIGNADO NO EJERCE ESA ACTIVIDAD</b>');
            } 

             // Agregar la clase al array `clases` del monitor
             $clases_monitor = $monitor->__get('clases');

             //el horario será el id de la clase, para que un profesor no pueda tener más de una clase a la misma hora, el mismo dia de la semana. 

             if(isset($clases_monitor[$this->id_clase])){
                throw new datosIncorrectos('<b>ERROR: EN LA CREACIÓN DE UN OBBJETO CLASE, EL MONITOR YA TIENE ASIGNADA UNA CLASE EN ESE HORARIO</b>');
             } 

             $clases_monitor[$this->id_clase] = $this;
             $monitor->__set('clases', $clases_monitor);
             $monitor->__set('jornada', count($clases_monitor) * Clase::DURACION_CLASE); //actualizamos las hosras trabajadas


        }else throw new datosIncorrectos('<b>ERROR: EN LA CREACIÓN DE UN OBBJETO CLASE, EL DNI ASIGNADO, NO CORRESPONDE A NINGUN MONITOR</b>'); 
      
    }
        }

        public static function mostrar_todas_Clases(){

            echo "<b>DATOS DE TODAS LAS CLASES DISPONIBLES: </b><br>";
                foreach(self::$horario_gym as $id_clase => $obj_Clase){

                    $propiedades = get_object_vars($obj_Clase); 

                    foreach($propiedades as $propiedad => $valor){
                        echo "$propiedad => $valor <br>"; 
                    }
                    echo "<br>"; 

                

                }
        }

         
        private static function Clases_filtradas($propiedad_filtrada, $valor_filtrado){
           
                     $clases_filtradas = array_filter(self::$horario_gym, function($clase) use ($propiedad_filtrada, $valor_filtrado) {
                        return $clase->$propiedad_filtrada === $valor_filtrado;
                    });

            return $clases_filtradas; 
        
         }


         public static function mostrar_clases_filtradas($propiedad_filtrada, $valor_filtrado){

              //MOSTRAMOS LOS RESULTADOS DE LAS FILTRACIONES
              $clases_filtradas=self::Clases_filtradas($propiedad_filtrada, $valor_filtrado);


              if(empty($clases_filtradas)) echo "<p> no existe ninguna clase disponible con el filtro establecido</p><br>"; 
              else{
                echo "<p><b>CLASES DISPONIBLES CON EL FILTRO ".strtoupper($valor_filtrado).":</b></p>"; 
                  foreach ($clases_filtradas as $obj_Clase) {
                      $propiedades = get_object_vars($obj_Clase);
              
                      foreach ($propiedades as $propiedad => $valor) {
                          echo "$propiedad => $valor <br>";
                      }
                      echo "<br>";
                  }
              }
         }


         public static function eliminarDisciplina($nombre_actividad)
         {

            $clases_filtradas = self::Clases_filtradas('nombre_actividad', $nombre_actividad);
            $clases = self::$horario_gym; 

            //obtenems todos los monitores, para restar sus jornadas de la disciplina que impartian (si es necesario): 
           $monitores = Trabajador:: getTrabajadoresMonitores(); 

            foreach($clases as $id_clase => $obj_clase){

                if(array_key_exists($id_clase, $clases_filtradas)){

                    //reducimos la jornada laboral del monitor que impartia esta disciplina para mantener los datos actualizados:
                    $dni_monitor_clase_eliminada = $obj_clase->__get('dni_monitor'); 
                     
                    if(isset($monitores[$dni_monitor_clase_eliminada])){

                        $monitor =  $monitores[$dni_monitor_clase_eliminada]; //obtenemos el objeto monitor
                        $jornada_monitor = $monitor->__get('jornada'); //obtenemos su jornada
                        $jornada_monitor -= self::DURACION_CLASE; //restamos dos horas, de la clase que estamos eliminando
                        $monitor->__set('jornada', $jornada_monitor); //actualizamos los cambios

                    }

                    unset(self::$horario_gym[$id_clase]); //eliminamos el objeto del array que almacena todas las clases
                    unset($obj_Clase); //eliminamos el objeto en si.
                }

         }
        }



        public static function getHorario_gym()
        {
                return self::$horario_gym;
        }
    }




?>