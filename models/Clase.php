<?php
  
final class Clase {

    

    public const DURACION_CLASE = 2;
    
    private $dni_monitor;
    private $nombre_actividad;
    private $dia_semana;
    private $hora_inicio;
    private $hora_fin;
    private $id_clase;
    

    function __construct() {

       
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




    public static function addClase($id_clase, $dni_monitor, $nombre_actividad) {
        try {
            include __DIR__ . '/../data/conexionBBDD.php';
    
            // Iniciar transacción para asegurar consistencia 
            $conn->beginTransaction();
    
            // Obtener el día de la semana y la hora de inicio a partir de su ID
            $datos_faltantes = explode('-', $id_clase);
            $dia_semana = $datos_faltantes[0];
            $hora_inicio = $datos_faltantes[1];
    
            // Obtener la hora de finalización de la clase
            $clase = new Clase();
            $hora_fin = $clase->horaFinalClase($hora_inicio);
    
            // Insertar en la base de datos
            $sql = "INSERT INTO CLASES(id_clase, dni_monitor, nombre_actividad, dia_semana, hora_inicio, hora_fin) 
                    VALUES (:id_clase, :dni_monitor, :nombre_actividad, :dia_semana, :hora_inicio, :hora_fin)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_clase', $id_clase, PDO::PARAM_STR);
            $stmt->bindParam(':dni_monitor', $dni_monitor, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_actividad', $nombre_actividad, PDO::PARAM_STR);
            $stmt->bindParam(':dia_semana', $dia_semana, PDO::PARAM_STR);
            $stmt->bindParam(':hora_inicio', $hora_inicio, PDO::PARAM_STR);
            $stmt->bindParam(':hora_fin', $hora_fin, PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                throw new PDOException('Excepción PDO al ejecutar la inserción de la clase en la BBDD');
            }
    
            // Llamar a actualizarMonitorNuevo() y verificar si se ejecuta correctamente
            $actualizado_monitor = Monitor::actualizarMonitorNuevo($conn, $dni_monitor);
    
            if (!$actualizado_monitor) {
                throw new PDOException("Error al actualizar el monitor con DNI $dni_monitor");
            }
    
            // Confirmar la transacción
            $conn->commit();
    
            // Redirigir a verClases.php con mensaje de éxito
            $msg = "Clase registrada correctamente";
            header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=" . urlencode($msg));
            exit;
    
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
    
            // Manejo de errores específicos
            if ($e->getCode() == 23000) {
                $msg = "Excepción PDO: ya existe una clase asignada para el ID $id_clase";
            } else {
                $msg = $e->getMessage();
            }
    
        } 
    
        // Redirigir de nuevo al formulario de añadir clase con mensaje de error
        header("Location: /proyecto_gym_MVC/view/clases/addClase.php?msg=" . urlencode($msg));
        exit;
    }
    
     
    //fUNCION PARA CALCULAR LA HORA FINAL AUTOMÁTICAMENTE
    private function horaFinalClase($hora_inicio) {
        $hora_inicio = substr($hora_inicio, 0, 2);
        $hora_fin = strval(intval($hora_inicio) + self::DURACION_CLASE);  //Cada clase durará dos horas

        return "$hora_fin:00";
    }


    public static function horario() {
        include  __DIR__ . '/../data/conexionBBDD.php'; 
    
        // Consulta SQL para obtener todas las clases disponibles
        $sql = "SELECT dni_monitor, nombre_actividad, dia_semana, hora_inicio FROM CLASES";
    
        // Usamos prepare y execute en lugar de query
        $stmt = $conn->prepare($sql);

        if(! $stmt->execute()) throw new PDOException('Excepción PDO al ejecutar la consulta para obtener el horario');
       
    
        $clases = []; 
        while ($clase = $stmt->fetchObject('Clase')) { 
            $clases[] = $clase;  
        }
    
        // Organizar las clases en un array asociativo para fácil acceso
        $horario = [];
        foreach ($clases as $clase) {
            $dia = $clase->dia_semana;  
            $hora = $clase->hora_inicio;
            $horario[$dia][$hora] = $clase;
        }
    
        return $horario;  
    }


    public static function horas_disponibles(){

        

        include  __DIR__ . '/../data/conexionBBDD.php'; 
    
        // Generar todos los id_clase posibles
        $dias_semana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
        $horas = ['10:00', '12:00', '16:00', '18:00'];
    
        $todos_id_clase = [];
        foreach ($dias_semana as $dia) {
            foreach ($horas as $hora) {
                $todos_id_clase[] = "$dia-$hora";
            }
        }
    
        // Obtener los id_clase ya registrados en la base de datos
        $sql = "SELECT id_clase FROM CLASES";
       

        if (!$stmt= $conn->prepare($sql)) {
            throw new PDOException('Excepción PDO al preparar la consulta de las clases disponibles');
        }
    
        if (!$stmt->execute()) {
            throw new PDOException('Excepción PDO al ejecutar la consulta de las clases disponibles');
        }
    
        $clases_registradas = $stmt->fetchAll(PDO::FETCH_COLUMN); 
    
        // diff obtiene las clases que no están registradas, es decir los elementos diferentes entre dos arrays
        $horas_disponibles = array_diff($todos_id_clase, $clases_registradas);
    
        return $horas_disponibles; 
    }



    public static function horas_ocupadas(){

        
        include  __DIR__ . '/../data/conexionBBDD.php'; 
    
    
        // Obtener los id_clase ya registrados en la base de datos, recibimos los datos ordenados con un criterio especifico 
        //para que en el formulario aparezcan las clases ocupadas por orden de la semana y no alfabético.
        $sql = "SELECT id_clase FROM CLASES ORDER BY 
        FIELD(SUBSTRING_INDEX(id_clase, '-', 1), 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'),
        SUBSTRING_INDEX(id_clase, '-', -1) ASC";


        if (!$stmt= $conn->prepare($sql)) {
            throw new PDOException('Excepción PDO al preparar la consulta de las clases disponibles');
        }
    
        if (!$stmt->execute()) {
            throw new PDOException('Excepción PDO al ejecutar la consulta de las clases disponibles');
        }
    
        $horas_ocupadas = $stmt->fetchAll(PDO::FETCH_COLUMN); 
    
       
        return $horas_ocupadas; 
    }

    
    

   
    public static function sustituirMonitor($dni_new_monitor, $id_clase) {

        include  __DIR__ . '/../data/conexionBBDD.php';
    
        try {
            $conn->beginTransaction(); // Iniciar transacción para asegurar consistencia
    
            // PASO 1) Obtener el dni del antiguo monitor (el monitor que estaba impartiendo esa clase)
            $query = "SELECT dni_monitor FROM CLASES WHERE id_clase = :id_clase";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_clase', $id_clase);
            $stmt->execute();
            $dni_old_monitor = $stmt->fetchColumn(); 


            if (!$dni_old_monitor) {
                throw new PDOException("Excepción PDO: No se encontró el antiguo monitor que imapartía la clase $id_clase");

            
            }
            
            //Evitamos actualizaciones ineccesarias sobre la BBDD
            if($dni_old_monitor == $dni_new_monitor){
                throw new PDOException('El monitor seleccionado ya imparte esa clase');
            }


            //Si se ha encontrado el antiguo monitor, además no es el mismo que el nuevo monitor, entonces actualizamos los datos en la BBDD
    
           $actualizado_monitor_old = Monitor::actualizarMonitorAntiguo($conn, $dni_old_monitor);

           $actualizado_monitor_new = Monitor::actualizarMonitorNuevo($conn, $dni_new_monitor);

            //Si existe algun error hacemos rollback de los cambios ejecutados
           if(!$actualizado_monitor_old || !$actualizado_monitor_new){

                throw new PDOException("Error al actualizar el monitor con DNI $dni_monitor");
           }
    
           
            //Si los monitores se han actualizado correctamente,  Actualizamos el monitor en la tabla CLASES
            $query = "UPDATE CLASES SET dni_monitor = :dni_new_monitor WHERE id_clase = :id_clase";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':dni_new_monitor', $dni_new_monitor);
            $stmt->bindParam(':id_clase', $id_clase);
            $stmt->execute();
    
            $conn->commit(); // Confirmamos todos los  cambios

            // Redirigir a verClases.php con mensaje de éxito
            $msg = "monitor sustituido correctamente";
            header("Location: /proyecto_gym_MVC/view/clases/verClases.php?msg=" . urlencode($msg));
            exit;
    

        } catch (PDOException $e) {

            $conn->rollBack(); // Revertir cambios en caso de error

               // Redirigir de nuevo al formulario sustituir monitor con mensaje de error
               $msg = $e->getMessage(); 
                header("Location: /proyecto_gym_MVC/view/clases/sustituirMonitor.php?msg=" . urlencode($msg));
                exit;
            
        }
    }
    

   
  
 


    
    public static function eliminarDisciplina($nombre_actividad) {
        
          
          
    }


    public static function eliminarClase($id_clase) {
        
        
      
}


}
?>
