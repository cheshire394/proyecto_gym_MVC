<?php

include_once('datosIncorrectos.php');
require_once('Persona.php');
require_once('Trabajador.php');
require_once('Monitor.php');

final class Clase {

    public const DURACION_CLASE = 2;
    public const RUTA_JSON_CLASE= __DIR__ . '/../data/clases.json';

    private $dni_monitor;
    private $nombre_actividad;
    private $dia_semana;
    private $hora_inicio;
    private $hora_fin;
    private $id_clase;
    

    function __construct($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio) {

        $this->dni_monitor = $dni_monitor;
        $this->nombre_actividad = $nombre_actividad;
        $this->dia_semana = $dia_semana;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $this->horaFinalClase($hora_inicio);

        //NOTA* -> este id de clase, solamente será válido mientras que el gym tenga una única sala, si en un futuro
        //la empresa creciera y ampliara el número de salas, tendremos que buscar otro id para que puedan existir dos o más clases al mismo tiempo.
        $this->id_clase = $this->dia_semana . "-" . $this->hora_inicio;

       
    }



    public function __get($name) {
        if (property_exists($this, $name)) return $this->$name;
        else throw new Exception("ERROR EN EL GETTER DE LA CLASE 'CLASES': SE HA TRATADO DE OBTENER UNA PROPIEDAD QUE NO EXISTE");
    }


    // La unica propiedad de la clase que modificable es el dni del monitor que la imparte, para mantener la congruencia de los datos, ya que 
    // la hora_inicio y la semana constituye la clave primaria, (y representa un espacio de tiempo físico dentro del gimnasio).
    public function setDni_monitor($dni_monitor)
    {
            $this->dni_monitor = $dni_monitor;

            return $this;
    }




