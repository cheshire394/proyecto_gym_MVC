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

            //guardamos la nueva clase en un array asociativo
            self::$horario_gym[$this->id_clase] = $this; 

        }

         public function __get($name){
            if(property_exists($this, $name)) return $this->$name; 
            else throw new Exception("ERROR EN EL GETTER DE LA CLASE 'CLASES': SE HA TRATADO DE OBTENER UNA PROPIEDAD QUE NO EXISTE"); 
        }

        public function setDniMonitor($valor){
            $this->dni_monitor=$valor; 
        }


    

        public static function addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio) {
            try {
                // Recuperamos los monitores existen
                $rutaJSON = __DIR__ . '/../data/monitores.json';
                $monitoresJSON = file_get_contents($rutaJSON);
                $monitores = json_decode($monitoresJSON, true);
        
                // Buscar el monitor en el JSON
                $monitorEncontrado = null;
                foreach ($monitores as &$monitor) {
                    if ($monitor['dni'] === $dni_monitor) {

                        // Inicializar "clases" y "diciplinas" si no existe en el JSON
                        if (!isset($monitor['clases'])) {
                            $monitor['clases'] = [];
                        }

                        if (!isset($monitor['disciplinas'])) {
                            $monitor['disciplinas'] = [];
                        }
                        $monitorEncontrado = &$monitor;
                        break;
                    }
                }
        
                // Verificamos que se encontró el monitro antes de continuar 
                if (!$monitorEncontrado) {
                    throw new datosIncorrectos('<b>ERROR: No se encontró el monitor con DNI ' . $dni_monitor . '</b>');
                }
        
                // Añadimos la disciplina si no existe en el array monitores solo si no existe (para no duplicar)
                if (!in_array($nombre_actividad, $monitorEncontrado['disciplinas'])) {
                    $monitorEncontrado['disciplinas'][] = $nombre_actividad;
                }
        
                // Crear un nuevo objeto Clase
                $clase = new Clase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio);
        
                // Agregamos la nueva clase al monitor array del monitor que la imparte
                $monitorEncontrado['clases'][$clase->id_clase] = [
                    'nombre_actividad' => $nombre_actividad,
                    'dia_semana' => $dia_semana,
                    'hora_inicio' => $hora_inicio
                ];
        
                // Actualizamos la jornada del monitor, según el número de clases que ejerce
                $monitorEncontrado['jornada'] = count($monitorEncontrado['clases']) * Clase::DURACION_CLASE;
        
                // Guardar los cambios en el archivo JSON monitores
                file_put_contents($rutaJSON, json_encode($monitores, JSON_PRETTY_PRINT));
        
                // Guardar la clase en el JSON de la clases.
                $clase->guardarClaseEnJSON();
        
                return true;//retorna conexito, para el mensaje de la vista

            } catch (datosIncorrectos $e) {
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
            $clasesJSON[] = $this->toArray(); 

            // Guardar los datos actualizados en el archivo JSON
            file_put_contents($rutaJSON, json_encode($clasesJSON, JSON_PRETTY_PRINT));

        }

        
        /*LAS CLASES, SON MUY IMPORTANTES PARA LA GESTIÓN DEL NEGOCIO, CUALQUIER CAMBIO EN UNA PROPIEDAD DE OBJETO, VA A AFECTAR
        A  MONITORES Y ADEMÁS SI EL GIMNASIO SOLO TIENE UNA SALA, NO TIENE SENTIDO QUE POR EJEMPLO PUEDA MODIFICAR LA HORA
        DE LA CLASE CON UN SETTER Y DUPLICAR UNA CLASE A LA MISMA HORA (PORQUE FISICAMENTE ESE ESPACIO NO EXISTE EN EL GIMNASIO)
        POR ESE MOTIVO UNA VEZ EL OBJETO CLASE ES CREADO, LA UNICA PROPIEDAD MODIFICABLE ES EL MONITOR, ES DECIR NO EXISTE SETTER DE CLASES*/
             
        public static function sustituirMonitor($dni_sustituto, $dia, $hora)
{           
    try {
                    $rutaClasesJSON = __DIR__ . '/../data/clases.json';
                    $rutaMonitoresJSON = __DIR__ . '/../data/monitores.json';

                    // Recuperamos la información del JSON de clases
                    $clasesJSON = json_decode(file_get_contents($rutaClasesJSON), true);

                    // Recuperamos el horario actual del gimnasio
                    $clases = self::getHorario_gym(); 


                    // Clave de la clase a modificar
                    $class_id_sustituir = $dia."-".$hora;

                    // Verificar si la clase existe
                    if (!isset($clases[$class_id_sustituir])) {
                        throw new Exception("Clase no encontrada");
                    }

                    // Almacenamos el DNI del monitor antiguo
                    $old_monitor = $clases[$class_id_sustituir]->__get('dni_monitor');

                    // Cambiamos el monitor de la clase en el objeto Clase
                    $clases[$class_id_sustituir]->setDniMonitor($dni_sustituto);

                    // Convertimos los objetos Clase de vuelta a un array para guardar en JSON
                    $clasesJSON = [];
                    foreach ($clases as $key => $clase) {
                        $clasesJSON[] = [
                            'id_clase' => $key,
                            'dni_monitor' => $clase->__get('dni_monitor'),
                            'nombre_actividad' => $clase->__get('nombre_actividad'),
                            'dia_semana' => $clase->__get('dia_semana'),
                            'hora_inicio' => $clase->__get('hora_inicio'),
                            'hora_fin' => $clase->__get('hora_fin') 
                        ];
                    }

                    // Guardar los datos actualizados en el archivo JSON de clases
                    file_put_contents($rutaClasesJSON, json_encode($clasesJSON, JSON_PRETTY_PRINT));

                    // Recuperamos los monitores
                    $monitoresJSON = json_decode(file_get_contents($rutaMonitoresJSON), true);

                    // Encontrar y actualizaMOS los monitores
                    foreach ($monitoresJSON as &$monitorJSON) {
                        
                        if ($monitorJSON['dni'] === $old_monitor) {
                            // Eliminamos la clase del monitor antiguo
                            if (isset($monitorJSON['clases'][$class_id_sustituir])) {
                                unset($monitorJSON['clases'][$class_id_sustituir]);
                            }
                            
                            // Actualizamos jornada
                            $monitorJSON['jornada'] -= self::DURACION_CLASE;
                        }

                      
                        if ($monitorJSON['dni'] === $dni_sustituto) {
                            // Añadimos la clase al nuevo monitor
                            $monitorJSON['clases'][$class_id_sustituir] = [
                                'nombre_actividad' => $clases[$class_id_sustituir]->__get('nombre_actividad'),
                                'dia_semana' => $clases[$class_id_sustituir]->__get('dia_semana'),
                                'hora_inicio' => $clases[$class_id_sustituir]->__get('hora_inicio')
                            ];
                            
                            // Actualizamos jornada del nuevo monitor
                            $monitorJSON['jornada'] += self::DURACION_CLASE;
                        }
                    }

                    // Guardar los monitores actualizados
                    file_put_contents($rutaMonitoresJSON, json_encode($monitoresJSON, JSON_PRETTY_PRINT));

                    return true;

    } catch(datosIncorrectos $e){
        
        return $e->datosIncorrectos();
    
    }catch(Exception $e) {
        return $e->getMessage();
    }
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
            
                // recuperamos el contenido actual del archivo JSON de clases
                $clasesJSON = file_get_contents($rutaJSON);
                $clasesJSON = json_decode($clasesJSON, true);


            // creamos todos los objetos que se encuentran en el json
                foreach($clasesJSON as $i => $claseJSON){
                    new Clase(
                        
                    $claseJSON['dni_monitor'],
                    $claseJSON['nombre_actividad'],
                    $claseJSON['dia_semana'],
                    $claseJSON['hora_inicio']

                    ); 
                }
                    return self::$horario_gym; //este array se crea cuando llamamos al constructor.

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