
<?php
abstract class Persona {

    /* Creamos las propiedades como 'protected' porque esta clase es abstracta y no va a crear objetos directamente. 
   No es necesario definir getters ni setters en esta clase, ya que las propiedades deben ser accesibles solo por 
   las clases que la hereden. Esto permite que las clases hijas puedan acceder y modificar estas propiedades. */
    protected $dni; 
    protected $nombre; 
    protected $apellidos;
    protected $fecha_nac; 
    protected $edad; 
    protected $telefono; 
    protected $email; 

   /**
    * Constructor
    * The function is a constructor that initializes object properties with provided values and
    * calculates the age based on the date of birth.
    * 
    * @param dni The DNI (Documento Nacional de Identidad) is a unique identification number assigned
    * to individuals in some countries, such as Spain. It typically consists of numbers and letters and
    * is used for identification purposes. In the context of your code snippet, the DNI is being passed
    * as a parameter to the constructor
    * @param nombre The parameter "nombre" in the constructor function is used to store the name of a
    * person. It is typically a string value representing the first name of the individual.
    * @param apellidos "apellidos" refers to the last names or surnames of a person. In the context of
    * the code snippet you provided, it is one of the parameters used in the constructor function of a
    * class.
    * @param fecha_nac The parameter `fecha_nac` in the `__construct` function represents the date of
    * birth of a person. It is used to store the date of birth in the object being created.
    * @param telefono The parameter "telefono" in the constructor function likely refers to the phone
    * number of the person being represented by the object being created. It is a common practice to
    * include contact information like phone numbers when creating objects that represent individuals
    * or entities.
    * @param email The `__construct` function in your code snippet is a constructor method for a class.
    * It initializes the object properties with the values passed as parameters. In this case, the
    * parameters are $dni, $nombre, $apellidos, $fecha_nac, $telefono, $email.
    */
    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email) {

        if($this->validarDni($dni))$this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->fecha_nac = $fecha_nac;
        $this->edad = $this->calcularEdad(); 
        $this->telefono = $telefono;  
        $this->email = $email; 
    }

   /**
    * Validación del DNI
    * This PHP function validates a Spanish DNI (National Identity Document) number by checking if the
    * provided letter matches the calculated letter based on the numerical part of the DNI.
    * 
    * @param dni The function `validarDni` is used to validate a Spanish DNI (Documento Nacional de
    * Identidad) number. The DNI number consists of 8 digits followed by a letter. The function
    * calculates the expected letter based on the 8 digits using a specific algorithm and then compares
    * it with
    * 
    * @return The function `validarDni` is returning a boolean value (`true` or `false`) based on
    * whether the input DNI (Spanish identification number) is valid or not. If the last letter of the
    * DNI matches the calculated correct letter based on the numerical part of the DNI, then the
    * function returns `true`, indicating that the DNI is valid. Otherwise, if the last
    */
    private function validarDni($dni){

       
        $check_dni  = false; 
        $dni_numeros = substr($dni, 0, 8); 
        $letraIntroducida = substr($dni, -1, 1); 

        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $letraCorrecta = $letras[intval($dni_numeros) % 23]; 

        if($letraIntroducida == $letraCorrecta) $check_dni=true; 
        else throw new datosIncorrectos('DNI INTRODUCIDO NO VÁLIDO'); 

        return $check_dni; 
    }

    /**
     * Calcular edad
     * The function calculates the age based on the date of birth provided.
     * 
     * @return `calcularEdad` function is returning the age of a person based on their date of
     * birth. It calculates the difference between the current date and the date of birth provided, and
     * then returns the age in years.
     */
    
     private function calcularEdad() { 
        // Obtiene la fecha actual
        $today = new DateTime(); 
        
        // Crea un objeto DateTime con la fecha de nacimiento del objeto
        $fecha_nac = new DateTime($this->fecha_nac); 
        
        // Calcula la diferencia entre la fecha actual y la fecha de nacimiento
        $diferencia = $today->diff($fecha_nac); 
        
        // Devuelve la edad en años (propiedad 'y' del objeto DateInterval)
        return $diferencia->y;
    }
    


    // Método mágico abstracto para obtener el valor de $name
    abstract public function __get($name);
    
    // Método mágico abstracto para asignar el valor de $name a $value
    abstract public function __set($name, $value);
}
?>