   /**
    * The function `addClase` creates a new class, saves it in JSON format, updates monitor data
    * affected by schedule changes, and throws an exception if the monitor data update fails.
    * 
    * @param dni_monitor The `dni_monitor` parameter in the `addClase` function represents the ID
    * number of the monitor who will be assigned to teach the class. It is a unique identifier for each
    * monitor in the system.
    * @param nombre_actividad The `nombre_actividad` parameter in the `addClase` function represents
    * the name of the activity or class that is being added. It could be something like "Yoga",
    * "Pilates", "Zumba", etc. This parameter is used to create a new instance of the `
    * @param dia_semana The parameter `dia_semana` represents the day of the week for the class. It
    * could be a string indicating the day such as "Monday", "Tuesday", etc., or it could be
    * represented by a numerical value where 1 represents Monday, 2 represents Tuesday, and so on.
    * @param hora_inicio The parameter `hora_inicio` in the `addClase` function represents the start
    * time of the class. It is the time at which the class will begin. This parameter is used to
    * specify the exact time when the class will start, typically in a 24-hour format (e.g.,
    * 
    * @return The `addClase` function is returning the result of the
    * `Monitor::actualizarDatosMonitores` method, which is stored in the variable ``. If
    * the data is updated successfully, it will return `true`. If there is an error during the update
    * process, it will throw an exception with the message 'ha surgido un error al actualizar los datos
    * de los monit
    */
    public static function addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio) {
        
        // Crear la clase
        $clase_creada = new Clase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio);
    
        // Guardar clase en JSON
        $monitor_clase_sustituida = $clase_creada->guardarClaseEnJSON();


        //Actualiazar los datos de los monitores afectados con el cambio de horario
        $actualizado= Monitor::actualizarDatosMonitores($dni_monitor, $monitor_clase_sustituida, $clase_creada);


        //si los datos actualizados no se han guardao bien lanzamos excepcition
        if(!$actualizado) throw new Exception('ha surgido un error al actualizar los datos de los monitores'); 

        
       return $actualizado; 
    
    }
     
    
   
   


    /**
     * The function `guardarClaseEnJSON` reads, updates, and saves class data in a JSON file, replacing
     * any existing class with the same ID and returning the previous monitor's ID.
     * 
     * @return The function `guardarClaseEnJSON()` is returning the variable
     * ``, which contains the DNI of the monitor of the class that was
     * replaced or removed during the process of updating the JSON data with a new class.
     */
    private function guardarClaseEnJSON() {
      
        // Leer el contenido actual del archivo JSON
        $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
        $clasesJSON = json_decode($clasesJSON, true);

        //Si existe otra clase con otro id_clase igual al que estamos insertando, eliminamos:
        $monitor_clase_sustituida=null; 
        foreach ($clasesJSON as $id_clase => $claseJSON) {

            if ($id_clase == $this->id_clase) {

                // Obtener el DNI del monitor de la clase antes de eliminarla, ya que habrá que modificar sus condicones:
                $monitor_clase_sustituida = $claseJSON['dni_monitor']; 
                unset($clasesJSON[$id_clase]); // Eliminar la clase que ocupaba ese lugar en el horario
            }
        }

        // Agregar la nueva clase a los datos existentes en formato JSON
        $clasesJSON[$this->id_clase] = $this->toArray(); 

        // Guardar los datos actualizados en el archivo JSON
        file_put_contents(self::RUTA_JSON_CLASE, json_encode($clasesJSON, JSON_PRETTY_PRINT));


        return $monitor_clase_sustituida; 
    }




    

    /**
     * The PHP function `sustituirMonitor` replaces a monitor in a gym class schedule with a substitute
     * monitor, updating the necessary data and saving the changes.
     * 
     * @param dni_monitor_sustituto The parameter `dni_monitor_sustituto` in the `sustituirMonitor`
     * function represents the ID or identifier of the substitute monitor who will be replacing another
     * monitor in a specific class at a particular day and time. This function is responsible for
     * substituting a monitor in a class schedule with
     * @param dia_semana The parameter `dia_semana` in the `sustituirMonitor` function represents the
     * day of the week for which you want to substitute a monitor in a gym class. It is used to
     * identify the specific class based on the day of the week and the start time.
     * @param hora_inicio The parameter `` in the `sustituirMonitor` function represents
     * the start time of the class for which you want to substitute the monitor. It is used to identify
     * a specific class within the schedule. For example, if a class starts at 10:00 AM, the `$
     * 
     * @return a boolean value `true` if the monitor substitution is successful.
     */
    public static function sustituirMonitor($dni_monitor_sustituto, $dia_semana, $hora_inicio) {

            $clases = Clase::getHorario_gym();
            $id_clase = $dia_semana . "-" . $hora_inicio;
        
            if (isset($clases[$id_clase])) {


                $dni_monitor_sustituido = $clases[$id_clase]->dni_monitor;
        
                if ($dni_monitor_sustituto === $dni_monitor_sustituido) {

                    throw new datosIncorrectos("Excepción personalizada: El monitor seleccionado es el mismo que el actual.");

                } 

                    //cambiamos el monitor en el json clases: 
                    $clases[$id_clase]->setDni_monitor($dni_monitor_sustituto);

                    // Guardar clase en JSON
                    $clases[$id_clase]->guardarClaseEnJSON();

                    // Actualizar datos de los monitores afectados
                    Monitor::actualizarDatosMonitores($dni_monitor_sustituto, $dni_monitor_sustituido, $clases[$id_clase]);
                

                    return true; // Sustitución exitosa

            } else {

                throw new datosIncorrectos("Excepción personalizada: el día y hora seleccionado no tiene ninguna actividad asignada, no hay ningun monitor para sustituir");

            }
    }
    

    /**
     * The function calculates the end time of a class based on the given start time, with each class
     * lasting for two hours.
     * 
     * @param hora_inicio The `hora_inicio` parameter in the `horaFinalClase` function represents the
     * starting time of a class. It is expected to be a string in the format "HH:MM" where HH is the
     * hour in 24-hour format. The function extracts the hour part from this parameter and calculates
     * 
     * @return The function `horaFinalClase` takes a starting time as input, extracts the hour part,
     * adds a constant duration of two hours to it, and returns the calculated end time in the format
     * "HH:00".
     */
    private function horaFinalClase($hora_inicio) {
        $hora_inicio = substr($hora_inicio, 0, 2);
        $hora_fin = strval(intval($hora_inicio) + self::DURACION_CLASE);  //Cada clase durará dos horas

        return "$hora_fin:00";
    }


    
  /**
   * The function `Clases_filtradas` filters classes based on a specified property and value, returning
   * an ordered schedule if any classes match the filter criteria.
   * 
   * @param propiedad_filtrada La variable `` en la función `Clases_filtradas` se
   * refiere a la propiedad por la cual se desea filtrar las clases. Por ejemplo, si se quiere filtrar
   * las clases por el tipo de clase (como "Yoga", "Pilates
   * @param valor_filtrado The `valor_filtrado` parameter is the value that the user wants to filter
   * the classes by. It is used to filter the classes based on a specific property value. For example,
   * if the user wants to filter classes by the instructor's name, the `valor_filtrado` would be
   * 
   * @return The `Clases_filtradas` function returns either an ordered schedule array based on the
   * filtered classes or an empty array if no classes match the filtering criteria set by the user.
   */
    public static function Clases_filtradas($propiedad_filtrada, $valor_filtrado) {
      

            //Recoge todos los monitores guardados en el Json , crea los objetos, y los devuelve en un array.
            $clases = self::getHorario_gym(); 

            //La excepción salta solamente si no hay clases en el horario, no cuando los filtros no son coincidentes en ninguna clase
            if(empty($clases)) throw new datosIncorrectos('No hay ninguna clase en el horario'); 
            
            //Filtramos las clases que según la propiedad y el valor que ha decidido el usuario:
            $clases_filtradas = array_filter($clases, function($clase) use ($propiedad_filtrada, $valor_filtrado) {
                return $clase->$propiedad_filtrada === $valor_filtrado;
            });

           
             //organizamos el horario antes de retornarlo si hay alguna clase filtrada:
            if(!empty($clases_filtradas))  return $horario_ordenado = self::ordenarHorario($clases_filtradas);


            else return []; //Si ningún filtro coincidia con las insercciones del usuario, enviamos el horario vacio...(las casillas saldrán en rojo todas)

            
    }




       /**
        * The function ordenarHorario organizes a given schedule array by day of the week and start
        * time before returning it.
        * 
        * @param horario The `ordenarHorario` function takes an array `` as input, where each
        * element represents a class schedule object. The function then organizes this schedule by day
        * of the week and start time before returning the sorted schedule in the form of a
        * multidimensional array `
        * 
        * @return The function `ordenarHorario` is returning an organized schedule array
        * `` where the classes are grouped by day of the week and sorted by start
        * time.
        */
        public static function ordenarHorario($horario){

             //organizamos el horario antes de retornarlo
             $horario_ordenado = [];
            foreach ($horario as $id_clase => $obj_Clase) {
                $claseArray = $obj_Clase->toArray();
                $horario_ordenado[$claseArray['dia_semana']][$claseArray['hora_inicio']] = $claseArray;
            }

            return $horario_ordenado; 

        }


      

 

       
  /**
   * The function `eliminarDisciplina` in PHP reads and updates a JSON file containing class
   * information, removes a specific class based on the activity name, and updates monitor data
   * accordingly.
   * 
   * @param nombre_actividad The `eliminarDisciplina` function is designed to remove a discipline from
   * a JSON file containing class information. The function takes the parameter ``,
   * which represents the name of the activity/discipline that needs to be removed.
   * 
   * @return The `eliminarDisciplina` function is returning a boolean value `true` after completing the
   * process of removing a discipline from the JSON file and updating the necessary data related to
   * monitors.
   */
    public static function eliminarDisciplina($nombre_actividad) {
        
          // Leer el contenido actual del archivo JSON
          $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
          $clasesJSON = json_decode($clasesJSON, true);
  

          if(!empty($clasesJSON)){

            $contador_clases_disciplina=0; 
        
                foreach ($clasesJSON as $id_clase => $claseJSON) {
                    
                        if($clasesJSON[$id_clase]['nombre_actividad'] == $nombre_actividad){

                            
                            //Es necesario crear el objeto clase, para poder utilizar el metodo actualizarMonitores: 
                            $monitor_clase_eliminada = $clasesJSON[$id_clase]['dni_monitor'];
                            $dia_semana = $clasesJSON[$id_clase]['dia_semana'];
                            $hora_inicio= $clasesJSON[$id_clase]['hora_inicio'];

                            $clase_eliminada = new Clase($monitor_clase_eliminada, $nombre_actividad, $dia_semana, $hora_inicio); 

                            Monitor::actualizarDatosMonitores(null, $monitor_clase_eliminada, $clase_eliminada); 

                            //Eliminamos la clase del json clases: 
                            unset($clasesJSON[$id_clase]); 

                            $contador_clases_disciplina++; 

                        } 
                    
                }

                if($contador_clases_disciplina == 0)throw new datosIncorrectos("Excepción personalizada: No existe ninguna clase de $nombre_actividad en el horario actualemnte, no se ha eliminado ninguna clase"); 
        
        }else{
            throw new datosIncorrectos('Excepción personalizada: No existe ninguna clase en el horario'); 
        }
  
          // Guardar los datos actualizados en el archivo JSON
          file_put_contents(self::RUTA_JSON_CLASE, json_encode($clasesJSON, JSON_PRETTY_PRINT));

          //Actualizamos la jornada de los monitores afectados: 
        
          return true; 
  
          
    }




    public static function eliminarClase($id_clase) {
        
        // Leer el contenido actual del archivo JSON
        $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
        $clasesJSON = json_decode($clasesJSON, true);


        if(!empty($clasesJSON)){
      

               if(isset($clasesJSON[$id_clase])){

                      
                          //Es necesario crear el objeto clase, para poder utilizar el metodo actualizarMonitores: 
                          $monitor_clase_eliminada = $clasesJSON[$id_clase]['dni_monitor'];
                          $dia_semana = $clasesJSON[$id_clase]['dia_semana'];
                          $hora_inicio= $clasesJSON[$id_clase]['hora_inicio'];
                          $nombre_actividad=$clasesJSON[$id_clase]['nombre_actividad']; 

                          $clase_eliminada = new Clase($monitor_clase_eliminada, $nombre_actividad, $dia_semana, $hora_inicio); 
                        
                           //Actualizamos la jornada de los monitor afectado: 
                          Monitor::actualizarDatosMonitores(null, $monitor_clase_eliminada, $clase_eliminada); 


                            //Eliminamos la clase del json clases: 
                            unset($clasesJSON[$id_clase]); 

            }else{
                    throw new datosIncorrectos('Excepción personalizada: No existe ninguna clase con ese dia de la semana y hora selecionado en el horario del gimnasio'); 
        }

        // Guardar los datos actualizados en el archivo JSON
        file_put_contents(self::RUTA_JSON_CLASE, json_encode($clasesJSON, JSON_PRETTY_PRINT));

       
      
        return true; 

  }else throw new datosIncorrectos('Excepción personalizada: No existe ninguna clase en el horario'); 

}

    
   
   
     
   
   /**
    * This PHP function reads the content of a JSON file containing gym class information and returns
    * an array of class objects.
    * 
    * @return Array array of Clase objects representing the gym schedule is being returned. Each Clase
    * object contains information about a specific class, including the monitor's ID, activity name,
    * day of the week, and start time.
    */
    public static function getHorario_gym() {

    
                // Leer el contenido actual del archivo JSON
                $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
                $clasesJSON= json_decode($clasesJSON, true);

                
                $horario_gym = [];
                foreach ($clasesJSON as $id_clase => $claseJson) {
                    $clase = new Clase(
                        $claseJson['dni_monitor'],
                        $claseJson['nombre_actividad'],
                        $claseJson['dia_semana'],
                        $claseJson['hora_inicio']
                    );
                    $horario_gym[$id_clase] = $clase;
                }

                

                return $horario_gym;

    }
    


    
    //Esta función permite escribir en el JSON los datos de los objetos, y además nos permite acceder a las propiedades de los objetos fuera del
    // script de la clase que lo ejecuta,(a diferencia de get_objects_value()).

    public function toArray() {
        return [
            
            'dni_monitor' => $this->dni_monitor,
            'nombre_actividad' => $this->nombre_actividad,
            'dia_semana' => $this->dia_semana,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin
        ];
    }

       
      
}
?>
