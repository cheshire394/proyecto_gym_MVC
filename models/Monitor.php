<?php
require_once __DIR__ . '/Recepcionista.php';

final class Monitor extends Recepcionista {

  

    const EUROS_HORA=30;
    private $disciplinas = []; 
    private  $clases = []; 


    function __construct(
        
        $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,
        $cuenta_bancaria,$funcion='monitor',$sueldo = 1100, $horas_extra = 0, $jornada=40) {
    
        $this->funcion='monitor'; 
         // clases y disciplinas se añade/elimina  cuando se manipula las clases (añadir, sustituitMonitor, eliminar disciplina...).
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
    


    public static function actualizarMonitorAntiguo(){

        include  __DIR__ . '/../data/conexionBBDD.php'; 
        // Actualizar condiciones del antiguo monitor
        $query = "SELECT jornada, sueldo FROM MONITORES WHERE dni = :dni_old_monitor";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':dni_old_monitor', $dni_old_monitor);
        $stmt->execute();
        $old_monitor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($old_monitor) {
            $nueva_jornada = $old_monitor['jornada'] - Clase::DURACION_CLASE;
            $nuevo_sueldo = $nueva_jornada * Monitor::EUROS_HORA;

            $query = "UPDATE MONITORES SET jornada = :nueva_jornada, sueldo = :nuevo_sueldo WHERE dni = :dni_old_monitor";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nueva_jornada', $nueva_jornada, PDO::PARAM_INT);
            $stmt->bindParam(':nuevo_sueldo', $nuevo_sueldo);
            $stmt->bindParam(':dni_old_monitor', $dni_old_monitor);
            $stmt->execute();
        }



    }



}

?>