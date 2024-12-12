<!-- Clase Socio -->
<?php

/* The code `ini_set('display_errors', 1); ini_set('display_startup_errors', 1);
error_reporting(E_ALL);` is used in PHP to configure the error reporting settings. */
//Visualizar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* The `Socio` class in PHP represents a member with properties such as membership details,
reservations, and methods for managing memberships and class bookings. */
final class Socio extends Persona
{
    private $tarifa; // 2 clases, // 3 clases // indefinidas (a la semana); hay que implementarlo en el registro
    private $fecha_alta;
    private $fecha_baja;
    private $reservas_clases = [];

    private static $contador_socios = 0;
    private static $socios = [];
    // VER QUE HACER CON LA CUENTA BANCARIA
    private static $bajas_socios = [];

    /**
     * The function is a constructor for a PHP class that initializes properties for a new member,
     * including setting a default tariff.
     * 
     * @param dni The `dni` parameter in the `__construct` function likely stands for "Documento
     * Nacional de Identidad" which is a unique identification number used in some countries. It is
     * commonly used to uniquely identify individuals.
     * @param nombre The parameter "nombre" in the constructor function refers to the first name of a
     * person. It is used to store the first name of a member when creating a new instance of the
     * class.
     * @param apellidos The parameter "apellidos" typically refers to the last name or surname of a
     * person. It is a common practice to include both the first name and last name when referring to
     * an individual.
     * @param fecha_nac The parameter `fecha_nac` in the `__construct` function represents the date of
     * birth of a person. It is used to store the date of birth of a member when creating a new
     * instance of the class.
     * @param telefono The `telefono` parameter in the `__construct` function likely refers to the
     * phone number of the person being registered as a member. It is a required piece of information
     * for creating a new member object.
     * @param email The `__construct` function you provided is a constructor method for a class that
     * seems to handle information about members or clients. The parameters passed to the constructor
     * are ``, ``, ``, ``, ``, ``, and an optional
     * parameter `
     * @param tarifa The `tarifa` parameter in the `__construct` function is a default parameter with a
     * default value of "2". This means that if the `tarifa` parameter is not provided when creating an
     * instance of the class, it will default to "2".
     */
    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa = "2")
    {
        self::$contador_socios++;
        $this->fecha_alta = date('d-m-Y');
        $this->fecha_baja = 'desconocida';
        $this->tarifa = $tarifa;

        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);

        self::$socios[$this->dni] = $this;
    }

    /**
     * The function __set in PHP is used to set the value of a property in a class, with specific
     * restrictions and error handling for the 'fecha_alta' property.
     * 
     * @param name The `__set` magic method in PHP is triggered when writing data to inaccessible
     * properties. In this case, the method is checking if the property being set is `fecha_alta`. If
     * it is, an exception of type `datosIncorrectos` is thrown with a specific error message.
     * @param value The `__set` magic method in PHP is triggered when you try to assign a value to a
     * property that is not accessible or does not exist within the class. In your code snippet, the
     * `__set` method is being used to handle property assignments dynamically.
     */
    public function __set($name, $value)
    {
        if ($name == 'fecha_alta') {
            throw new datosIncorrectos("ERROR DESDE EL SETTER SOCIO: NO ES POSIBLE MODIFICAR LA FECHA DE ALTA DE UN SOCIO EN EL REGISTRO");
        }

        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new Exception("ERROR DESDE EL SETTER SOCIO: La propiedad '$name' no existe en la clase Socio.");
        }
    }

    /**
     * The function __get in PHP is used to dynamically retrieve inaccessible properties of an object.
     * 
     * @param name The `` parameter in the `__get` magic method refers to the name of the property
     * that is being accessed or retrieved from the object dynamically. In this context, it is used to
     * check if the property exists in the class instance and return its value if it does, or throw an
     * exception
     * 
     * @return If the property exists in the class, the value of that property will be returned.
     * Otherwise, an Exception will be thrown with the message "ERROR DESDE EL GETTER SOCIO: La
     * propiedad '' no existe en la clase Socio."
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("ERROR DESDE EL GETTER SOCIO: La propiedad '$name' no existe en la clase Socio.");
        }
    }

    /**
     * The function "mostrarTodosSocios" in PHP displays the data of all members stored in a static
     * array.
     */
    public static function mostrarTodosSocios()
    {
        foreach (self::$socios as $dni => $obj_socio) {
            $propiedades = get_object_vars($obj_socio);

            echo "<b>DATOS DEL SOCIO $dni:</b><br>";
            foreach ($propiedades as $propiedad => $value) {
                if (is_array($value)) continue;
                echo $propiedad . " => " . $value . "<br>";
            }

            echo "<br>";
        }
    }

    /**
     * The function "mostrarDatosSocio" in PHP displays the properties and values of an object,
     * excluding arrays.
     */
    public function mostrarDatosSocio()
    {
        $propiedades = get_object_vars($this);

        foreach ($propiedades as $propiedad => $value) {
            if (is_array($value)) continue;

            echo $propiedad . " => " . $value . "<br>";
        }
    }

    // IMPLEMENTAR CON CONTROLLERS Y VIEW
    /**
     * The `reservarClase` function in PHP includes the `Clase.php` file and retrieves the gym class
     * schedule using the `getHorario_gym` method.
     */
    public function reservarClase()
    {
        require_once('Clase.php');
        $clases = Clase::getHorario_gym();
    }

    // IMPLEMENTAR CON CONTROLLERS Y VIEW
    public function desapuntarseClase()
    {
    }

    /**
     * The function "darBajaSocio" is used to mark a member as inactive by setting the "fecha_baja"
     * property, decreasing the total count of members, adding the member to a list of inactive
     * members, and removing the member from the active members list.
     */
    public function darBajaSocio()
    {
        // A MEDIAS DE IMPLEMENTAR:
        $this->__set('fecha_baja', date('d-m-Y'));
        self::$contador_socios--;

        self::$bajas_socios[$this->dni] = $this; // añadimos a los socios que se han dado de baja

        // eliminamos del array de socios:
        unset(self::$socios[$this->dni]);
    }
}
?>
