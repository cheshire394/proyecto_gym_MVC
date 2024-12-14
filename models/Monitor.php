<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


final class Monitor extends Trabajador {

    const RUTA_JSON_MONITORES= __DIR__ . '/../data/monitores.json';  
    private $disciplinas = []; 
    private  $clases = []; 




    function __construct(
        
        $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,
        $cuenta_bancaria,$funcion='monitor',$sueldo = 1100, $horas_extra = 0, $jornada=40) {
    
        $this->funcion='monitor'; 
         // clases y disciplinas se rellena cuando se añada o sustituya el monitor de una clase una clase
        $this->clases=[]; 
        $this->disciplinas =[]; 

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


    public static function monitoresJSON()
    {
        // Leer el contenido actual del archivo JSON
        $monitoresJSON = file_get_contents(self::RUTA_JSON_MONITORES);
        $monitores = json_decode($monitoresJSON, true);

        $monitoresObjetos = []; // Inicializar array para almacenar objetos Monitor

        foreach ($monitores as $dni_monitor => $monitor) {
            // Calcular la jornada en función del número de clases
            $jornada = count($monitor['clases']) * Clase::DURACION_CLASE; // Cada clase dura 2 horas

            // Calcular el sueldo dinámicamente
            $sueldo = $jornada * self::EUROS_HORA; // 20 euros por hora

            // Crear el objeto Monitor
            $monitorObj = new Monitor(
                $dni_monitor,
                $monitor['nombre'],
                $monitor['apellidos'],
                $monitor['fecha_nac'],
                $monitor['telefono'],
                $monitor['email'],
                $monitor['cuenta_bancaria'],
                'monitor',
                $sueldo,
                isset($monitor['horas_extra']) ? $monitor['horas_extra'] : 0,
                $jornada
            );

            // Añadir disciplinas y clases al objeto Monitor
            $monitorObj->disciplinas = isset($monitor['disciplinas']) ? $monitor['disciplinas'] : [];
            $monitorObj->clases = isset($monitor['clases']) ? $monitor['clases'] : [];

            // Almacenar el objeto Monitor en el array
            $monitoresObjetos[$dni_monitor] = $monitorObj;
        }

        return $monitoresObjetos;
    }

   

    // Método para convertir el objeto Monitor a un array compatible con la estructura JSON
    public function to_array() {
        $monitorArray = [
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'fecha_nac' => $this->fecha_nac,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'cuenta_bancaria' => $this->cuenta_bancaria,
            'funcion' => $this->funcion,
            'sueldo' => $this->sueldo,
            'horas_extra' => $this->horas_extra,
            'jornada' => $this->jornada,
            'disciplinas' => $this->disciplinas,
            'clases' => $this->clases
        ];
    

        return $monitorArray;
    }

    public static function guardarMonitoresEnJSON($monitoresObj) {
        try {
            $monitoresArray = [];
        
            foreach ($monitoresObj as $dni_monitor => $monitorObj) {
                // Convertir cada objeto a array, con depuración
                $monitorArray = $monitorObj->to_array();
                
                // Verificar que las clases sean arrays 
                if (isset($monitorArray['clases']) && is_array($monitorArray['clases'])) {
                    $monitorArray['clases'] = array_map(function($clase) {
                        return is_object($clase) ? $clase->toArray() : $clase;
                    }, $monitorArray['clases']);
                }
                
                $monitoresArray[$dni_monitor] = $monitorArray;
            }
        
            // Guardar con más opciones de JSON
            $json = json_encode($monitoresArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            
            // Verificaciones adicionales
            if ($json === false) {
                error_log("Error en la codificación JSON");
                return false;
            }
            
            $resultado = file_put_contents(self::RUTA_JSON_MONITORES, $json);
            
            if ($resultado === false) {
                error_log("Error al escribir en el archivo JSON de monitores");
            }
            
            return $resultado !== false;
        } catch (Exception $e) {
            error_log("Excepción al guardar monitores: " . $e->getMessage());
            return false;
        }
    }


    public static function actualizarDatosMonitores($new_monitor, $old_monitor, $monitoresObj){

    }


    

    
}

?>