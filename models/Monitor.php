<?php

final class Monitor extends Trabajador {

    const RUTA_JSON_MONITORES= __DIR__ . '/../data/monitores.json';  


 /* The above code appears to be a comment block in PHP. It includes some commented-out PHP code
 related to properties of Monitors, specifically `` and ``. The code also
 includes some commented-out text with the question "What is the above code doing?" and no answer
 provided. */
    //propiedades de los Monitores
    private $disciplinas = []; 
    private  $clases = []; 




   /**
    * The function is a constructor for a class with default values and properties related to a monitor
    * in a fitness center.
    * 
    * @param dni The `dni` parameter in the constructor function likely stands for "Documento Nacional
    * de Identidad," which is a unique identification number used in some countries. It is commonly
    * used to identify individuals for various purposes such as legal, administrative, or financial
    * transactions.
    * @param nombre The `__construct` function you provided is a constructor method for a class. It
    * initializes the object with the given parameters. In this case, the parameters are:
    * @param apellidos The `apellidos` parameter in the constructor function refers to the last name or
    * surname of the person being instantiated as an object. It is a common practice to include both
    * the first name (`nombre`) and last name (`apellidos`) when creating an object to represent an
    * individual.
    * @param fecha_nac The parameter `fecha_nac` in the constructor function is typically used to store
    * the date of birth of the person. It stands for "fecha de nacimiento" in Spanish, which translates
    * to "date of birth" in English. This parameter is used to capture and store the birthdate of the
    * @param telefono The `telefono` parameter in the constructor function is used to store the phone
    * number of the person being instantiated. It is likely a string or integer variable that holds the
    * contact number of the individual.
    * @param email The `email` parameter in the constructor function is used to store the email address
    * of the person being instantiated as a monitor. This email address can be used for communication
    * purposes, notifications, or any other relevant interactions within the system.
    * @param cuenta_bancaria The `cuenta_bancaria` parameter in the constructor function is used to
    * store the bank account number of the employee. This information is typically used for payroll
    * purposes to ensure that the employee's salary is deposited into the correct bank account.
    * @param funcion The `funcion` parameter in the constructor function is used to specify the role or
    * function of the object being created. In this case, the default value for `funcion` is set to
    * 'monitor'. This parameter allows you to initialize the object with a specific role, but it can be
    * overridden
    * @param sueldo The `sueldo` parameter in the constructor function represents the salary of the
    * employee. In this case, the default value for `sueldo` is set to 1100. This means that if the
    * `sueldo` is not provided when creating an instance of the class, it will default to
    * @param horas_extra The parameter `horas_extra` in the constructor function represents the number
    * of extra hours worked by the employee. By default, it is set to 0, meaning that if the employee
    * works extra hours beyond their regular working hours, those hours can be tracked and accounted
    * for separately. This parameter allows for
    * @param jornada The parameter "jornada" in the constructor function refers to the number of hours
    * in a standard workday for the employee. In this case, the default value for "jornada" is set to
    * 40 hours, which means that the employee is expected to work 40 hours per week
    */
    function __construct(
        
        $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,
        $cuenta_bancaria,$funcion='monitor',$sueldo = 1100, $horas_extra = 0, $jornada=40) {
    
        $this->funcion='monitor'; 
         // clases y disciplinas se añade/elimina  cuando se manipula las clases (añadir, sustituitMonitor, eliminar disciplina...).
        $this->clases=[]; 
        $this->disciplinas =[]; 

        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email,$cuenta_bancaria,$funcion, $sueldo, $horas_extra, $jornada); 
    }


    

  /**
   * The function __set in PHP sets the value of a property only if the property name is 'clases' or
   * 'disciplinas', otherwise it calls the parent __set method.
   * 
   * @param name The `name` parameter in the `__set` method refers to the name of the property being
   * set in the object. In this code snippet, it checks if the property name is either 'clases' or
   * 'disciplinas'. If it is, it sets the value of that property to
   * @param value The `value` parameter in the `__set` method represents the value that is being
   * assigned to the property identified by the `` parameter. In this code snippet, the method
   * checks if the `` is either 'clases' or 'disciplinas'. If it is, it
   */
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






   /**
    * The function `monitoresJSON` reads data from a JSON file, calculates salary and work hours for
    * each monitor, creates Monitor objects, and returns an array of Monitor objects.
    * 
    * @return An array of Monitor objects is being returned. Each Monitor object contains information
    * such as the monitor's personal details (name, surname, date of birth, phone number, email, bank
    * account), role, salary, extra hours worked, total hours worked, disciplines they teach, and
    * classes they conduct.
    */
    public static function monitoresJSON()
    {
        // Leer el contenido actual del archivo JSON
        $monitoresJSON = file_get_contents(self::RUTA_JSON_MONITORES);
        $monitores = json_decode($monitoresJSON, true);

        $monitoresObjetos = []; // Inicializar array para almacenar objetos Monitor

        foreach ($monitores as $monitor_clase_add => $monitor) {
            // Calculamos la jornada en función del número de clases
            $jornada = count($monitor['clases']) * Clase::DURACION_CLASE; // Cada clase dura 2 horas

            // Calculamos el sueldo 
            $sueldo = $jornada * self::EUROS_HORA; // 30 euros por hora

            // Crear el objeto Monitor
            $monitorObj = new Monitor(
                $monitor_clase_add,
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
            $monitoresObjetos[$monitor_clase_add] = $monitorObj;
        }

        return $monitoresObjetos;
    }

   

   /**
    * The function `to_array` converts a Monitor object into an array compatible with JSON structure.
    * 
    * @return The `to_array` method is returning an array containing the properties of the Monitor
    * object. The array includes keys such as 'nombre', 'apellidos', 'fecha_nac', 'telefono', 'email',
    * 'cuenta_bancaria', 'funcion', 'sueldo', 'horas_extra', 'jornada', 'disciplinas', and 'clases',
    * each with their
    */
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






   /**
    * The function `guardarMonitoresEnJSON` converts monitor objects and their associated classes to
    * arrays and saves them in JSON format.
    * 
    * @param monitoresObj  is an array where each key represents the DNI (identification
    * number) of a monitor and the corresponding value is an object representing the monitor. Each
    * monitor object may have associated classes (objects) that also need to be converted to arrays
    * before saving the data to a JSON file.
    * 
    * @return The function `guardarMonitoresEnJSON` is returning the result of the `file_put_contents`
    * function, which is either the number of bytes written to the file on success, or `false` on
    * failure. This value is then returned by the function.
    */
    public static function guardarMonitoresEnJSON($monitoresObj) {
        
            $monitoresArray = [];
        
            foreach ($monitoresObj as $monitor_clase_add => $monitorObj) {

                // Convertir cada objeto a array
                $monitorArray = $monitorObj->to_array();
                
                // como los monitores, tienen asociadas clases (objetos), debemos covertirlos también a array.
                if (isset($monitorArray['clases']) && is_array($monitorArray['clases'])) {

                    $monitorArray['clases'] = array_map(function($clase) {

                        return is_object($clase) ? $clase->toArray() : $clase;  //llama a toArray de clase "clases"

                    }, $monitorArray['clases']);

                }
                
                //añadimos el monitor al array común con todos los monitores
                $monitoresArray[$monitor_clase_add] = $monitorArray;
            }
        
            //guardamos los monitores...en formato JSON
            $guardado = file_put_contents(self::RUTA_JSON_MONITORES, json_encode($monitoresArray, JSON_PRETTY_PRINT));
            
           
            return $guardado; 
        } 
    






   /**
    * The function `actualizarDatosMonitores` updates monitor data based on class changes, including
    * adjusting schedules and adding new classes and disciplines.
    * 
    * @param dni_monitor The `dni_monitor` parameter in the `actualizarDatosMonitores` function
    * represents the unique identifier (DNI) of the monitor whose data is being updated. This
    * identifier is used to retrieve and update information about the monitor, such as their schedule,
    * classes they teach, and disciplines they are
    * @param monitor_clase_sustituida The function `actualizarDatosMonitores` is responsible for
    * updating monitor data based on certain conditions. Let's break down the logic step by step:
    * @param clase_creada The provided code snippet is a PHP function named `actualizarDatosMonitores`
    * that updates monitor data based on certain conditions. It takes three parameters: ``,
    * ``, and ``.
    * 
    * @return The function `actualizarDatosMonitores` returns a boolean value indicating whether the
    * update of monitor data was successfully saved in a JSON file.
    */
    public static function actualizarDatosMonitores($monitor_clase_add, $monitor_clase_eliminada,$clase){

       // Obtenemos monitores desde json
       $monitoresObj = Monitor::monitoresJSON();

       $id_clase = $clase->__get('id_clase'); 
       $nombre_actividad= $clase->__get('nombre_actividad'); 
       



       //Solamente es necesario ejecutarlo, si existia previamente un monitor con esa clase 
       //(puede ser string dni_monitor o puede ser null, depende del retorno de guardarClaseJSON).
       if(!empty($monitor_clase_eliminada)){

           if ($monitor_clase_add !== $monitor_clase_eliminada){ 
               //actualizar jornada solo si no es el mismo monitor que ejercia la clase sustituida
               $jornada_monitor= $monitoresObj[$monitor_clase_eliminada]->__get('jornada'); 
               $actualizar_jornada= $jornada_monitor - Clase::DURACION_CLASE; 
               $monitoresObj[$monitor_clase_eliminada]->__set('jornada', $actualizar_jornada); 
           
           }

           // eliminams la clase del objeto  porque ya no la va impartir esa clase (ha sido eliminada en guardarClaseJSON())
           $monitor_clases =$monitoresObj[$monitor_clase_eliminada]->__get('clases'); 
           unset($monitor_clases[$id_clase]); 

           // Actualizamos las clases del monitor en el objeto
           $monitoresObj[$monitor_clase_eliminada]->__set('clases', $monitor_clases);
       }
       



   //Actualizamos los datos del monitor que impartirá la nueva clase (si se esta añadiendo una clase o sustituyendo un monitor): 
   //NOTA*-> esta parte del método no se ejecuta, si se llama desde eliminarDisciplina o eliminarClase

   if(!empty($monitor_clase_add)){
       
        if ($monitor_clase_add !== $monitor_clase_eliminada) {
            
            // Actualizamos la jornada
            $jornada_monitor = $monitoresObj[$monitor_clase_add]->__get('jornada');
            $actualizar_jornada = $jornada_monitor + Clase::DURACION_CLASE;
            $monitoresObj[$monitor_clase_add]->__set('jornada', $actualizar_jornada);
            

        }

        // Actualizamos clases del monitor
        $clases_monitor = $monitoresObj[$monitor_clase_add]->__get('clases');
        $clases_monitor[$id_clase] = $clase;
        $monitoresObj[$monitor_clase_add]->__set('clases', $clases_monitor);
        
    
            // Actualizamos las disciplinas del monitor (solamente, si no la tenia asignada antes)
            $disciplinas_monitor = $monitoresObj[$monitor_clase_add]->__get('disciplinas');
            if (!in_array($nombre_actividad, $disciplinas_monitor)) {
                $disciplinas_monitor[] = $nombre_actividad;
                $monitoresObj[$monitor_clase_add]->__set('disciplinas', $disciplinas_monitor);
            
            }
    
    }

           // guardar y verificar que se ha guardado
           $guardado = Monitor::guardarMonitoresEnJSON($monitoresObj);

   
           return $guardado; 
       }
   

    

    
}

?>