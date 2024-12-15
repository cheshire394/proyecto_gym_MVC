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


    public static function actualizarDatosMonitores($dni_monitor, $monitor_clase_sustituida,$clase_creada){

       // Obtener monitores
       $monitoresObj = Monitor::monitoresJSON();

       $id_clase = $clase_creada->__get('id_clase'); 
       $nombre_actividad= $clase_creada->__get('nombre_actividad'); 
       

       if(!empty($monitor_clase_sustituida)){

           if ($dni_monitor !== $monitor_clase_sustituida){ 
               //actualizar jornada solo si no es el mismo monitor que ejercia la clase sustituida
               $jornada_monitor= $monitoresObj[$monitor_clase_sustituida]->__get('jornada'); 
               $actualizar_jornada= $jornada_monitor - Clase::DURACION_CLASE; 
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
           $actualizar_jornada = $jornada_monitor + Clase::DURACION_CLASE;
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
   

    

    
}

?>