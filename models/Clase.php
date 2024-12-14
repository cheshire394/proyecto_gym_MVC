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

        self::$horario_gym[$this->id_clase] = $this;
    }



    public function __get($name) {
        if (property_exists($this, $name)) return $this->$name;
        else throw new Exception("ERROR EN EL GETTER DE LA CLASE 'CLASES': SE HA TRATADO DE OBTENER UNA PROPIEDAD QUE NO EXISTE");
    }


    public static function addClase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio) {
        
        // Crear la clase
        $clase_creada = new Clase($dni_monitor, $nombre_actividad, $dia_semana, $hora_inicio);
    
        // Guardar clase en JSON
        $monitor_clase_sustituida = $clase_creada->guardarClaseEnJSON();
       
    
        // Obtener monitores
        $monitoresObj = Monitor::monitoresJSON();
        $id_clase = $dia_semana."-".$hora_inicio;


        if(!empty($monitor_clase_sustituida)){

            if ($dni_monitor !== $monitor_clase_sustituida){ 
                //actualizar jornada solo si no es el mismo monitor que ejercia la clase sustituida
                $jornada_monitor= $monitoresObj[$monitor_clase_sustituida]->__get('jornada'); 
                $actualizar_jornada= $jornada_monitor - self::DURACION_CLASE; 
                $monitoresObj[$monitor_clase_sustituida]->__set('jornada', $actualizar_jornada); 
            
            }
            // eliminar la clase del objeto  porque ya no la va impartir esa clase (ha sido eliminada en guardarClaseJSON())
            $monitor_clases =$monitoresObj[$monitor_clase_sustituida]->__get('clases'); 
            unset($monitor_clases[$id_clase]); 
            // Actualizar las clases del monitor en el objeto
            $monitoresObj[$monitor_clase_sustituida]->__set('clases', $monitor_clases);
        }
        
    
        
        if ($dni_monitor !== $monitor_clase_sustituida) {
          
            // Actualizar jornada
            $jornada_monitor = $monitoresObj[$dni_monitor]->__get('jornada');
            $actualizar_jornada = $jornada_monitor + self::DURACION_CLASE;
            $monitoresObj[$dni_monitor]->__set('jornada', $actualizar_jornada);
           

        }

        // Actualizar clases del monitor
        $clases_monitor = $monitoresObj[$dni_monitor]->__get('clases');
        $clases_monitor[$id_clase] = $clase_creada;
        $monitoresObj[$dni_monitor]->__set('clases', $clases_monitor);
        

         // Actualizar disciplinas
         $disciplinas_monitor = $monitoresObj[$dni_monitor]->__get('disciplinas');
         if (!in_array($nombre_actividad, $disciplinas_monitor)) {
             $disciplinas_monitor[] = $nombre_actividad;
             $monitoresObj[$dni_monitor]->__set('disciplinas', $disciplinas_monitor);
            
         }

            // Intentar guardar y verificar
            $guardado = Monitor::guardarMonitoresEnJSON($monitoresObj);
           
    
            return $guardado; 
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



    public static function sustituirMonitor($dni_monitor_sustituto, $dia, $hora) {
        

        
            return true; 
        
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

 

    public static function eliminarDisciplina($nombre_actividad) {
        $clases_filtradas = self::Clases_filtradas('nombre_actividad', $nombre_actividad);
        $clases = self::$horario_gym;

        //Obtenemos todos los monitores, para restar sus jornadas de la disciplina que impartían (si es necesario):
        $monitores = Trabajador::getTrabajadoresMonitores();

        foreach ($clases as $id_clase => $obj_clase) {
            if (array_key_exists($id_clase, $clases_filtradas)) {
                // Reducimos la jornada laboral del monitor que impartía esta disciplina para mantener los datos actualizados:
                $dni_monitor_clase_eliminada = $obj_clase->__get('dni_monitor');

                if (isset($monitores[$dni_monitor_clase_eliminada])) {
                    $monitor = $monitores[$dni_monitor_clase_eliminada]; // Obtenemos el objeto monitor
                    $jornada_monitor = $monitor->__get('jornada'); // Obtenemos su jornada
                    $jornada_monitor -= self::DURACION_CLASE; // Restamos dos horas, de la clase que estamos eliminando
                    $monitor->__set('jornada', $jornada_monitor); // Actualizamos los cambios
                }

                unset(self::$horario_gym[$id_clase]); // Eliminamos el objeto del array que almacena todas las clases
                unset($obj_Clase); // Eliminamos el objeto en sí.
            }
        }
    }

    public static function getHorario_gym() {
        try {
            

            // Leer el contenido actual del archivo JSON
            $clasesJSON = file_get_contents(self::RUTA_JSON_CLASE);
            $clasesJSON = json_decode($clasesJSON, true);

            foreach ($clasesJSON as $i => $claseJSON) {
                new Clase(
                    //id_clase y hora_fin se crean en el costructor automáricamente
                    $claseJSON['dni_monitor'],
                    $claseJSON['nombre_actividad'],
                    $claseJSON['dia_semana'],
                    $claseJSON['hora_inicio']
                );
            }

            // Se crea el array con todas las clases cuando llamamos al constructor.
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
