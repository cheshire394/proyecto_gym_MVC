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
            foreach ($monitores as &$monitor) {
                if ($monitor['dni'] == $dni_monitor) {
                    // Si la disciplina no existe, añadirla
                    if (!in_array($nombre_actividad, $monitor['disciplinas'])) {
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
        foreach ($clasesJSON as $i => $claseJSON) {
            if ($claseJSON['id_clase'] == $this->id_clase) {
                unset($clasesJSON[$i]);
                break;
            }
        }

        // Agregar la nueva clase a los datos existentes
        $clasesJSON[] = [
            'id_clase' => $this->dia_semana . "-" . $this->hora_inicio,
            'dni_monitor' => $this->dni_monitor,
            'nombre_actividad' => $this->nombre_actividad,
            'dia_semana' => $this->dia_semana,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->horaFinalClase($this->hora_inicio)
        ];

        // Guardar los datos actualizados en el archivo JSON
        file_put_contents($rutaJSON, json_encode($clasesJSON, JSON_PRETTY_PRINT));
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
