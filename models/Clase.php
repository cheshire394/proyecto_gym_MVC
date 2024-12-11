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

         public function __get($name){
            if(property_exists($this, $name)) return $this->$name; 
            else throw new Exception("ERROR EN EL GETTER DE LA CLASE 'CLASES': SE HA TRATADO DE OBTENER UNA PROPIEDAD QUE NO EXISTE"); 
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


        public static function addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio) {
          
        
            try {

                        //RECUPERAMOS LOS MONITORES EXISTENTES
                        $rutaJSON = __DIR__ . '/../data/monitores.json';

                        // Leer el contenido actual del archivo JSON
                        $monitoresJSON = file_get_contents($rutaJSON);
                        $monitores = json_decode($monitoresJSON, true);

                        // Flag para saber si se modificó el JSON
                        $modificado = false;
                        $monitorEncontrado = null;

                        // Buscar y modificar el monitor
                        foreach($monitores as &$monitor) {
                            if($monitor['dni'] == $dni_monitor) {
                                // Si la disciplina no existe, añadirla
                                if(!in_array($nombre_actividad, $monitor['disciplinas'])) {
                                    $monitor['disciplinas'][] = $nombre_actividad;
                                    $modificado = true;
                                }
                                
                                // Guardar el monitor encontrado
                                $monitorEncontrado = $monitor;
                                break;
                            }
                        }

                        // Si se modificó el JSON, guardarlo
                        if ($modificado) {
                            file_put_contents($rutaJSON, json_encode($monitores, JSON_PRETTY_PRINT));
                        }

                        // Verificar que se encontró el monitor
                        if (!$monitorEncontrado) {
                            throw new datosIncorrectos('<b>ERROR: No se encontró el monitor con DNI ' . $dni_monitor . '</b>');
                        }

                        // Crear el objeto Monitor
                        new Monitor(
                            $monitorEncontrado['dni'],
                            $monitorEncontrado['nombre'],
                            $monitorEncontrado['apellidos'],
                            $monitorEncontrado['fecha_nac'],
                            $monitorEncontrado['telefono'],
                            $monitorEncontrado['email'],
                            $monitorEncontrado['cuenta_bancaria'],
                            'monitor',
                            $monitorEncontrado['sueldo'] ?? 1100,
                            $monitorEncontrado['horas_extra'] ?? 0,
                            $monitorEncontrado['jornada'] ?? 40,
                            $monitorEncontrado['disciplinas']
                        );
        
                        // Crear un nuevo objeto Clase
                        $clase = new Clase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio);

                        // Guardar la clase en el archivo JSON
                        $clase->guardarClaseEnJSON();

                        return true; 

    
    } catch(datosIncorrectos $e) {
        return $e->datosIncorrectos();
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
        
        
        private function guardarClaseEnJSON() {

            $rutaJSON = __DIR__ . '/../data/clases.json';
           
        
            // Leer el contenido actual del archivo JSON
            $clasesJSON = file_get_contents($rutaJSON);
            $clasesJSON = json_decode($clasesJSON, true);


            //Si existe otra clase con otro id_clase igual al que estamos insertando, eliminamos: 
            foreach($clasesJSON as $i => $claseJSON){
                if($claseJSON['id_clase'] == $this->id_clase){
                    unset($clasesJSON[$i]); 
                    break; 
                }
            }
        
            // Agregar la nueva clase a los datos existentes
            $clasesJSON[] = [
                'id_clase' => $this->dia_semana."-".$this->hora_inicio,
                'dni_monitor' => $this->dni_monitor,
                'nombre_actividad' => $this->nombre_actividad,
                'dia_semana' => $this->dia_semana,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin'=>$this->horaFinalClase($this->hora_inicio) 
            ];

            // Guardar los datos actualizados en el archivo JSON
            file_put_contents($rutaJSON, json_encode($clasesJSON, JSON_PRETTY_PRINT));

        }

        
        /*LAS CLASES, SON MUY IMPORTANTES PARA LA GESTIÓN DEL NEGOCIO, CUALQUIER CAMBIO EN UNA PROPIEDAD DE OBJETO, VA A AFECTAR
        A  MONITORES Y ADEMÁS SI EL GIMNASIO SOLO TIENE UNA SALA, NO TIENE SENTIDO QUE POR EJEMPLO PUEDA MODIFICAR LA HORA
        DE LA CLASE CON UN SETTER Y DUPLICAR UNA CLASE A LA MISMA HORA (PORQUE FISICAMENTE ESE ESPACIO NO EXISTE EN EL GIMNASIO)
        POR ESE MOTIVO UNA VEZ EL OBJETO CLASE ES CREADO, LA UNICA PROPIEDAD MODIFICABLE ES EL MONITOR, Y SIEMPRE Y CUANDO CUMPLA LOS
        REQUISITOS DEL METODO ASGINAR MONITOR: */      
             
        public function sustituirMonitor(string $dni_new_monitor)
        {           
                    $this->asignarMonitor($dni_new_monitor); 
                    $this->dni_monitor = $dni_new_monitor;
                   
                    return $this;
        } 



       


        private function horaFinalClase($hora_inicio){

            $hora_inicio = substr($hora_inicio, 0, 2); 
            $hora_fin= strval(intval($hora_inicio) + self::DURACION_CLASE);  //cada clase durará dos horas
            
            return "$hora_fin:00";   
        }

        
       

      

         
        public static function Clases_filtradas($propiedad_filtrada, $valor_filtrado){


            try{
                $rutaJSON = __DIR__ . '/../data/clases.json';
            
                // Leer el contenido actual del archivo JSON
                $clasesJSON = file_get_contents($rutaJSON);
                $clasesJSON = json_decode($clasesJSON, true);


            
                foreach($clasesJSON as $i => $claseJSON){
                    new Clase(
                        
                    $claseJSON['dni_monitor'],
                    $claseJSON['nombre_actividad'],
                    $claseJSON['dia_semana'],
                    $claseJSON['hora_inicio']

                    ); 
                }
                    

                $clases_filtradas = array_filter(self::$horario_gym, function($clase) use ($propiedad_filtrada, $valor_filtrado) {
                    return $clase->$propiedad_filtrada === $valor_filtrado;
                });

                return $clases_filtradas; 
                

            }catch(datosIncorrectos $e){
                return $e->datosIncorrectos(); 

            }catch(Exception $e){
                return $e->getMessage(); 
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

        try{
                $rutaJSON = __DIR__ . '/../data/clases.json';
            
                // Leer el contenido actual del archivo JSON
                $clasesJSON = file_get_contents($rutaJSON);
                $clasesJSON = json_decode($clasesJSON, true);


            
                foreach($clasesJSON as $i => $claseJSON){
                    new Clase(
                        
                    $claseJSON['dni_monitor'],
                    $claseJSON['nombre_actividad'],
                    $claseJSON['dia_semana'],
                    $claseJSON['hora_inicio']

                    ); 
                }
                    return self::$horario_gym; //se crea el array con todas las clases cuando llamamos al constructor.

            }catch(datosIncorrectos $e){
                return $e->datosIncorrectos(); 

            }catch(Exception $e){
                return $e->getMessage(); 
            }
        }

        /* toArray es una alternativa a get_object_vars($obj) , ya que este metodo no es capaz devolverte las propiedades
         cuando lo llamas desde una script diferente al de la clase al cual pertenece el objeto, y to array, si nos permite esta acción:*/

        public function toArray() {
            return [
                'id_clase' => $this->id_clase,
                'dni_monitor' => $this->dni_monitor,
                'nombre_actividad' => $this->nombre_actividad,
                'dia_semana' => $this->dia_semana,
                'hora_inicio' => $this->hora_inicio,
                'hora_fin' => $this->hora_fin
            ];
        }
    }




?>