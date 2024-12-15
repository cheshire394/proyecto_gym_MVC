<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    private static $horario_gym = [];
    

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


    public function setDni_monitor($dni_monitor)
    {
            $this->dni_monitor = $dni_monitor;

            return $this;
    }



    public static function addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio) {
        
        // Crear la clase
        $clase_creada = new Clase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio);
    
        // Guardar clase en JSON
        $monitor_clase_sustituida = $clase_creada->guardarClaseEnJSON();

        //Actualiazar los datos de los monitores afectados con el cambio de horario
        $actualizado= Monitor::actualizarDatosMonitores($dni_monitor, $monitor_clase_sustituida, $clase_creada);

        
       return $actualizado; 
    
    }
     
    
   
   


    private function guardarClaseEnJSON() {
      
        // Leer el contenido actual del archivo JSON
        $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
        $clasesJSON = json_decode($clasesJSON, true);

        //Si existe otra clase con otro id_clase igual al que estamos insertando, eliminamos:
        $monitor_clase_sustituida=null; 
        foreach ($clasesJSON as $id_clase => $claseJSON) {
            if ($id_clase == $this->id_clase) {
                $monitor_clase_sustituida = $claseJSON['dni_monitor']; // Obtener el DNI del monitor de la clase existente
                unset($clasesJSON[$id_clase]); // Eliminar la clase existente
                break;
            }
        }

        // Agregar la nueva clase a los datos existentes en formato JSON
        $clasesJSON[$this->id_clase] = $this->toArray(); 

        // Guardar los datos actualizados en el archivo JSON
        file_put_contents(self::RUTA_JSON_CLASE, json_encode($clasesJSON, JSON_PRETTY_PRINT));


        return $monitor_clase_sustituida; 
    }


    

    public static function sustituirMonitor($dni_monitor_sustituto, $dia_semana, $hora_inicio) {
        $clases = Clase::getHorario_gym();
        $id_clase = $dia_semana . "-" . $hora_inicio;
    
        if (isset($clases[$id_clase])) {
            $dni_monitor_sustituido = $clases[$id_clase]->__get('dni_monitor');
    
            if ($dni_monitor_sustituto === $dni_monitor_sustituido) {
                throw new Exception("Excepción: El monitor seleccionado es el mismo que el actual.");
            } else {
                $clases[$id_clase]->setDni_monitor($dni_monitor_sustituto);
                // Guardar clase en JSON
                $clases[$id_clase]->guardarClaseEnJSON();
                // Actualizar datos de los monitores afectados
                Monitor::actualizarDatosMonitores($dni_monitor_sustituto, $dni_monitor_sustituido, $clases[$id_clase]);
            }
            return true; // Sustitución exitosa
        } else {
            throw new Exception("La clase indicada no está incluida en el horario.");
        }
    }
    

    private function horaFinalClase($hora_inicio) {
        $hora_inicio = substr($hora_inicio, 0, 2);
        $hora_fin = strval(intval($hora_inicio) + self::DURACION_CLASE);  //Cada clase durará dos horas

        return "$hora_fin:00";
    }

    public static function Clases_filtradas($propiedad_filtrada, $valor_filtrado) {
        try {
            
            //Recoge todos los monitores guardados en el Json , crea los objetos, y los en un array.
            $clases = self::getHorario_gym(); 
            
            //el primer parametro retorna rescata los valores del json, crea las clases y las retorna
            $clases_filtradas = array_filter($clases, function($clase) use ($propiedad_filtrada, $valor_filtrado) {
                return $clase->$propiedad_filtrada === $valor_filtrado;
            });

            return $clases_filtradas;
            
        } catch (datosIncorrectos $e) {
            return $e->datosIncorrectos();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

 

    //NO CONSIGO QUE FUNCIONE (SOSPECHO QUE EL FALLO VIENE DESDE GETHORARIO_GYM YA QUE HE DESCUBIERTO UNA ANIDACION EXTRAÑA)

    public static function eliminarDisciplina($nombre_actividad) {
        
        $clases = self::getHorario_gym();
    
        if (empty($clases)) {
            throw new Exception("No hay clases disponibles para eliminar.");
        }
    
       
        $clases_actualizadas = array_filter($clases, function($clase) use ($nombre_actividad) {
            return $clase->__get('nombre_actividad') !== $nombre_actividad;
        });
    
       
        if (count($clases_actualizadas) === count($clases)) {
            throw new Exception("No se encontraron clases para la disciplina: $nombre_actividad.");
        }
    
        // Guardar el nuevo horario en el JSON
        $resultado = file_put_contents(self::RUTA_JSON_CLASE, json_encode($clases_actualizadas, JSON_PRETTY_PRINT));
    
        if ($resultado === false) {
            throw new Exception("Error al guardar los cambios en el archivo JSON.");
        }
    
        return true; // Éxito
    }
    
    public static function getHorario_gym() {
        try {
            // Si ya existe un horario cargado, retornar directamente
            if (!empty(self::$horario_gym)) {
                return self::$horario_gym;
            }
    
            // Leer el contenido actual del archivo JSON
            $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
            $clasesData = json_decode($clasesJSON, true);
    
            // Inicializar el array horario_gym (evitar duplicados o reinicios incorrectos)
            self::$horario_gym = [];
    
            foreach ($clasesData as $claseData) {
                $clase = new Clase(
                    $claseData['dni_monitor'],
                    $claseData['nombre_actividad'],
                    $claseData['dia_semana'],
                    $claseData['hora_inicio']
                );
                self::$horario_gym[] = $clase;
            }
    
            return self::$horario_gym;
        } catch (datosIncorrectos $e) {
            return $e->datosIncorrectos();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    
        
    
        
    
        
    

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
