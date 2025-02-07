<?php


final class Monitor extends Persona {

  

    const EUROS_HORA=30;
    
    private $jornada;
    private $sueldo;
    private $cuenta_bancaria;
    private $funcion;
    private $horas_extra;

    function __construct(){

        //contructor vacio para fetchObject()
    }
        
        

    public function __set($name, $value)
    {
    

        // Verifica si la propiedad existe y asigna el valor
        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            // Lanza una excepción si la propiedad no existe
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase monitor");
        }
    }

    public function __get($name)
    {
        // Verifica si la propiedad existe en la propia clase Monitor
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        // Si no está en Monitor, revisar en Persona
        elseif (property_exists(get_parent_class($this), $name)) {
            return $this->$name;
        } else {
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Monitor ni en Persona.");
        }
    }
    


    //funcion para obtener todos los datos de los monitores y verlo en una tarbla (crea los objetos)
    public static function verMonitores(){

        include  __DIR__ . '/../data/conexionBBDD.php'; 
        
        $sql = "SELECT * FROM MONITORES"; 

       if(!$stmt = $conn->prepare($sql)) throw new PDOException('Error al preparar la consuta que obtiene todos los monitores en verMonitores'); 
       if(!$stmt->execute()) throw new PDOException("Excepción PDO: error al ejecutar la consulta en verMonitores"); 

        $monitores=[]; 
        while($monitor = $stmt->fetchObject('Monitor')){
            
            $monitores[]=$monitor; 

        }

        return $monitores;


    }


    public static function get_monitores(){


        // Consulta para obtener los DNI y nombres de los monitores para que los formularios aparezcan
        require_once  __DIR__ . '/../data/conexionBBDD.php'; 
        $sql = "SELECT dni, nombre FROM MONITORES";
        $stmt = $conn->prepare($sql);

        if(!$stmt->execute()) throw new PDOException("Excepción PDO: error al ejecutar la consulta en get_monitores"); 
        
        $monitores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $monitores; 

    

    }

  

    public static function actualizarMonitorNuevo($conn, $dni_new_monitor) {
        try {
            $query = "SELECT jornada, sueldo FROM MONITORES WHERE dni = :dni_new_monitor";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':dni_new_monitor', $dni_new_monitor, PDO::PARAM_STR);
            $stmt->execute();
            $new_monitor = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$new_monitor) {
                throw new PDOException("Monitor con DNI $dni_new_monitor no encontrado");
            }
    
            $nueva_jornada = $new_monitor['jornada'] + Clase::DURACION_CLASE;
            $nuevo_sueldo = $nueva_jornada * Monitor::EUROS_HORA;
    
            $query = "UPDATE MONITORES SET jornada = :nueva_jornada, sueldo = :nuevo_sueldo WHERE dni = :dni_new_monitor";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nueva_jornada', $nueva_jornada, PDO::PARAM_INT);
            $stmt->bindParam(':nuevo_sueldo', $nuevo_sueldo, PDO::PARAM_STR);
            $stmt->bindParam(':dni_new_monitor', $dni_new_monitor, PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                throw new PDOException("Error al actualizar el monitor con DNI $dni_new_monitor");
            }
    
            return true; // Todo funcionó correctamente
    
        } catch (PDOException $e) {
            echo "Error en actualizarMonitorNuevo: " . $e->getMessage();
            return false;
        }
    }
    


    public static function actualizarMonitorAntiguo($conn, $dni_old_monitor){

        try {
            $query = "SELECT jornada, sueldo FROM MONITORES WHERE dni = :dni_old_monitor";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':dni_old_monitor', $dni_old_monitor, PDO::PARAM_STR);
            $stmt->execute();
            $new_monitor = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$new_monitor) {
                throw new PDOException("Monitor con DNI $dni_old_monitor no encontrado");
            }
    
            $nueva_jornada = $new_monitor['jornada'] - Clase::DURACION_CLASE;
            $nuevo_sueldo = $nueva_jornada * Monitor::EUROS_HORA;
    
            $query = "UPDATE MONITORES SET jornada = :nueva_jornada, sueldo = :nuevo_sueldo WHERE dni = :dni_old_monitor";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nueva_jornada', $nueva_jornada, PDO::PARAM_INT);
            $stmt->bindParam(':nuevo_sueldo', $nuevo_sueldo, PDO::PARAM_STR);
            $stmt->bindParam(':dni_old_monitor', $dni_old_monitor, PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                throw new PDOException("Error al actualizar el monitor con DNI $dni_old_monitor");
            }
    
            return true; // Todo funcionó correctamente
    
        } catch (PDOException $e) {
            echo "Error en actualizarMonitorNuevo: " . $e->getMessage();
            return false;
        }



    }



}

?>