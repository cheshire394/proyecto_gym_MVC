<?php
  
final class Clase {

    

    public const DURACION_CLASE = 2;
    
    private $dni_monitor;
    private $nombre_actividad;
    private $dia_semana;
    private $hora_inicio;
    private $hora_fin;
    private $id_clase;
    private $socios_inscritos = []; 

    function __construct() {

       
    }



    public function __get($name) {
        if (property_exists($this, $name)) return $this->$name;
        else throw new Exception("ERROR EN EL GETTER DE LA CLASE 'CLASES': SE HA TRATADO DE OBTENER UNA PROPIEDAD QUE NO EXISTE");
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
    
           return true; 
    
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
    
            // Manejo de errores con mensajes específicos segun el tipo de PDO exception: si el metodo clases disponibles funciona correctamente
            // la violación de la primary key nunca deberia suceder, ya que desde el formulario solo se puede añadir clases en huecos libres.
            if ($e->getCode() == 23000) {

                return "Excepción PDO: ya existe una clase asignada para el ID $id_clase";
                
            } else {
               return $msg = $e->getMessage();
            }
    
        } 
    
      
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


    public static function get_disciplinas(){

        include  __DIR__ . '/../data/conexionBBDD.php'; 
    
        // Consulta SQL para obtener todas las clases disponibles (una única vez)
        $sql = "SELECT DISTINCT nombre_actividad FROM CLASES ORDER BY nombre_actividad ASC";


       if(!$stmt = $conn->prepare($sql)) throw new PDOException('Excepción PDO al preparar la consulta para el nombre de las disciplinas en get_disciplinas');

        if(!$stmt->execute()) throw new PDOException('Excepción PDO al ejecutar la consulta para el nombre de las disciplinas en get_disciplinas');
       
    
        $disciplinas = []; 
        while ($disciplina = $stmt->fetchColumn()) { 
            $disciplinas[] = $disciplina;  
        }
    
        return $disciplinas;  

    }

    
    

   
    public static function sustituirMonitor($dni_new_monitor, $id_clase) {
        include __DIR__ . '/../data/conexionBBDD.php';
    
        try {
            $conn->beginTransaction(); 
    
            // Obtener el dni del antiguo monitor
            $query = "SELECT dni_monitor FROM CLASES WHERE id_clase = :id_clase";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':id_clase', $id_clase);
            $stmt->execute();
            $dni_old_monitor = $stmt->fetchColumn(); 
    
            if (!$dni_old_monitor) {
                throw new PDOException("No se encontró el monitor para la clase $id_clase");
            }
    
            // Evitar actualización innecesaria
            if ($dni_old_monitor == $dni_new_monitor) {
                throw new  Exception("El monitor seleccionado ya imparte esta clase");
            }
    
            // Actualizar monitores
            $actualizado_monitor_old = Monitor::actualizarMonitorAntiguo($conn, $dni_old_monitor);
            $actualizado_monitor_new = Monitor::actualizarMonitorNuevo($conn, $dni_new_monitor);
    
            if (!$actualizado_monitor_old || !$actualizado_monitor_new) {
                throw new PDOException("Error al actualizar el monitor.");
            }
    
            // Actualizar el monitor en la tabla CLASES
            $query = "UPDATE CLASES SET dni_monitor = :dni_new_monitor WHERE id_clase = :id_clase";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':dni_new_monitor', $dni_new_monitor);
            $stmt->bindParam(':id_clase', $id_clase);
            $stmt->execute();
    
