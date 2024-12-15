<!--The class `Socio` represents a member with properties such as personal information, membership
details, and methods for managing and interacting with member data. -->
<?php

// Visualizar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

final class Socio extends Persona
{
    private $tarifa; // Tipos de tarifas: 2 clases, 3 clases o indefinidas (a la semana)
    private $fecha_alta;
    private $fecha_baja;
    private $cuenta_bancaria;
    private static $contador_socios = 0;
    private static $socios = [];
    private static $bajas_socios = [];

   /**
    * The function is a PHP constructor that validates input data for a new member, sets default
    * values, and stores the member in a class array.
    * 
    * @param dni The `__construct` function you provided seems to be a constructor method for a class,
    * where you are initializing properties of an object. The parameters passed to the constructor are
    * ``, ``, ``, ``, ``, ``, ``, and
    * @param nombre The `nombre` parameter in the `__construct` function represents the first name of a
    * person. It is typically a string value that holds the first name of the individual.
    * @param apellidos The parameter "apellidos" typically refers to the last name or surname of a
    * person. It is a common practice to include both the first name and last name when referring to an
    * individual. In the context of the provided code snippet, the "apellidos" parameter is likely used
    * to store the last
    * @param fecha_nac The parameter `fecha_nac` in the `__construct` function represents the date of
    * birth of a person. It is used to store the date of birth of a member when creating a new instance
    * of the class.
    * @param telefono The `telefono` parameter in the `__construct` function likely represents the
    * phone number of the person being registered as a member. It is a piece of contact information
    * that can be used to reach out to the member if needed.
    * @param email The email parameter in the constructor function is used to store the email address
    * of the person being registered as a member. It is validated using the `filter_var` function with
    * the `FILTER_VALIDATE_EMAIL` filter to ensure that the email provided is in a valid email format.
    * If the email is not valid
    * @param tarifa The `tarifa` parameter in the constructor function seems to represent the
    * membership fee or subscription level for a member. In this case, it has a default value of "2".
    * This parameter allows for specifying different membership tiers or fees for the members when
    * creating an instance of the class.
    * @param cuenta_bancaria The `cuenta_bancaria` parameter in the constructor function seems to
    * represent the bank account number of the person being registered as a member. This parameter is
    * used to store the bank account information of the member in the class instance.
    */
    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa = "2", $cuenta_bancaria)
    {
        // Validar entradas
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new datosIncorrectos("ERROR: El email proporcionado no es válido.");
        }
        if (!preg_match('/^\d{8}[A-Z]$/', $dni)) {
            throw new datosIncorrectos("ERROR: El DNI proporcionado no es válido.");
        }

        self::$contador_socios++;
        $this->fecha_alta = date('d-m-Y');
        $this->fecha_baja = 'desconocida';
        $this->tarifa = $tarifa;
        $this->cuenta_bancaria = $cuenta_bancaria;

        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);

        self::$socios[$this->dni] = $this;
    }

    // Método mágico para establecer propiedades
    /**
     * The function __set in PHP is used to set the value of a property in a class, with specific
     * restrictions and error handling.
     * 
     * @param name The `name` parameter in the `__set` magic method refers to the name of the property
     * that is being set on the object. In this context, it is used to determine which property is
     * being set and apply specific logic based on the property name.
     * @param value The `` parameter in the `__set` magic method represents the value that is
     * being assigned to a property of an object. In the provided code snippet, this parameter is used
     * to set the value of a property in the class `Socio` when the property is not 'fecha_alta
     */
    public function __set($name, $value)
    {
        if ($name == 'fecha_alta') {
            throw new datosIncorrectos("ERROR: No es posible modificar la fecha de alta de un socio.");
        }

        if (property_exists($this, $name)) {
            $this->$name = $value;
        } else {
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Socio.");
        }
    }

    // Método mágico para obtener propiedades
    /**
     * The function __get in PHP is used to dynamically retrieve inaccessible properties of an object.
     * 
     * @param name The `__get` magic method in PHP is used to intercept attempts to access
     * non-accessible or non-existing properties of an object. In this case, the method checks if the
     * property with the name specified in the `` parameter exists in the current object instance.
     * If it does, the method
     * 
     * @return If the property exists in the class, the value of that property will be returned.
     * Otherwise, an Exception will be thrown with the message "ERROR: La propiedad '' no existe
     * en la clase Socio."
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Socio.");
        }
    }
    public static function filtrarSocios($campo, $valor)
    {
        $socios = self::cargarSocios();
        return array_filter($socios, function ($socio) use ($campo, $valor) {
            return isset($socio[$campo]) && stripos($socio[$campo], $valor) !== false;
        });
    }

    // Agregar un nuevo socio
    private static $rutaJSON = __DIR__ . '/../data/socios.json';

    public static function addSocio(string $dni, string $nombre, string $apellidos, string $fecha_nac, string $telefono, string $email, string $tarifa, string $cuenta_bancaria)
    {
        try {
            // Leer socios existentes
            $sociosJson = self::cargarSocios();

            // Verificar si ya existe un socio con el mismo DNI
            if (self::buscarSocio('dni', $dni)) {
                throw new Exception("ERROR: Ya existe un socio con el DNI $dni");
            }

            // Crear el nuevo socio
            $nuevoSocio = new Socio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $cuenta_bancaria);

            // Guardar el socio en el archivo JSON
            $sociosJson[] = $nuevoSocio->toArray();
            self::guardarSocios($sociosJson);

            return true; // Éxito
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private static function cargarSocios(): array
    {
        if (!file_exists(self::$rutaJSON)) {
            return []; // Si no existe el archivo, retorna un array vacío
        }

        $sociosJson = file_get_contents(self::$rutaJSON);
        return json_decode($sociosJson, true) ?? [];
    }

    private static function guardarSocios(array $socios)
    {
        file_put_contents(self::$rutaJSON, json_encode($socios, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function buscarSocio(string $campo, $valor): ?array
    {
        $socios = self::cargarSocios();

        foreach ($socios as $socio) {
            if ($socio[$campo] === $valor) {
                return $socio; // Retorna el socio si lo encuentra
            }
        }

        return null; // Retorna null si no lo encuentra
    }

    public static function modificarSocio(
        string $dni_socio,
        ?string $nombre = null,
        ?string $apellidos = null,
        ?string $fecha_nac = null,
        ?string $telefono = null,
        ?string $email = null,
        ?string $tarifa = null,
        ?string $fecha_alta = null,
        ?string $fecha_baja = null,
        ?string $cuenta_bancaria = null
    ): string {
        $socios = self::cargarSocios();
        $socioEncontrado = false;

        foreach ($socios as &$socio) {
            if ($socio['dni'] === $dni_socio) {
                $socioEncontrado = true;

                // Actualizar solo los campos proporcionados
                if ($nombre !== null) $socio['nombre'] = $nombre;
                if ($apellidos !== null) $socio['apellidos'] = $apellidos;
                if ($fecha_nac !== null) $socio['fecha_nac'] = $fecha_nac;
                if ($telefono !== null) $socio['telefono'] = $telefono;
                if ($email !== null) $socio['email'] = $email;
                if ($tarifa !== null) $socio['tarifa'] = $tarifa;
                if ($fecha_alta !== null) $socio['fecha_alta'] = $fecha_alta;
                if ($fecha_baja !== null) $socio['fecha_baja'] = $fecha_baja;
                if ($cuenta_bancaria !== null) $socio['cuenta_bancaria'] = $cuenta_bancaria;

                break;
            }
        }

        if (!$socioEncontrado) {
            throw new Exception("No se encontró un socio con el DNI: $dni_socio");
        }

        self::guardarSocios($socios);
        return "El socio con DNI $dni_socio ha sido modificado correctamente.";
    }

    private function toArray(): array
    {
        return [
            'dni' => $this->dni,
            'nombre' => $this->nombre,
            'apellidos' => $this->apellidos,
            'fecha_nac' => $this->fecha_nac,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'tarifa' => $this->tarifa,
            'fecha_alta' => $this->fecha_alta ?? date('Y-m-d'),
            'fecha_baja' => $this->fecha_baja ?? null,
            'cuenta_bancaria' => $this->cuenta_bancaria,
        ];
    }



    public static function mostrarSocios()
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


    public function verSocio()
    {
        $propiedades = get_object_vars($this);

        foreach ($propiedades as $propiedad => $value) {
            if (is_array($value)) continue;

            echo $propiedad . " => " . $value . "<br>";
        }
    }

    /**
     * The function "darBajaSocio" is used to mark a member as inactive by setting the "fecha_baja"
     * property, decreasing the total count of members, adding the member to a list of inactive
     * members, and removing the member from the active members list.
     */ public static function eliminarSocio($campo, $valor)
    {
        try {
            // Cargar los socios desde el archivo JSON
            $socios = self::cargarSocios();

            // Buscar y eliminar el socio con el campo y valor especificados
            $sociosActualizados = array_filter($socios, function ($socio) use ($campo, $valor) {
                return !isset($socio[$campo]) || $socio[$campo] !== $valor;
            });

            // Si no hay cambios, significa que no se encontró el socio
            if (count($socios) === count($sociosActualizados)) {
                throw new Exception("No se encontró ningún socio con $campo igual a '$valor'.");
            }

            // Guardar los socios actualizados en el archivo JSON
            self::guardarSocios(array_values($sociosActualizados));

            return "El socio con $campo igual a '$valor' ha sido eliminado correctamente.";
        } catch (Exception $e) {
            throw new Exception("Error al eliminar el socio: " . $e->getMessage());
        }
    }
}
