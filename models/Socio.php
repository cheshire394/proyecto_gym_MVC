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
    private $reservas_clases = [];
    private $cuenta_bancaria;

    private static $contador_socios = 0;
    private static $socios = [];
    private static $bajas_socios = [];

    // Constructor de la clase
    function __construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa = "2", $cuenta_bancaria, $reservas_clases = [])
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
        $this->reservas_clases = $reservas_clases;

        parent::__construct($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email);

        self::$socios[$this->dni] = $this;
    }

    // Método mágico para establecer propiedades
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
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        } else {
            throw new Exception("ERROR: La propiedad '$name' no existe en la clase Socio.");
        }
    }

    // Agregar un nuevo socio
    public static function addSocio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $cuenta_bancaria, $reservas_clases = [])
    {
        try {
            $rutaJSON = __DIR__ . '/../data/socios.json';

            // Leer el archivo JSON
            $sociosJson = file_exists($rutaJSON) ? json_decode(file_get_contents($rutaJSON), true) : [];

            // Verificar si el socio ya existe
            foreach ($sociosJson as $socio) {
                if ($socio['dni'] === $dni) {
                    throw new datosIncorrectos("<b>ERROR: Ya existe un socio con el DNI $dni</b>");
                }
            }

            // Crear nuevo objeto Socio
            $nuevoSocio = new Socio($dni, $nombre, $apellidos, $fecha_nac, $telefono, $email, $tarifa, $cuenta_bancaria, $reservas_clases);

            // Guardar al JSON
            $nuevoSocio->guardarSocioEnJSON();

            return true;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function modificarSocio(
        $dni_socio,
        $nombre = null,
        $apellidos = null,
        $fecha_nac = null,
        $edad = null,
        $telefono = null,
        $email = null,
        $tarifa = null,
        $fecha_alta = null,
        $fecha_baja = null,
        $reservas_clases = null,
        $cuenta_bancaria = null
    ) {
        try {
            // Ruta del archivo JSON
            $rutaJSON = __DIR__ . '/../data/socios.json';
    
            // Leer el contenido actual del archivo JSON
            if (!file_exists($rutaJSON)) {
                throw new Exception("El archivo de socios no existe.");
            }
            $sociosJson = file_get_contents($rutaJSON);
            $socios = json_decode($sociosJson, true);
    
            // Buscar el socio por DNI
            $socioEncontrado = false;
    
            foreach ($socios as &$socio) {
                if ($socio['dni'] === $dni_socio) {
                    $socioEncontrado = true;
    
                    // Actualizar solo los campos proporcionados
                    if ($nombre !== null) $socio['nombre'] = $nombre;
                    if ($apellidos !== null) $socio['apellidos'] = $apellidos;
                    if ($fecha_nac !== null) $socio['fecha_nac'] = $fecha_nac;
                    if ($edad !== null) $socio['edad'] = $edad;
                    if ($telefono !== null) $socio['telefono'] = $telefono;
                    if ($email !== null) $socio['email'] = $email;
                    if ($tarifa !== null) $socio['tarifa'] = $tarifa;
                    if ($fecha_alta !== null) $socio['fecha_alta'] = $fecha_alta;
                    $socio['fecha_baja'] = $fecha_baja !== null ? $fecha_baja : "Desconocida";
                    if ($reservas_clases !== null) $socio['reservas_clases'] = $reservas_clases;
                    if ($cuenta_bancaria !== null) $socio['cuenta_bancaria'] = $cuenta_bancaria;
    
                    break;
                }
            }
    
            // Si no se encontró el socio, lanzar excepción
            if (!$socioEncontrado) {
                throw new Exception("No se encontró un socio con el DNI: $dni_socio");
            }
    
            // Guardar los cambios en el archivo JSON
            file_put_contents($rutaJSON, json_encode($socios, JSON_PRETTY_PRINT));
    
            return "El socio con DNI $dni_socio ha sido modificado correctamente.";
    
        } catch (Exception $e) {
            return "<b>Error:</b> " . $e->getMessage();
        }
    }
    
    private function guardarSocioEnJSON() {
        $rutaJSON = __DIR__ . '/../data/socios.json';
    
        // Leer el contenido actual del archivo JSON
        $sociosJson = file_exists($rutaJSON) ? json_decode(file_get_contents($rutaJSON), true) : [];
    
        // Actualizar o agregar este socio al JSON
        $actualizado = false;
        foreach ($sociosJson as &$socioJson) {
            if ($socioJson['dni'] === $this->dni) {
                $socioJson = [
                    'dni' => $this->dni,
                    'nombre' => $this->nombre,
                    'apellidos' => $this->apellidos,
                    'fecha_nac' => $this->fecha_nac,
                    'telefono' => $this->telefono,
                    'email' => $this->email,
                    'tarifa' => $this->tarifa,
                    'fecha_alta' => $this->fecha_alta,
                    'fecha_baja' => $this->fecha_baja,
                    'reservas_clases' => $this->reservas_clases,
                ];
                $actualizado = true;
                break;
            }
        }
    
        // Si no se actualizó, agregar el socio como nuevo
        if (!$actualizado) {
            $sociosJson[] = [
                'dni' => $this->dni,
                'nombre' => $this->nombre,
                'apellidos' => $this->apellidos,
                'fecha_nac' => $this->fecha_nac,
                'telefono' => $this->telefono,
                'email' => $this->email,
                'tarifa' => $this->tarifa,
                'fecha_alta' => $this->fecha_alta,
                'fecha_baja' => $this->fecha_baja,
                'reservas_clases' => $this->reservas_clases,
            ];
        }
    
        // Guardar en el archivo JSON
        file_put_contents($rutaJSON, json_encode($sociosJson, JSON_PRETTY_PRINT));
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