            $conn->commit(); 
            return true; 
    
        } catch (PDOException $e) {
            $conn->rollBack(); 
            error_log("Error en sustituir Monitor: " . $e->getMessage()); // Registrar error
            return $e->getMessage(); // Devolver el mensaje de error específico
        }
    }
    
    

    public static function eliminarClase($id_clase) {
        include __DIR__ . '/../data/conexionBBDD.php';
    
        try {
            $conn->beginTransaction(); // Iniciar transacción
    
            // Obtener el DNI del monitor antes de eliminar la clase
            $sql = "SELECT dni_monitor FROM CLASES WHERE id_clase = :id_clase";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_clase', $id_clase, PDO::PARAM_STR);
            $stmt->execute();
            //Solo devuelve un único valor de la primera fila del resultado
            $dni_monitor = $stmt->fetchColumn();
    
            if (!$dni_monitor) {
                throw new PDOException("No se encontró la clase con ID $id_clase.");
            }
    
            // Actualizar las condiciones del monitor antes de eliminar la clase
            $actualizado_monitor_old = Monitor::actualizarMonitorAntiguo($conn, $dni_monitor);
            if (!$actualizado_monitor_old) {
                throw new PDOException("Error al actualizar las condiciones del monitor.");
            }
    
            // Eliminar la clase
            $sql = "DELETE FROM CLASES WHERE id_clase = :id_clase";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_clase', $id_clase, PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                throw new PDOException("Error al eliminar la clase con ID $id_clase.");
            }
    
            $conn->commit(); // Confirmar la transacción
            return true; 
        } catch (PDOException $e) {
            $conn->rollBack(); // Revertir cambios en caso de error
            error_log("Error en eliminarClase: " . $e->getMessage()); // Registrar error
            return  $e->getMessage(); // Devolver el mensaje de error específico
        }
    }
    
  
    
    public static function eliminarDisciplina($nombre_actividad) {
        include __DIR__ . '/../data/conexionBBDD.php';
    
        try {
            $conn->beginTransaction(); // Iniciar la transacción
    
            // Obtener los DNI de los monitores que impartían la disciplina
            $sql = "SELECT dni_monitor FROM CLASES WHERE nombre_actividad = :nombre_actividad";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre_actividad', $nombre_actividad, PDO::PARAM_STR);
            $stmt->execute();

            //Recupera todas las filas de la consulta, pero solo los valores de una columna específica (dni_monitor)
            $dnis_monitores = $stmt->fetchAll(PDO::FETCH_COLUMN); 
    
            if (empty($dnis_monitores)) {
                throw new PDOException("No se encontraron clases para la disciplina '$nombre_actividad'.");
            }
    
            // Contar cuántas veces aparece cada monitor para saber cuántas clases impartía:

            /*$dnis_monitores nos devuelve un array simple de tipo string con los dnis, si un dni aparece dos veces, en el array aparece dos veces
            como queremos contabilizar el numero de veces que aparece cada dni, para actualizar sus datos tantas veces como aparezca hemos utilizado 
            esta función que nos devuelve una array asociativo en la que la key es el dni de un monitor concreto y el valor es el numero de veces que aparece en consulta

            por ejemplo: 

            array = {

                647465657T => 2,
                646446466M => 4
            
            }
            
            */
            $conteo_monitores = array_count_values($dnis_monitores);
    
            // Actualizar las condiciones de los monitores afectados
            foreach ($conteo_monitores as $dni_monitor => $cantidad_clases) {
                for ($i = 0; $i < $cantidad_clases; $i++) {
                    $actualizado = Monitor::actualizarMonitorAntiguo($conn, $dni_monitor);
                    if (!$actualizado) {
                        throw new PDOException("Error al actualizar las condiciones del monitor con DNI $dni_monitor.");
                    }
                }
            }
    
            // Eliminar las clases de la disciplina
            $sql = "DELETE FROM CLASES WHERE nombre_actividad = :nombre_actividad";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':nombre_actividad', $nombre_actividad, PDO::PARAM_STR);
    
            if (!$stmt->execute()) {
                throw new PDOException("Error al eliminar las clases de '$nombre_actividad'.");
            }
    
            $conn->commit(); // Confirmar la transacción
            return true; 
    
        } catch (PDOException $e) {
            $conn->rollBack(); // Revertir cambios en caso de error
            error_log("Error en eliminarDisciplina: " . $e->getMessage()); // Registrar error
            return $e->getMessage(); // Devolver mensaje de error específico
        }
    }
    
    
    public static function clasesSocios() {
        include __DIR__ . '/../data/conexionBBDD.php'; 
    
        $sql = "SELECT 
                    c.id_clase, 
                    c.nombre_actividad, 
                    m.nombre AS nombre_monitor, 
                    s.nombre AS nombre_socio, 
                    s.apellidos AS apellido_socio
                FROM CLASES c
                JOIN MONITORES m ON c.dni_monitor = m.dni
                LEFT JOIN SOCIOS_CLASES cs ON c.id_clase = cs.id_clase
                LEFT JOIN SOCIOS s ON cs.dni_socio = s.dni
                ORDER BY c.id_clase";
    
        if (!$stmt = $conn->prepare($sql)) {
            throw new PDOException('Error en la preparación de la consulta clasesSocios');
        }
    
        if (!$stmt->execute()) {  // Verifica correctamente si `execute()` falla
            throw new PDOException('Error en la ejecución de la consulta clasesSocios');
        }
    
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!$resultado) {
            return []; // Devuelve un array vacío si no hay resultados para evitar errores
        }
    
        // Formatear los datos en un array estructurado
        $clases = [];
        foreach ($resultado as $fila) {
            $id_clase = $fila['id_clase'];
    
            if (!isset($clases[$id_clase])) {
                $clases[$id_clase] = [
                    'id_clase' => $fila['id_clase'],
                    'nombre_actividad' => $fila['nombre_actividad'],
                    'nombre_monitor' => $fila['nombre_monitor'],
                    'socios' => []
                ];
            }
    
            if (!empty($fila['nombre_socio']) && !empty($fila['apellido_socio'])) {
                $clases[$id_clase]['socios'][] = $fila['nombre_socio'] . " " . $fila['apellido_socio'];
            }
        }
    
        return array_values($clases);
    }


  
    

}
?>
