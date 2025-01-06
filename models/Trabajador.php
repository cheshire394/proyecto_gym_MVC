<?php


class Trabajador extends Persona {

    const RUTA_JSON_RECEPCIONISTAS = __DIR__ . '/../data/recepcionistas.json';
    const EUROS_HORA=30;




/* The code snippet you provided is defining private properties within the `Trabajador` class in PHP.
Let's break down each property: */

    private $funcion; 
    private $sueldo; 
    private $jornada; 
    private $horas_extra; 
    private $cuenta_bancaria;
    

    /**
     * The function is a constructor for a class that initializes properties including account number,
     * role, salary, extra hours, and work hours.
     * 
     * @param dni The `dni` parameter in the constructor function typically stands for "Documento
     * Nacional de Identidad" which is a national identification number used in some countries. It is a
     * unique identifier for individuals.
     * @param nombre The `__construct` function you provided is a constructor method for a class. It
     * initializes the properties of an object when it is created. In this case, it sets the values for
     * `cuenta_bancaria`, `funcion`, `sueldo`, `horas_extra`, and `j
     * @param apellidos The parameter "apellidos" typically refers to the last name or surname of a
     * person. It is a common practice in many cultures to have a first name (nombre) followed by the
     * last name (apellidos) to uniquely identify individuals.
     * @param fecha_nac The parameter `fecha_nac` in the constructor function represents the date of
     * birth of the employee. It is used to store the date when the employee was born.
     * @param telefono The `telefono` parameter in the constructor function is typically used to store
     * the phone number of the employee. It is a way to provide contact information for the employee.
     * @param email The `__construct` function you provided is a constructor method for a class. It
     * initializes the object properties with the values passed as arguments. In this case, it sets the
     * values for `cuenta_bancaria`, `funcion`, `sueldo`, `horas_extra`, and `j
     * @param cuenta_bancaria The `cuenta_bancaria` parameter in the constructor function is used to
     * store the bank account number of an employee. It is passed as an argument when creating a new
     * instance of the class. The `validarCuentaBancaria` method is called to validate the bank account
     * number before
     * @param funcion The `funcion` parameter in the constructor function is used to specify the role
     * or function of the employee. In the provided code snippet, the default value for `funcion` is
     * set to 'recepcionista', which means if no value is provided for `funcion` when creating an
     * instance
     * @param sueldo The parameter `` in the constructor function represents the salary of the
     * employee. In this case, the default value is set to 1100. This means that if no value is
     * provided for the `` parameter when creating an instance of the class, the salary will
     * default to
     * @param horas_extra The parameter `horas_extra` in the constructor function represents the number
     * of extra hours worked by the employee. It is initialized with a default value of 0, indicating
     * that initially, the employee has not worked any extra hours. This parameter can be used to
     * calculate additional payment or bonuses based on the
     * @param jornada The parameter `jornada` in the constructor function represents the regular number
     * of working hours per week for the employee. In this case, it is set to a default value of 40
     * hours per week. This value can be adjusted based on the specific working hours required for the
     * employee's position or
     */
    function __construct(
        $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, 
        $cuenta_bancaria, $funcion = 'recepcionista', $sueldo = 1100,$horas_extra = 0, $jornada =40) {
    
    
    $this->cuenta_bancaria = $this->validarCuentaBancaria($cuenta_bancaria); 
    $this->funcion = $funcion;
    $this->sueldo = $sueldo;
    $this->horas_extra = $horas_extra;
    $this->jornada = $jornada;

    //propiedades heredadas:
    parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);
    

}

        

    public function __set($name, $value) {

        if (property_exists($this, $name)) {

            $this->$name = $value; 


            if($name == 'jornada'){

                $actualizar_sueldo= $value * Trabajador::EUROS_HORA; 
                $this->__set('sueldo', $actualizar_sueldo); 
            }

        } else {
            throw new Exception('ERROR EN EL SETTER TRABAJADOR: LA PROPIEDAD QUE DESEAS MODIFICAR NO EXISTE'); 
        }
    }

    public function __get($name) {

        if (property_exists($this, $name)) {
            return $this->$name; 

        } else {
            throw new Exception('ERROR EN EL GETTER TRABAJADOR: LA PROPIEDAD QUE DESEAS OBTENER NO EXISTE');
        }
    }

   
    //METODOS PARA MANEJAR EL LOGEO DE LA TRABAJADORA RECEPCIONISTA:




  /**
   * The function `recepcionistasJSON` reads a JSON file containing receptionists' data and returns
   * an array of receptionists, initializing the file if it doesn't exist.
   * 
   * @return The `recepcionistasJSON` function returns an array of receptionists. If the JSON file
   * containing receptionists exists, it reads the content, decodes it into an array, and returns the
   * array of receptionists. If the JSON file does not exist, it creates an empty JSON file and returns
   * an empty array.
   */
    public static function recepcionistasJSON() {

        // si el archivo JSON existe
        if (!file_exists(self::RUTA_JSON_RECEPCIONISTAS)) {

            // Creamos un archivo JSON vacío si no existe, si es el primer logeo
            file_put_contents(self::RUTA_JSON_RECEPCIONISTAS, json_encode([]));
        }

        // obtenemos las recepcionstas registradas
        $recepcionistas = file_get_contents(self::RUTA_JSON_RECEPCIONISTAS);
        
        $recepcionistas = json_decode($recepcionistas, true);

        // Devolvemos el array de recepcionistas (vacío si no hay ninguna)
      
        return $recepcionistas ?: [];
    }

   
    


    /**
     * The PHP function `registrar` registers a new receptionist by checking if the provided ID is
     * unique, encrypting the password, adding the new receptionist to the existing list, and saving
     * the updated list to a JSON file.
     * 
     * @param datos The `datos` parameter in the `registrar` function seems to contain information
     * about a new receptionist that needs to be registered. It likely includes details such as the
     * receptionist's name, DNI (identification number), and password.
     * 
     * @return The `registrar` function returns a boolean value. If a recepcionista with the same DNI
     * already exists in the list of recepcionistas, it returns `false` to indicate that the
     * registration was not successful. If the registration is successful (i.e., no existing
     * recepcionista with the same DNI), it returns `true` to indicate a successful registration.
     */
    public static function registrar($datos) {
         
        //Array con las recepcionistas del JSON
        $recepcionistas = self::recepcionistasJSON();

        // Verificamos si ya existe un recepcionista con ese DNI
        
        foreach ($recepcionistas as $recepcionista) {
            if ($recepcionista['dni'] === $datos['dni']) {
                return false; // Ya existe, salimos del registro con mensaje informátivo
            }
        }

    
        // encriptar las contraseña, en el json.
        $datos['password'] = password_hash($datos['password'], PASSWORD_BCRYPT);

        // Añadimos nueva recepcionista
        $recepcionistas[] = $datos;

        // Guardar en el JSON
        $registrado=file_put_contents(self::RUTA_JSON_RECEPCIONISTAS, json_encode($recepcionistas, JSON_PRETTY_PRINT));

        return $registrado; //boolean
    }







   /**
    * The PHP function `login` checks if the provided DNI and password match those stored in a JSON
    * file of receptionists and starts a session if successful.
    * 
    * @param dni The "dni" parameter in the code snippet stands for the identification number
    * (Documento Nacional de Identidad) of a receptionist. It is used to uniquely identify a
    * receptionist in the system.
    * @param password The `login` function you provided is a PHP method that takes a DNI (Documento
    * Nacional de Identidad) and a password as parameters. It then checks if the provided DNI and
    * password match any of the receptionists' credentials stored in a JSON file. If a match is found,
    * it
    * 
    * @return The `login` function returns a boolean value. It returns `true` if the provided DNI and
    * password match with any of the recepcionistas' data stored in the JSON file, and it successfully
    * starts a session with the user's DNI and name. If the credentials do not match any recepcionista
    * or are invalid, it returns `false`.
    */
    public static function login($dni, $password) {

        //Array con las recepcionistas del JSON
        $recepcionistas = self::recepcionistasJSON();

        // Buscamos si el DNI  y contraseña conincide con las guardadas en el fichero: 
        foreach ($recepcionistas as $recepcionista) {
            if ($recepcionista['dni'] === $dni && password_verify($password, $recepcionista['password'])) {

                // Iniciamos session: 
                session_start();
                $_SESSION['dni'] = $dni;
                $_SESSION['nombre'] = $recepcionista['nombre'];
                return true;
            }
        }

        // Credenciales no válidas
        return false;
    }

 

 /**
  * The function "validarCuentaBancaria" in PHP validates a bank account number starting with 'ES' and
  * having a length of 24 characters.
  * 
  * @param cuenta The `validarCuentaBancaria` function you provided is used to validate a bank account
  * number. It checks if the account number starts with 'ES' and has a total length of 24 characters.
  * It then verifies if the remaining characters are all digits using `ctype_digit`.
  * 
  * @return The function `validarCuentaBancaria` is returning the input `` if it meets the
  * specified conditions. If the first two characters of the input are 'ES' and the length of the input
  * is 24, and all the characters after 'ES' are digits, then the function returns the input ``.
  */
    public function validarCuentaBancaria($cuenta) {
        
        $cuenta = trim($cuenta);
        if (substr($cuenta, 0, 2) === 'ES' && strlen($cuenta) === 24) {
            
            $numeros = substr($cuenta, 2);
            /*usamos ctype_digit, porqué es más restrictivo que is_numeric y no permite usar números negativos */
            if (ctype_digit($numeros)) {
                return $cuenta; 
            }
        }
    
        throw new datosIncorrectos('ERROR: LA CUENTA BANCARIA INTRODUCIDA NO ES VÁLIDA');
    }


    /**
     * The function creates objects for receptionists based on data retrieved from a JSON file.
     * 
     * @return An array of Trabajador objects representing receptionists is being returned.
     */
    public static function crearObjetosRecepcionista(){

    $recepcionistas = self::recepcionistasJSON(); 
     foreach($recepcionistas as $dni => $recepcionista){

        $recepcionistaObj= new Trabajador(
            $recepcionista['dni'],
            $recepcionista['nombre'],
            $recepcionista['apellidos'],
            $recepcionista['fecha_nac'],
            $recepcionista['telefono'],
            $recepcionista['email'],
            $recepcionista['cuenta_bancaria'],
            'recepcionista',
            $recepcionista['sueldo'],
            $recepcionista['horas_extra'],
            $recepcionista['jornada'] 
        );

          $recepcionistasObj[] = $recepcionistaObj;

     }

     return $recepcionistasObj; 


}

}


?>